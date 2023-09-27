<?php

namespace App\Http\Controllers\expedientes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UsuariosSuperController extends Controller
{
    public function listadoUsuarios() {
        $usuarios = DB::table('users')->get();
    
        foreach ($usuarios as $usuario) {
            switch ($usuario->rol) {
                case 1:
                    $usuario->rol = 'Usuario';
                    break;
                case 2:
                    $usuario->rol = 'Administrador';
                    break;
                case 3:
                    $usuario->rol = 'Super Administrador';
                    break;
                default:
                    // Puedes manejar un valor por defecto en caso de que no se cumpla ninguno de los casos anteriores
                    $usuario->rol = 'Rol Desconocido';
            }
        }
    
        return view('expedientes.usuarios.homeUsuariosSuper', ['elementos' => $usuarios]);
    }

    public function nuevo(){
        return view('expedientes.usuarios.usuariosCrear');
    }

    public function store(Request $request)
    {
        // Obtén los datos del formulario
        $nombre = $request->input('nombre');
        $apellidos = $request->input('apellidos');
        $rol = $request->input('rol');
        $otros_datos = $request->input('otros_datos');
    
       // Obtén los valores de los permisos del formulario como un array
$permisos = $request->input('permisos', []);

// Define los nombres de los permisos
$permisosNombres = [
    'expediente_registrar',
    'expediente_consultar',
    'expediente_editar',
    'expediente_eliminar',
    'expediente_reportes',
    'guardavalor_registrar',
    'guardavalor_retirar',
    'guardavalor_editar',
    'guardavalor_consultar',
    'guardavalor_reportes',
];

// Inicializa un array para almacenar los valores de los permisos
$valoresPermisos = [];

// Itera sobre los nombres de los permisos y verifica si están presentes en el array de permisos
foreach ($permisosNombres as $permisoNombre) {
    $valoresPermisos[$permisoNombre] = in_array($permisoNombre, $permisos);
}

// Inserta el usuario en la tabla 'usuarioSistema' con los permisos
$ultimoInsertado = DB::table('users')->insertGetId([
    'nombre' => $nombre,
    'apellidos' => $apellidos,
    'registroHuellaDigital' => 12345,
    'otros_datos' => $otros_datos,
    'rol' => $rol,
    'registrarExpediente' => $valoresPermisos['expediente_registrar'],
    'consultarExpediente' => $valoresPermisos['expediente_consultar'],
    'editarExpediente' => $valoresPermisos['expediente_editar'],
    'eliminarExpediente' => $valoresPermisos['expediente_eliminar'],
    'reportesExpediente' => $valoresPermisos['expediente_reportes'],
    'registrarGuardavalores' => $valoresPermisos['guardavalor_registrar'],
    'retirarGuardavalores' => $valoresPermisos['guardavalor_retirar'],
    'editarGuardavalores' => $valoresPermisos['guardavalor_editar'],
    'consultarGuardavalores' => $valoresPermisos['guardavalor_consultar'],
    'reportesGuardavalores' => $valoresPermisos['guardavalor_reportes'],
]);

// Redirige a la página de inicio de usuarios
return redirect()->route('homeUsuarios');

    }
    
    
    public function search(Request $request){

        $query = $request->input('usuario');
    
        if (is_numeric($query)) {
            // Si la entrada es un número, busca por ID
            $usuarios = DB::table('users')->where('idUsuarioSistema', $query)->get();
        } else {
            // Si la entrada no es un número, busca por nombre o apellidos
            $usuarios = DB::table('users')->where('nombre', 'LIKE', "%$query%")
                                ->orWhere('apellidos', 'LIKE', "%$query%")
                                ->get();
        }
        if ($usuarios->isEmpty()) {
            // Si no se encontraron coincidencias, obtén todos los registros
            $usuarios = DB::table('users')->get();
            $mensaje = "No se encontraron coincidencias. Mostrando todos los registros.";
        }
    
        return view('expedientes.usuarios.homeUsuariosSuper', ['elementos' => $usuarios, 'mensaje' => $mensaje ?? null]);
    }


    public function detallesUsuario($id) {
        $usuario = DB::table('users')->where('idUsuarioSistema', $id)->first();
        return view('expedientes.usuarios.detallesUsuario', ['usuario' => $usuario]);
    }

    public function borrar($id) {
    }
    
    
}
