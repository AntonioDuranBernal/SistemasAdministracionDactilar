<?php

namespace App\Http\Controllers\expedientes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UsuariosSuperController extends Controller
{
    
    public function __construct(){
        $this->middleware('auth');
    }

    public function edit($usuario){
        $usuario = DB::table('users')->where('idUsuarioSistema', $usuario)->first();        
    return view('expedientes.usuarios.edit', ['usuario' => $usuario]);
    }
    
    public function update(Request $request, $id) {

        $request->validate([
            'nombre' => 'required',
            'apellidos' => 'required',
            'rol' => 'required',
        ]);
    
        // Recoge los datos del formulario
        $apellidos = $request->input('apellidos');
        $otrosDatos = $request->input('otros_datos');
        $rol = $request->input('rol');
    
        $permisosValues = [
            'registrarExpediente' => $request->has('expediente_registrar') ? 1 : 0,
            'consultarExpediente' => $request->has('expediente_consultar') ? 1 : 0,
            'editarExpediente' => $request->has('expediente_editar') ? 1 : 0,
            'eliminarExpediente' => $request->has('expediente_eliminar') ? 1 : 0,
            'reportesExpediente' => $request->has('expediente_reportes') ? 1 : 0,
            'registrarGuardavalores' => $request->has('guardavalor_registrar') ? 1 : 0,
            'retirarGuardavalores' => $request->has('guardavalor_retirar') ? 1 : 0,
            'editarGuardavalores' => $request->has('guardavalor_editar') ? 1 : 0,
            'consultarGuardavalores' => $request->has('guardavalor_consultar') ? 1 : 0,
            'reportesGuardavalores' => $request->has('guardavalor_reportes') ? 1 : 0,
        ];
    
        DB::table('users')
            ->where('idUsuarioSistema', $id)
            ->update([
                'apellidos' => $apellidos,
                'otros_datos' => $otrosDatos,
                'rol' => $rol,
            ] + $permisosValues);
    
        return redirect()->route('detallesUsuario', $id);
    }
    

    public function listadoUsuarios() {
        $usuarios = DB::table('users')->get();    
        return view('expedientes.usuarios.homeUsuariosSuper', ['elementos' => $usuarios]);
    }

    public function nuevo(){
        return view('expedientes.usuarios.usuariosCrear');
    }

    public function store(Request $request)
    {
        try {

        $request->validate([
            'nombre' => 'required',
            'apellidos' => 'required',
            'rol' => 'required',
        ]);

        $huella = $request->input('huella');
        $nombre = $request->input('nombre');
        $apellidos = $request->input('apellidos');
        $rol = $request->input('rol');
        $otros_datos = $request->input('otros_datos');
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
    
        // Inserta el usuario en la tabla 'usuarioSistema' con los permisos y la huella digital
        $ultimoInsertado = DB::table('users')->insertGetId([
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'registroHuellaDigital' => $huella, // Agrega la huella digital aquí
            //'registroHuellaDigital' => app('hash')->make($otros_datos),
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
            'password' => app('hash')->make($otros_datos),
        ]);
    
        DB::table('users')->where('idUsuarioSistema', $ultimoInsertado)->update(['id' => $ultimoInsertado]);
        DB::table('users')->where('idUsuarioSistema', $ultimoInsertado)->update(['email' => $ultimoInsertado]);
        
        session()->flash('success', 'Usuario registrado con éxito, número de usuario: '.$ultimoInsertado);
        return redirect()->route('homeUsuarios');

    } catch (\Exception $e) {
        session()->flash('error', 'Ocurrió un error al registrar el usuario: ' . $e->getMessage());
        return redirect()->route('homeUsuarios');
    }
    }
    
    

    public function volverHomeSegunArea() {

        $idUser = DB::table('users')
            ->where('idUsuarioSistema', auth()->id()) // Filtrar por el ID del usuario autenticado
            ->value('idUsuarioSistema');
            
        $user = DB::table('users')->where('idUsuarioSistema', $idUser)->first();
        $area = $user->area;

        if($area==2){
            return redirect()->route('expedientesGV',$idUser);
        }else{
            return redirect()->route('expedientesBasico',$idUser);
        }
    }



    
    public function search(Request $request){

        $query = $request->input('usuario');
    
        if (is_numeric($query)) {
            $usuarios = DB::table('users')->where('idUsuarioSistema', $query)->get();
        } else {
            // Si la entrada no es un número, busca por nombre o apellidos
            $usuarios = DB::table('users')->where('nombre', 'LIKE', "%$query%")
                                ->orWhere('apellidos', 'LIKE', "%$query%")
                                ->get();
        }
        if ($usuarios->isEmpty()) {
            $usuarios = DB::table('users')->get();
            session()->flash('info', 'No se encontraron coincidencias.');
            return view('expedientes.usuarios.homeUsuariosSuper', ['elementos' => $usuarios, 'mensaje' => $mensaje ?? null]);
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
