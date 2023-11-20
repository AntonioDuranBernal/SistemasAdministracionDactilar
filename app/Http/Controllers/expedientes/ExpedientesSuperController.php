<?php

namespace App\Http\Controllers\expedientes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Auth;
use Auth;


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

        session()->flash('success', 'El expediente fue actualizado.');
    return redirect()->route('homeExpedientes');
}


    
    public function devolverExpediente($id_e,$id_u,$id_a){

        $fecha = date('Y-m-d');

        $idUser = DB::table('users')
        ->where('idUsuarioSistema', auth()->id())
        ->value('idUsuarioSistema');

        $nombreUser = DB::table('users')
        ->select(DB::raw("CONCAT(nombre, ' ', apellidos) as nombre_completo"))
        ->where('idUsuarioSistema', auth()->id())
        ->value('nombre_completo');
    
                DB::table('expedientes') //EXPEDIENTES
                    ->where('id_expediente',$id_e)
                    ->update(['estado' => 'Disponible',
                    'usuario_posee' => null,
                ]); 

                
                DB::table('actividad_expedientes') //ACTIVIDAD
                    ->where('id_actividad', $id_a)
                    ->update(['estado' => 'Disponible',
                ]);

                $act = DB::table('actividad_expedientes') 
                ->where('id_actividad', $id_a)
                ->first();

                DB::table('actividad_expedientes')->insert([
                    'estado' => 'Disponible',
                    'id_expediente' => $id_e,
                    'fecha_entrega' => $fecha,
                    'fecha_actividad' => $fecha,
                    'Movimiento' => 'Devolución',
                    'Motivo' => 'Devolución',
                    'fecha_entrega' => $fecha,
                    'fecha_solicitud' => $act->fecha_solicitud,
                    'fecha_devolucion' => $act->fecha_devolucion,
                    'id_usuario_solicita' => $act->id_usuario_solicita,
                    'id_usuario_realiza'=> $nombreUser,
                ]);



                return redirect()->route('expedientesBasico',$idUser);

    }

    public function editarExp($id_expediente){
    $expediente = DB::table('expedientes')->where('id_expediente',$id_expediente)->first();
    
    // Verifica si se encontró el expediente
    if (!$expediente) {
        session()->flash('info', 'Expediente no encontrado.');
        return redirect()->route('homeExpedientes');
    }
    
    return view('expedientes.expedientes.editarExpediente', compact('expediente'));
    }


    public function borrarUsuario($IUE)
    {
        $idUser = DB::table('users')
            ->where('idUsuarioSistema', auth()->id()) // Filtrar por el ID del usuario autenticado
            ->value('idUsuarioSistema');

        DB::table('users')->where('idUsuarioSistema', $IUE)->delete();
        
        return redirect()->route('volverHomeSegunArea');
    }

    
    public function eliminarExpediente($id)
    {
        $idUser = DB::table('users')
            ->where('idUsuarioSistema', auth()->id()) // Filtrar por el ID del usuario autenticado
            ->value('idUsuarioSistema');

        DB::table('expedientes')->where('id_expediente', $id)->delete();
        
        return redirect()->route('homeClientesUsuario',$idUser);
    }
    
    
    public function inicioExpedientes(){

        $idUser = DB::table('users')
                ->where('idUsuarioSistema', auth()->id()) // Filtrar por el ID del usuario autenticado
                ->value('idUsuarioSistema');

        DB::table('users')   
        ->where('idUsuarioSistema', $idUser)
        ->update(['area' => 1]);

        $user = DB::table('users')->where('idUsuarioSistema', $idUser)->first();
        $idRol = $user->rol;
        $nombre = $user->nombre;

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
        
        $elementosActualizados = [];

        if ($idRol==1) {
            $fechaLimite = now()->subDays(5); // Obtener la fecha límite (hoy - 3 días)
            $elementos = DB::table('actividad_expedientes')
                ->join('expedientes', 'actividad_expedientes.id_expediente', '=', 'expedientes.id_expediente')
                ->select('actividad_expedientes.*', 'expedientes.nombre AS nombre')
                ->where('id_usuario_solicita', $idUser)
                ->where('fecha_solicitud', '>=', $fechaLimite) // Filtrar por los últimos 3 días
                ->orderBy('fecha_solicitud', 'desc') // Ordenar por fecha_solicitud de mayor a menor
                ->get();
            
        
        } else {
            $fechaLimite = now()->subDays(5); // Obtener la fecha límite (hoy - 3 días)
            $elementos = DB::table('actividad_expedientes')
                ->join('expedientes', 'actividad_expedientes.id_expediente', '=', 'expedientes.id_expediente')
                ->select('actividad_expedientes.*', 'expedientes.nombre AS nombre')
                ->where('fecha_solicitud', '>=', $fechaLimite) // Filtrar por los últimos 3 días
                ->orderBy('fecha_solicitud', 'desc') // Ordenar por fecha_solicitud de mayor a menor
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

                    $numExpe = DB::table('clientes_expedientes')
                    ->join('expedientes', 'clientes_expedientes.id_consecutivo', '=', 'expedientes.id_cliente')
                    ->where('expedientes.id_expediente', $elemento->id_expediente)
                    ->value('clientes_expedientes.nombre');

                    $numExpeCliente = DB::table('clientes_expedientes')
                    ->join('expedientes', 'clientes_expedientes.id_cliente', '=', 'expedientes.id_cliente')
                    ->where('expedientes.id_expediente', $elemento->id_expediente)
                    ->value('clientes_expedientes.id_cliente');
                
        
                // Obtener los datos originales del elemento
                $elementoOriginal = (array) $elemento;
        
                // Formatear las fechas en día, mes y año
                $elementoOriginal['fecha_solicitud'] = date('d-m-Y', strtotime($elemento->fecha_solicitud));
                $elementoOriginal['fecha_devolucion'] = date('d-m-Y', strtotime($elemento->fecha_devolucion));
        
                // Actualizar los campos necesarios
                $elementoOriginal['OtroDato'] = $nombreExpediente;
                $elementoOriginal['id_usuario_otorga'] = $nombreUsuario;
                $elementoOriginal['id_cliente']= $numExpe;
                $elementoOriginal['tomo']= $numExpeCliente;
                $elementoOriginal['id_expediente']= $elemento->id_expediente;
    
                // Verificar y actualizar el estado si es necesario
                if ($elementoOriginal['estado'] == 'En uso') {

                    $fechaDevolucion = strtotime($elemento->fecha_devolucion);
                    $fechaDevolucionFormateada = date('Y-m-d', $fechaDevolucion);

                    date_default_timezone_set('America/Mexico_City');
                    $fechaActual = date('Y-m-d');
                    
                    if (strtotime($fechaDevolucionFormateada) < strtotime($fechaActual)) {
                        //if ($fechaActual > $fechaDevolucionFormateada) {
                        // La fecha actual es mayor que la fecha de devolución, actualizar el estado a 'Devolución atrasada'
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

        return view('expedientes.super.homeAdmin', ['elementos' => $elementosActualizados,'permisosUsuario' => $permisosUsuario, 'usuarioActual' => $idUser, 'idRol' => $idRol, 'nombre'=>$nombre]);

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

            date_default_timezone_set('America/Mexico_City');
            $fecha_hoy = date('Y-m-d');

            // Verificar y actualizar el estado si es necesario
            if ($elementoOriginal['estado'] == 'En uso' && strtotime($elemento->fecha_devolucion) < $fecha_hoy) {

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
        $elementos = DB::table('expedientes')
        ->orderBy('fecha_creacion', 'desc')
        ->get();
    
        
        // Recorrer los elementos y truncar los campos "descripcion" y "nombre de expediente"
        foreach ($elementos as $elemento) {
            $elemento->descripcion = mb_substr($elemento->descripcion, 0, 30); // Truncar a 25 caracteres
            $elemento->nombre = mb_substr($elemento->nombre, 0, 30); // Truncar a 25 caracteres
        }
    
        return view('expedientes.expedientes.homeExpedientesListado', ['elementos' => $elementos]);
    }

    public function homeExpedientesUB($id_usuario) {

        $elementos = DB::table('expedientes')
    ->orderBy('fecha_creacion', 'desc')
    ->get();

        
        // Recorrer los elementos y truncar los campos "descripcion" y "nombre de expediente"
        foreach ($elementos as $elemento) {
            $elemento->descripcion = mb_substr($elemento->descripcion, 0, 30); // Truncar a 25 caracteres
            $elemento->nombre = mb_substr($elemento->nombre, 0, 30); // Truncar a 25 caracteres
        }
    
        return view('expedientes.expedientes.homeExpedientesListadoUB', ['elementos' => $elementos, 'id_usuario' => $id_usuario]);
    }

    public function search(Request $request) {
        $search_term = $request->input('id_expediente');
    
        if (preg_match('/^\d+$/', $search_term)) {
            // Si el término de búsqueda es solo un número, busca en el campo 'id_cliente'
            $expedientes = DB::table('expedientes')
                ->where('id_cliente', $search_term)
                ->get();
        } elseif (preg_match('/^\d+-\d+$/', $search_term)) {
            // Si el término de búsqueda tiene el formato "número-número", busca en el campo 'nombre'
            $expedientes = DB::table('expedientes')
                ->where('nombre', $search_term)
                ->get();
        } else {
            // Si no es un número, busca por nombre
            $expedientes = DB::table('expedientes')
                ->where('descripcion', 'LIKE', "%$search_term%")
                ->get();
        }
    
        if ($expedientes->isEmpty()) {
            return redirect()->route('homeExpedientes')->with('error', 'No se encontró ningún tomo con el término proporcionado.');
        }
    
        // Modificar los resultados para incluir el nombre completo
        $expedientes = $expedientes->map(function ($expediente) {
            $nombreCompleto = $expediente->nombre . " - " . $expediente->descripcion; // Modificar según tus campos
            $expediente->nombre_completo = $nombreCompleto;
            return $expediente;
        });
    
        return view('expedientes.expedientes.homeExpedientesListado', ['elementos' => $expedientes]);
    }
    

    public function detallesExpedienteBasico($id_exp,$id_usuario){

        $expediente = DB::table('expedientes')
        ->where('id_expediente', $id_exp)
        ->first();

    // Verificar si el expediente existe en la base de datos
    if (!$expediente) {
        return redirect()->route('homeClientesUsuario',$id_usuario)->with('error', 'No se encontró expediente.');
    }

      // Obtener el cliente al que pertenece el expediente
      $clienteNombre = DB::table('clientes_expedientes')
      ->where('id_consecutivo', $expediente->id_cliente)
      ->value('nombre');

     // Verificar si se encontró el cliente
    if (!$clienteNombre) {
    return redirect()->route('homeClientesUsuario',$id_usuario)->with('error', 'No se encontró ningún detalle de ese expediente.');
    }

    // Obtener el usuario que registró el expediente
    $usuarioCreador = DB::table('users')
      ->where('idUsuarioSistema', $expediente->usuario_creador)
      ->value('nombre'); // Cambia 'nombre_usuario' por el nombre de la columna que contiene el nombre del usuario

      $usuarioPosee = DB::table('users')
      ->where('idUsuarioSistema', $expediente->usuario_posee)
      ->value('nombre'); // Cambia 'nombre_usuario' por el nombre de la columna que contiene el nombre del usuario


    $expediente->id_cliente = $clienteNombre;
    $expediente->usuario_creador = $usuarioCreador;
    $expediente->usuario_posee = $usuarioPosee;
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
        
        $idUser = DB::table('users')
            ->where('idUsuarioSistema', auth()->id()) // Filtrar por el ID del usuario autenticado
            ->value('idUsuarioSistema');

        // Buscar el expediente por su nombre en la base de datos
        $expediente = DB::table('expedientes')
            ->where('id_expediente',$id_exp)
            ->first();                                                                                                                                                                                                                                                                                                                                                                                                                          
        
              // Obtener el cliente al que pertenece el expediente
      $clienteNombre = DB::table('clientes_expedientes')
      ->where('id_consecutivo', $expediente->id_cliente)
      ->value('nombre');
    
        // Verificar si se encontró el cliente
        if (!$clienteNombre) {
            return redirect()->route('homeExpedientes')->with('error', 'No se encontró ningún cliente asociado a este expediente.');
        }
    
        // Obtener el usuario que registró el expediente
        $usuarioCreador = DB::table('users')
            ->where('idUsuarioSistema', $expediente->usuario_creador)
            ->value('nombre'); // Cambia 'nombre_usuario' por el nombre de la columna que contiene el nombre del usuario
    
            $usuarioPosee = DB::table('users')
            ->where('idUsuarioSistema', $expediente->usuario_posee)
            ->value('nombre'); // Cambia 'nombre_usuario' por el nombre de la columna que contiene el nombre del usuario
      
        $expediente->id_cliente = $clienteNombre;
        $expediente->usuario_creador = $usuarioCreador;
        $expediente->usuario_posee = $usuarioPosee;
        $expediente->fecha_creacion = date('d-m-Y', strtotime($expediente->fecha_creacion));

        $consulta = DB::table('users')
    ->select('registrarExpediente', 'consultarExpediente', 'editarExpediente', 'eliminarExpediente', 'reportesExpediente')
    ->where('idUsuarioSistema', $idUser)
    ->first();

    $permisos = (array) $consulta;
    $permisosUsuario = [];

    foreach ($permisos as $indice => $valor) {
    $permisosUsuario[] = ['indice' => $indice, 'valor' => $valor];
    }


        return view('expedientes.expedientes.detalles', ['expediente' => $expediente, 'permisosUsuario' => $permisosUsuario]);
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
            ->where('id_consecutivo', $expediente->id_cliente)
            ->value('nombre');

            // Verificar si se encontró el cliente
        if (!$clienteNombre) {
            session()->flash('info', 'No se encontró ningún cliente asociado a este expediente.');
            return redirect()->route('expedientesBasico',$id_exp)->with('error', 'No se encontró cliente.');
        }
    
        // Obtener el usuario que registró el expediente
        $usuarioCreador = DB::table('users')
            ->where('idUsuarioSistema', $expediente->usuario_creador)
            ->value('nombre'); // Cambia 'nombre_usuario' por el nombre de la columna que contiene el nombre del usuario
    
        $expediente->id_cliente = $clienteNombre;
        $expediente->usuario_creador = $usuarioCreador;

        $fecha = date('Y-m-d', strtotime('-1 day'));

        return view('expedientes.expedientes.solicitarExpUB', ['expediente' => $expediente, 'id_usuario' => $id_usuario, 'fecha' => $fecha]);

    }
    
    public function solicitar($id_exp) {
        // Buscar el expediente por su nombre en la base de datos
        $expediente = DB::table('expedientes')
            ->where('id_expediente', $id_exp)
            ->first();
    
        // Verificar si el expediente existe en la base de datos
        if (!$expediente) {
            session()->flash('info', 'No se encontró ningún detalle de ese expediente..');
            return redirect()->route('homeExpedientes');
        }
    
        // Obtener el cliente al que pertenece el expediente
        $clienteNombre = DB::table('clientes_expedientes')
            ->where('id_consecutivo', $expediente->id_cliente)
            ->value('nombre');
    
        // Verificar si se encontró el cliente
        if (!$clienteNombre) {
            session()->flash('info', 'No se encontró ningún cliente asociado a este expediente.');
            return redirect()->route('homeExpedientes');
        }
    
        // Obtener el usuario que registró el expediente
        $usuarioCreador = DB::table('users')
            ->where('idUsuarioSistema', $expediente->usuario_creador)
            ->value('nombre'); // Cambia 'nombre_usuario' por el nombre de la columna que contiene el nombre del usuario
    
        $expediente->id_cliente = $clienteNombre;
        $expediente->usuario_creador = $usuarioCreador;

        $fecha = date('Y-m-d', strtotime('-1 day'));

        return view('expedientes.expedientes.solicitar', ['expediente' => $expediente, 'fecha' => $fecha]);
    }
    

    public function almacenarActividad(Request $request)
    {

        // Obtén los valores de los campos del formulario
        $id_expediente = $request->input('id_expediente');
        $fecha_devolucion = $request->input('fecha_devolucion');
        $motivo = $request->input('motivo');
        
        date_default_timezone_set('America/Mexico_City');
        $fecha_solicitud = date('Y-m-d');

        $idUsuarioSistema = DB::table('users')
            ->where('idUsuarioSistema', auth()->id()) // Filtrar por el ID del usuario autenticado
            ->value('idUsuarioSistema');

            $idUsuarioSolicita = DB::table('users')
            ->where('idUsuarioSistema', auth()->id()) // USUARIO QUE SOLICITAS
            ->value('idUsuarioSistema');

            $nombreusuariorealiza = DB::table('users')
            ->where('idUsuarioSistema', auth()->id()) // USUARIO QUE SOLICITA
            ->select(DB::raw('CONCAT(nombre, " ", apellidos) as nombreCompleto'))
            ->value('nombreCompleto');
        
    
        // Crea un nuevo registro en la tabla actividad_expediente
        DB::table('actividad_expedientes')->insert([
            'id_usuario_otorga' => $idUsuarioSistema, // Valor fijo
            'id_usuario_solicita' => $idUsuarioSistema, 
            'fecha_solicitud' => $fecha_solicitud,
            'fecha_devolucion' => $fecha_devolucion,
            'motivo' => $motivo,
            'movimiento' => 'Prestamo',
            'id_expediente' => $id_expediente,
            'estado'=>'En uso',
            'id_usuario_realiza'=>$nombreusuariorealiza,
        ]);

        DB::table('expedientes')
        ->where('id_expediente', $id_expediente)
        ->update(['estado' => 'En uso'
        , 'usuario_posee' => $idUsuarioSolicita
        ]);

        session()->flash('success', 'Actividad registrada correctamente');
    
        return redirect()->route('homeAdminExpedientes');
    }

    public function almacenarActividadUsuarioBasico(Request $request)
    {
        // Obtén los valores de los campos del formulario
        $id_expediente = $request->input('id_expediente');
        $fecha_devolucion = $request->input('fecha_devolucion');
        $motivo = $request->input('motivo');
        $id_usuario = $request->input('id_usuario');
        
        date_default_timezone_set('America/Mexico_City');
        $fecha_solicitud = date('Y-m-d');

        $idUsuarioSistema = DB::table('users')
            ->where('idUsuarioSistema', auth()->id()) // Filtrar por el ID del usuario autenticado
            ->value('idUsuarioSistema');

            $idUsuarioSolicita = DB::table('users')
            ->where('idUsuarioSistema', auth()->id()) // USUARIO QUE SOLICITAS
            ->value('idUsuarioSistema');

            $nombreusuariorealiza = DB::table('users')
            ->where('idUsuarioSistema', auth()->id()) // USUARIO QUE SOLICITA
            ->select(DB::raw('CONCAT(nombre, " ", apellidos) as nombreCompleto'))
            ->value('nombreCompleto');
    
        // Crea un nuevo registro en la tabla actividad_expediente
        DB::table('actividad_expedientes')->insert([
            'id_usuario_otorga' => $idUsuarioSistema, // Valor fijo
            'id_usuario_solicita' => $idUsuarioSistema, // Valor fijo
            'fecha_solicitud' => $fecha_solicitud,
            'fecha_devolucion' => $fecha_devolucion,
            'motivo' => $motivo,
            'movimiento' => 'Prestamo',
            'id_expediente' => $id_expediente,
            'estado'=>'En uso',
            'id_usuario_realiza'=>$nombreusuariorealiza,
        ]);

        DB::table('expedientes')
        ->where('id_expediente', $id_expediente)
        ->update(['estado' => 'En uso'
        , 'usuario_posee' => $idUsuarioSolicita
        ]);


        session()->flash('success', 'Actividad almacenada correctamente');
        return redirect()->route('expedientesBasico',$id_usuario);
    }



    
    
    
    
    

}
