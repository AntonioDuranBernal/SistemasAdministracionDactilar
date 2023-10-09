<?php

namespace App\Http\Controllers\expedientes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class ExpedientesSuperController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function actualizarExp(Request $request, $id_expediente)
{
    // Valida los datos del formulario
    /*$request->validate([
        'nombreDocumento' => 'required',
        'descripcion' => 'required',
        // Agrega más reglas de validación según tus necesidades
    ]);*/

    // Actualiza los datos del expediente utilizando consultas de SQL
    DB::table('expedientes')
        ->where('id_expediente', $id_expediente)
        ->update([
            'nombre' => $request->input('nombreDocumento'),
            'descripcion' => $request->input('descripcion'),
            'folio_real' => $request->input('folioReal'),
            'otros_datos' => $request->input('otrosDatos'),
        ]);

    return redirect()->route('homeExpedientes')->with('success', 'Expediente actualizado correctamente');
}


    
    public function devolverExpediente($id_e,$id_u,$id_a){

                // Actualizar el estado a 'Devolución atrasada'
                    DB::table('actividad_expedientes')
                    ->where('id_actividad', $id_a)
                    ->update(['estado' => 'Disponible']);
    
                DB::table('expedientes')
                    ->where('id_expediente',$id_e)
                    ->update(['estado' => 'Disponible']); 

                    return redirect()->route('expedientesBasico',$id_u);

    }

    public function editarExp($id_expediente){
    $expediente = DB::table('expedientes')->where('id_expediente',$id_expediente)->first();
    
    // Verifica si se encontró el expediente
    if (!$expediente) {
        return redirect()->route('homeExpedientes')->with('error', 'Expediente no encontrado');
    }

    return view('expedientes.expedientes.editarExpediente', compact('expediente'));
    }

    
    public function eliminarExpediente($id)
    {
        //$usuario = Auth::user();
        //$idUser = $usuario->idUsuarioSistema;
        //$nombre = $usuario->nombre;
    
        $idUser = 7;

        DB::table('expedientes')->where('id_expediente', $id)->delete();
        
        return redirect()->route('homeClientesUsuario',$idUser);
    }
    
    
    public function inicioExpedientes(){

        //$usuario = Auth::user();
        //$idUser = $usuario->idUsuarioSistema;
        //$nombre = $usuario->nombre;
        //$idRol = $usuario->rol;

        $idUser = 1;
        $nombre = 'ES BASICO EN EXPEDIENTES SUPER CONTROLLER';
        $idRol = 3;

        echo "USUARIO LOGEADOO: ".$idUser. " NOMBRE: ".$nombre;

        $user = DB::table('users')->where('idUsuarioSistema', $idUser)->first();
        $consulta = DB::table('users')
        ->select('registrarExpediente', 'consultarExpediente', 'editarExpediente', 'eliminarExpediente', 'reportesExpediente')
        ->where('idUsuarioSistema', $idUser)
        ->first();
    
        $permisos = (array) $consulta;
        $permisosUsuario = [];
        foreach ($permisos as $indice => $valor) {
        $permisosUsuario[] = ['indice' => $indice, 'valor' => $valor];
        }

        foreach ($permisos as $permiso) {
            echo $permiso . " ";
        }
        $elementosActualizados = [];

        if ($idRol==1) {
                        //CODIGO PARA USUARIO BASICO
                        $elementos = DB::table('actividad_expedientes')//CONSULTA PARA OBTENER LA ACTIVIDAD PARA MOSTAR EN ADMINISTRADOR
                        ->join('expedientes', 'actividad_expedientes.id_expediente', '=', 'expedientes.id_expediente')
                        ->select('actividad_expedientes.*', 'expedientes.nombre AS nombre')
                        ->where('id_usuario_solicita', $idUser)
                        ->get();
        } else {

                        //CODIGO PARA USUARIO SUPER O ADMIN
                        $elementos = DB::table('actividad_expedientes')//CONSULTA PARA OBTENER LA ACTIVIDAD PARA MOSTAR EN ADMINISTRADOR
                        ->join('expedientes', 'actividad_expedientes.id_expediente', '=', 'expedientes.id_expediente')
                        ->select('actividad_expedientes.*', 'expedientes.nombre AS nombre')
                        ->get();
        }

        if ($elementos) {
            foreach ($elementos as $elemento) {
    
                $nombreExpediente = DB::table('expedientes')
                    ->where('id_expediente', $elemento->id_expediente)
                    ->value('nombre');
        
                $nombreUsuario = DB::table('users')
                    ->where('idUsuarioSistema', $elemento->id_usuario_solicita)
                    ->value('nombre');
        
                // Obtener los datos originales del elemento
                $elementoOriginal = (array) $elemento;
        
                // Formatear las fechas en día, mes y año
                $elementoOriginal['fecha_solicitud'] = date('d-m-Y', strtotime($elemento->fecha_solicitud));
                $elementoOriginal['fecha_devolucion'] = date('d-m-Y', strtotime($elemento->fecha_devolucion));
        
                // Actualizar los campos necesarios
                $elementoOriginal['id_expediente'] = $nombreExpediente;
                $elementoOriginal['id_usuario_solicita'] = $nombreUsuario;
    
                // Verificar y actualizar el estado si es necesario
                if ($elementoOriginal['estado'] == 'En uso') {
                    // Calcular la diferencia en días entre la fecha de devolución y la fecha actual
                    $fechaDevolucion = strtotime($elemento->fecha_devolucion);
                    $fechaActual = strtotime(date('Y-m-d'));
                    echo "fecha dev: " . $fechaDevolucion . " fecha actual ".$fechaActual;
                    $diferenciaDias = floor(($fechaDevolucion - $fechaActual) / (60 * 60 * 24));
                
                    if ($diferenciaDias < 0) {
                        // Actualizar el estado a 'Devolución atrasada'
                        DB::table('actividad_expedientes')
                            ->where('id_actividad', $elemento->id_actividad)
                            ->update(['estado' => 'Devolución atrasada']);
                
                        DB::table('expedientes')
                            ->where('id_expediente', $elemento->id_expediente)
                            ->update(['estado' => 'Devolución atrasada']);
                
                        // Actualizar el estado en el elemento original
                        $elementoOriginal['estado'] = 'Devolución atrasada';
                    }
                }
        
                // Agregar el registro actualizado al arreglo
                $elementosActualizados[] = (object) $elementoOriginal;
            }
        }

        return view('expedientes.super.homeAdmin', ['elementos' => $elementosActualizados,'permisosUsuario' => $permisosUsuario, 'usuario' => $idUser, 'idRol' => $idRol]);

    }
       

        public function inicioExpedientesANTIGUO(){

        //separacion codigo original

        $elementos = DB::table('actividad_expedientes')->get();

        $elementosActualizados = [];
    
        foreach ($elementos as $elemento) {
    
            $nombreExpediente = DB::table('expedientes')
                ->where('id_expediente', $elemento->id_expediente)
                ->value('nombre');
    
            $nombreUsuario = DB::table('users')
                ->where('idUsuarioSistema', $elemento->id_usuario_solicita)
                ->value('nombre');
    
            // Obtener los datos originales del elemento
            $elementoOriginal = (array) $elemento;
    
            // Formatear las fechas en día, mes y año
            $elementoOriginal['fecha_solicitud'] = date('d-m-Y', strtotime($elemento->fecha_solicitud));
            $elementoOriginal['fecha_devolucion'] = date('d-m-Y', strtotime($elemento->fecha_devolucion));
    
            // Actualizar los campos necesarios
            $elementoOriginal['id_expediente'] = $nombreExpediente;
            $elementoOriginal['id_usuario_solicita'] = $nombreUsuario;

            // Verificar y actualizar el estado si es necesario
            if ($elementoOriginal['estado'] == 'En uso' && strtotime($elemento->fecha_devolucion) < strtotime(date('Y-m-d H:i:s'))) {
            // Actualizar el estado a 'Devolución atrasada'
            DB::table('actividad_expedientes')
                ->where('id_actividad', $elemento->id_actividad)
                ->update(['estado' => 'Devolución atrasada']);

            DB::table('expedientes')
                ->where('id_expediente',$elemento->id_expediente)
                ->update(['estado' => 'Devolución atrasada']);

            // Actualizar el estado en el elemento original
            $elementoOriginal['estado'] = 'Devolución atrasada';
            }
    
            // Agregar el registro actualizado al arreglo
            $elementosActualizados[] = (object) $elementoOriginal;
        }

        return view('expedientes.super.homeSuper', ['elementos' => $elementosActualizados]);
    }

    public function listadoExpedientes() {
        $elementos = DB::table('expedientes')->get();
    
        // Recorrer los elementos y truncar los campos "descripcion" y "nombre de expediente"
        foreach ($elementos as $elemento) {
            $elemento->descripcion = mb_substr($elemento->descripcion, 0, 25); // Truncar a 25 caracteres
            $elemento->nombre = mb_substr($elemento->nombre, 0, 25); // Truncar a 25 caracteres
        }
    
        return view('expedientes.expedientes.homeExpedientesListado', ['elementos' => $elementos]);
    }


    public function search(Request $request) {
        $search_term = $request->input('id_expediente');
    
        // Verifica si el término de búsqueda es un número o un nombre
        if (is_numeric($search_term)) {
            // Si es un número, busca por ID
            $expedientes = DB::table('expedientes')
                ->where('id_expediente', $search_term)
                ->get();
        } else {
            // Si no es un número, busca por nombre
            $expedientes = DB::table('expedientes')
                ->where('nombre', 'LIKE', "%$search_term%")
                ->get();
        }
    
        if ($expedientes->isEmpty()) {
            return redirect()->route('homeExpedientes')->with('error', 'No se encontró ningún expediente con el término proporcionado.');
        }
    
        return view('expedientes.expedientes.homeExpedientesListado', ['elementos' => $expedientes]);
    }

    public function detallesExpedienteBasico($id_exp,$id_usuario){
        $expediente = DB::table('expedientes')
        ->where('id_expediente', $id_exp)
        ->first();

    // Verificar si el expediente existe en la base de datos
    if (!$expediente) {
        return redirect()->route('homeClientesUsuario',$id_usuario)->with('error', 'No se encontró ningún detalle de ese expediente.');
    }

      // Obtener el cliente al que pertenece el expediente
      $clienteNombre = DB::table('clientes_expedientes')
      ->where('id_cliente', $expediente->id_cliente)
      ->value('nombre');

     // Verificar si se encontró el cliente
    if (!$clienteNombre) {
    return redirect()->route('homeClientesUsuario',$id_usuario)->with('error', 'No se encontró ningún detalle de ese expediente.');
    }

    // Obtener el usuario que registró el expediente
    $usuarioCreador = DB::table('users')
      ->where('idUsuarioSistema', $expediente->usuario_creador)
      ->value('nombre'); // Cambia 'nombre_usuario' por el nombre de la columna que contiene el nombre del usuario

    $expediente->id_cliente = $clienteNombre;
    $expediente->usuario_creador = $usuarioCreador;
    $expediente->fecha_creacion = date('d-m-Y', strtotime($expediente->fecha_creacion));



    $consulta = DB::table('users')
    ->select('registrarExpediente', 'consultarExpediente', 'editarExpediente', 'eliminarExpediente', 'reportesExpediente')
    ->where('idUsuarioSistema', $id_usuario)
    ->first();

    $permisos = (array) $consulta;
    $permisosUsuario = [];

    foreach ($permisos as $indice => $valor) {
    $permisosUsuario[] = ['indice' => $indice, 'valor' => $valor];
    }

     return view('expedientes.expedientes.detallesExpUserBasico', ['id_usuario' => $id_usuario, 'expediente' => $expediente, 'permisosUsuario' => $permisosUsuario]);

    }

    public function detallesExpediente($id_exp) {
        // Buscar el expediente por su nombre en la base de datos
        $expediente = DB::table('expedientes')
            ->where('id_expediente',$id_exp)
            ->first();
    
        // Verificar si el expediente existe en la base de datos
        if (!$expediente) {
            return redirect()->route('homeExpedientes')->with('error', 'No se encontró ningún detalle de ese expediente.');
        }
    
        // Obtener el cliente al que pertenece el expediente
        $clienteNombre = DB::table('clientes_expedientes')
            ->where('id_cliente', $expediente->id_cliente)
            ->value('nombre');
    
        // Verificar si se encontró el cliente
        if (!$clienteNombre) {
            return redirect()->route('homeExpedientes')->with('error', 'No se encontró ningún cliente asociado a este expediente.');
        }
    
        // Obtener el usuario que registró el expediente
        $usuarioCreador = DB::table('users')
            ->where('idUsuarioSistema', $expediente->usuario_creador)
            ->value('nombre'); // Cambia 'nombre_usuario' por el nombre de la columna que contiene el nombre del usuario
    
        $expediente->id_cliente = $clienteNombre;
        $expediente->usuario_creador = $usuarioCreador;
        $expediente->fecha_creacion = date('d-m-Y', strtotime($expediente->fecha_creacion));


        return view('expedientes.expedientes.detalles', ['expediente' => $expediente]);
    }

    public function solicitarExpUB($id_exp, $id_usuario){
        // Buscar el expediente por su nombre en la base de datos
        $expediente = DB::table('expedientes')
            ->where('id_expediente', $id_exp)
            ->first();
    
        // Verificar si el expediente existe en la base de datos
        if (!$expediente) {
            return redirect()->route('expedientesBasico',$id_exp)->with('error', 'No se encontró ningún detalle de ese expediente.');
        }

        // Obtener el cliente al que pertenece el expediente
        $clienteNombre = DB::table('clientes_expedientes')
            ->where('id_cliente', $expediente->id_cliente)
            ->value('nombre');

            // Verificar si se encontró el cliente
        if (!$clienteNombre) {
            return redirect()->route('expedientesBasico',$id_exp)->with('error', 'No se encontró cliente.');
        }
    
        // Obtener el usuario que registró el expediente
        $usuarioCreador = DB::table('users')
            ->where('idUsuarioSistema', $expediente->usuario_creador)
            ->value('nombre'); // Cambia 'nombre_usuario' por el nombre de la columna que contiene el nombre del usuario
    
        $expediente->id_cliente = $clienteNombre;
        $expediente->usuario_creador = $usuarioCreador;

        $fecha = now()->format('Y-m-d');

        return view('expedientes.expedientes.solicitarExpUB', ['expediente' => $expediente, 'id_usuario' => $id_usuario, 'fecha' => $fecha]);

    }
    
    public function solicitar($id_exp) {
        // Buscar el expediente por su nombre en la base de datos
        $expediente = DB::table('expedientes')
            ->where('id_expediente', $id_exp)
            ->first();
    
        // Verificar si el expediente existe en la base de datos
        if (!$expediente) {
            return redirect()->route('homeExpedientes')->with('error', 'No se encontró ningún detalle de ese expediente.');
        }
    
        // Obtener el cliente al que pertenece el expediente
        $clienteNombre = DB::table('clientes_expedientes')
            ->where('id_cliente', $expediente->id_cliente)
            ->value('nombre');
    
        // Verificar si se encontró el cliente
        if (!$clienteNombre) {
            return redirect()->route('homeExpedientes')->with('error', 'No se encontró ningún cliente asociado a este expediente.');
        }
    
        // Obtener el usuario que registró el expediente
        $usuarioCreador = DB::table('users')
            ->where('idUsuarioSistema', $expediente->usuario_creador)
            ->value('nombre'); // Cambia 'nombre_usuario' por el nombre de la columna que contiene el nombre del usuario
    
        $expediente->id_cliente = $clienteNombre;
        $expediente->usuario_creador = $usuarioCreador;

        $fecha = now()->format('Y-m-d');

        //date_default_timezone_set('America/Mexico_City');
        //$fecha = date("Y-m-d h:m:s");
        //$fecha = date("Y-m-d",strtotime($fecha."- 1 days"));

        return view('expedientes.expedientes.solicitar', ['expediente' => $expediente, 'fecha' => $fecha]);
    }
    

    public function almacenarActividad(Request $request)
    {

        // Obtén los valores de los campos del formulario
        $id_expediente = $request->input('id_expediente');
        $fecha_devolucion = $request->input('fecha_devolucion');
        $motivo = $request->input('motivo');
        
        // Obtiene la fecha actual
        $fecha_solicitud = now();

        //$usuario = Auth::user();
        //$idUsuarioSistema = $usuario->idUsuarioSistema;
        //$nombre = $usuario->nombre;
        $idUsuarioSistema = 1;
    
        // Crea un nuevo registro en la tabla actividad_expediente
        DB::table('actividad_expedientes')->insert([
            'id_usuario_otorga' => 1, // Valor fijo
            'id_usuario_solicita' => $idUsuarioSistema, // Valor fijo
            'fecha_solicitud' => $fecha_solicitud,
            'fecha_devolucion' => $fecha_devolucion,
            'motivo' => $motivo,
            'id_expediente' => $id_expediente,
            'estado'=>'En uso',
        ]);

        DB::table('expedientes')
        ->where('id_expediente', $id_expediente)
        ->update(['estado' => 'En uso']);
    
        return redirect()->route('homeAdminExpedientes')->with('success', 'Actividad almacenada correctamente');
    }

    public function almacenarActividadUsuarioBasico(Request $request)
    {
        // Obtén los valores de los campos del formulario
        $id_expediente = $request->input('id_expediente');
        $fecha_devolucion = $request->input('fecha_devolucion');
        $motivo = $request->input('motivo');
        $id_usuario = $request->input('id_usuario');
        
        // Obtiene la fecha actual
        $fecha_solicitud = now();

        //$usuario = Auth::user();
        //$idUsuarioSistema = $usuario->idUsuarioSistema;
        //$nombre = $usuario->nombre;
        $idUsuarioSistema = 7;
    
        // Crea un nuevo registro en la tabla actividad_expediente
        DB::table('actividad_expedientes')->insert([
            'id_usuario_otorga' => 1, // Valor fijo
            'id_usuario_solicita' => $idUsuarioSistema, // Valor fijo
            'fecha_solicitud' => $fecha_solicitud,
            'fecha_devolucion' => $fecha_devolucion,
            'motivo' => $motivo,
            'id_expediente' => $id_expediente,
            'estado'=>'En uso',
        ]);

        DB::table('expedientes')
        ->where('id_expediente', $id_expediente)
        ->update(['estado' => 'En uso']);


        return redirect()->route('expedientesBasico',$id_usuario)->with('success', 'Actividad almacenada correctamente');
    }

    public function homeExpedientesUB($id_usuario) {
        $elementos = DB::table('expedientes')
        ->orderBy('fecha_creacion', 'desc')
        ->get();
        
        // Recorrer los elementos y truncar los campos "descripcion" y "nombre de expediente"
        foreach ($elementos as $elemento) {
            $elemento->descripcion = mb_substr($elemento->descripcion, 0, 25); // Truncar a 25 caracteres
            $elemento->nombre = mb_substr($elemento->nombre, 0, 25); // Truncar a 25 caracteres
        }
    
        return view('expedientes.expedientes.homeExpedientesListadoUB', ['elementos' => $elementos, 'id_usuario' => $id_usuario]);
    }

    
    
    
    
    

}
