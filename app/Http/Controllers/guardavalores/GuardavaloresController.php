<?php

namespace App\Http\Controllers\guardavalores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;


class GuardavaloresController extends Controller
{

    public function __construct(){
        $this->middleware('auth')->except('storeAPI');
    }

    public function storeAPI(Request $request) {

        $validatedData = $request->validate([
            'numero_contrato' => 'required',
            'numero_pagare' => 'required',
            'fechaTerminacion' => 'required',
            'monto' => 'required',
            'fechaEntrega' => 'required',
            'numero_credito' => 'required',
            'funcionario' => 'string',
            'id_cliente' => 'required',
        ]);

        $fecha_creacion = Carbon::now('America/Mexico_City')->toDateTimeString();

        $usuario_creador = 1;

        $id_pagare = DB::table('guardavalores')->insertGetId([
            'nombre' => 'Pagare '.$validatedData['numero_pagare'],
            'numero_contrato' => $validatedData['numero_contrato'],
            'numero_pagare' => $validatedData['numero_pagare'],
            'fecha_terminacion' => $validatedData['fechaTerminacion'],
            'fecha_entrega' => $validatedData['fechaEntrega'],
            'funcionario' => $validatedData['funcionario'],
            'estado' => 'Disponible',
            'tipo_gv' => 'Pagare',
            'id_cliente' =>  $request->id_cliente,
            'usuario_creador' => $usuario_creador,
            'fecha_creacion' => $fecha_creacion,
            'monto' => $validatedData['monto'],
            'numero_credito' => $validatedData['numero_credito'],
            'usuario_posee' => 0,
        ]);

        DB::table('actividad_guardavalores')->insert([
            'id_documento' => $id_pagare,
            'id_usuario' => $usuario_creador,
            'fecha_ingreso' => $fecha_creacion,
            'fecha_actividad' => $fecha_creacion, 
            'estado' => 'Ingreso',
            'movimiento' => 'Ingreso',
            'motivo' => 'Ingreso',
            'tipo_gv' => 'Pagare'
        ]);

        return response()->json(['message' => 'Pagare guardado con éxito en sistema Alamos'], 201);
    }


    public function editarGV($id){
        $gv = DB::table('guardavalores')->where('id_documento',$id)->first();
        
        // Verifica si se encontró el expediente
        if (!$gv) {
            return redirect()->route('homeAdminGuardavalores')->with('error', 'Documento no encontrado');
        }
    
        //Filtrar los campos no vacíos
        $nonEmptyFields = [];
        foreach ($gv as $key => $value) {
            if (!empty($value)) {
                $nonEmptyFields[$key] = $value;
            }
        }
    
        return view('guardavalores.guardavalores.editarGV', ['gv'=>$gv]);
    }    

    public function actualizarGV(Request $request, $id)
    {
        // Valida los datos del formulario
        $request->validate([
            'nombre' => 'required',
            'tipo_credito' => 'required',
        ]);
    
        // Obtén todos los datos del formulario
        $nombre = $request->input('nombre');
        $numeroContrato = $request->input('numero_contrato');
        $numeroPagare = $request->input('numero_pagare');
        $descripcion = $request->input('descripcion');
        $folioReal = $request->input('folio_real');
        $otrosDatos = $request->input('otros_datos');
        $funcionario = $request->input('funcionario');
        $monto = $request->input('monto');
        $tipoCredito = $request->input('tipo_credito');
    
        // Crea un array con los campos que no están vacíos o nulos
        $datosActualizados = [
            'nombre' => $nombre,
            'tipo_credito' => $tipoCredito, // Asumiendo que 'tipo_gv' es equivalente a 'tipo_credito'
        ];
    
        // Agrega otros campos que quieras actualizar aquí, por ejemplo:
        if (!is_null($numeroContrato) && $numeroContrato !== '') {
            $datosActualizados['numero_contrato'] = $numeroContrato;
        }
    
        if (!is_null($numeroPagare) && $numeroPagare !== '') {
            $datosActualizados['numero_pagare'] = $numeroPagare;
        }
    
        if (!is_null($descripcion) && $descripcion !== '') {
            $datosActualizados['descripcion'] = $descripcion;
        }
    
        if (!is_null($folioReal) && $folioReal !== '') {
            $datosActualizados['folio_real'] = $folioReal;
        }
    
        if (!is_null($otrosDatos) && $otrosDatos !== '') {
            $datosActualizados['otros_datos'] = $otrosDatos;
        }
    
        if (!is_null($funcionario) && $funcionario !== '') {
            $datosActualizados['funcionario'] = $funcionario;
        }
    
        if (!is_null($monto) && $monto !== '') {
            $datosActualizados['monto'] = $monto;
        }
    
        // Actualiza los datos del guardavalor si hay campos para actualizar
        if (!empty($datosActualizados)) {
            DB::table('guardavalores')
                ->where('id_documento', $id)
                ->update($datosActualizados);
        }
    
        return redirect()->route('homeAdminGuardavalores')->with('success', 'Guardavalor actualizado correctamente');
    }
    

    public function homeAdminGuardavalores(){

        $user = DB::table('users')
        ->where('idUsuarioSistema', auth()->id())->first();
        $idUser = $user->id;
        $nombre = $user->nombre;
        $idRol = $user->rol;

        $consulta = DB::table('users')
        ->select('registrarGuardavalores', 'retirarGuardavalores', 'editarGuardavalores', 'consultarGuardavalores', 'reportesGuardavalores')
        ->where('idUsuarioSistema', $idUser)
        ->first();
    
        $permisos = (array) $consulta;
        $permisosUsuario = [];
    
        foreach ($permisos as $indice => $valor) {
        $permisosUsuario[] = ['indice' => $indice, 'valor' => $valor];
        }

        $elementos = DB::table('actividad_guardavalores')
        ->orderBy('fecha_actividad', 'desc')
        ->get();
        
        $elementosActualizados = [];
    
        foreach ($elementos as $elemento) {
            $nombreContrato = DB::table('guardavalores')
                ->where('id_documento', $elemento->id_documento)
                ->value('nombre');
    
            $nombreUsuario = DB::table('users')
                ->where('idUsuarioSistema', $elemento->id_usuario)
                ->value('nombre');
    
            // Obtener los datos originales del elemento
            $elementoOriginal = (array) $elemento;
    
            // Formatear las fechas en día, mes y año
            $elementoOriginal['fecha_ingreso'] = date('d-m-Y', strtotime($elemento->fecha_ingreso));
            $elementoOriginal['fecha_retiro'] = date('d-m-Y', strtotime($elemento->fecha_retiro));
            $elementoOriginal['fecha_actividad'] = date('d-m-Y', strtotime($elemento->fecha_actividad));

            // Actualizar los campos necesarios
            $elementoOriginal['id_documento'] = $nombreContrato;
            $elementoOriginal['id_usuario'] = $nombreUsuario;
            
            $elementosActualizados[] = (object) $elementoOriginal;
        }

        return view('guardavalores.home', ['nombre' => $nombre, 'idRol' => $idRol, 'elementos' => $elementosActualizados, 'listadoPermisos' => $permisosUsuario]);
    
    }

    

    public function buscarGV(Request $request) {
        $id_gv = $request->input('id_gv');
        
        if (is_numeric($id_gv)) {
            $gv = DB::table('guardavalores')
            ->where('numero_contrato', $id_gv)
            ->get();
        } else {
            $gv = DB::table('guardavalores')
            ->where('nombre', 'LIKE', "%$id_gv%")
            ->get();
        }
    
        if ($gv->isEmpty()) {
            session()->flash('info', 'No se encontró documento.');
            return redirect()->route('homeGV');
        }

        $idUser = DB::table('users')
        ->where('idUsuarioSistema', auth()->id()) // Filtrar por el ID del usuario autenticado
        ->value('idUsuarioSistema');

    $consulta = DB::table('users')
    ->select('registrarGuardavalores', 'retirarGuardavalores', 'editarGuardavalores', 'consultarGuardavalores', 'reportesGuardavalores')
    ->where('idUsuarioSistema', $idUser)
    ->first();

    $permisos = (array) $consulta;
    $permisosUsuario = [];

    foreach ($permisos as $indice => $valor) {
    $permisosUsuario[] = ['indice' => $indice, 'valor' => $valor];
    }
    
        return view('guardavalores.guardavalores.home', ['elementos' => $gv, 'permisosUsuario' => $permisosUsuario]);
    }


    public function homeGV(){

        $elementos = DB::table('guardavalores')
        ->get();

        $idUser = DB::table('users')
            ->where('idUsuarioSistema', auth()->id()) // Filtrar por el ID del usuario autenticado
            ->value('idUsuarioSistema');

        $consulta = DB::table('users')
        ->select('registrarGuardavalores', 'retirarGuardavalores', 'editarGuardavalores', 'consultarGuardavalores', 'reportesGuardavalores')
        ->where('idUsuarioSistema', $idUser)
        ->first();
    
        $permisos = (array) $consulta;
        $permisosUsuario = [];
    
        foreach ($permisos as $indice => $valor) {
        $permisosUsuario[] = ['indice' => $indice, 'valor' => $valor];
    }

    return view('guardavalores.guardavalores.home', ['elementos' => $elementos, 'permisosUsuario' => $permisosUsuario]);
}
    
    public function documentoGV($id_cliente){
        $cliente = DB::table('clientes_guardavalores')
        ->where('id_cliente',$id_cliente)
        ->first();

        if ($cliente) {
            $fecha = now()->format('Y-m-d');
            return view('guardavalores.guardavalores.crearDV', ['cliente' => $cliente, 'fecha'=>$fecha]);
        }

    }

    public function pagareGV($id_cliente){
        $cliente = DB::table('clientes_guardavalores')
        ->where('id_cliente',$id_cliente)
        ->first();

        if ($cliente) {
            $fecha = now()->format('Y-m-d'); //crearPagare.blade.php
            return view('guardavalores.guardavalores.crearPagare', ['cliente' => $cliente, 'fecha'=>$fecha]);
        }

    }

    public function contratoGV($id_cliente){
        $cliente = DB::table('clientes_guardavalores')
        ->where('id_cliente',$id_cliente)
        ->first();

        if ($cliente) {
            $fecha = now()->format('Y-m-d'); //crearContrato.blade.php
            return view('guardavalores.guardavalores.crearContrato', ['cliente' => $cliente, 'fecha'=>$fecha]);
        }

    }
        

    public function storePagare(Request $request){

        $validatedData = $request->validate([
            'numero_contrato' => 'required',
            'numero_pagare' => 'required',
            'fechaTerminacion' => 'required',
            'funcionario' => 'string',
            'monto' => 'required',
            'fechaEntrega' => 'required',
            'numero_credito' => 'required',
        ]);

        $fecha_creacion = Carbon::now('America/Mexico_City')->toDateTimeString();

        $usuario_creador = DB::table('users')
            ->where('idUsuarioSistema', auth()->id())
            ->value('idUsuarioSistema');

        $id_pagare = DB::table('guardavalores')->insertGetId([
            'nombre' => 'Pagare '.$validatedData['numero_pagare'],
            'numero_contrato' => $validatedData['numero_contrato'],
            'numero_pagare' => $validatedData['numero_pagare'],
            'fecha_terminacion' => $validatedData['fechaTerminacion'],
            'fecha_entrega' => $validatedData['fechaEntrega'],
            'funcionario' => $validatedData['funcionario'],
            'estado' => 'Disponible',
            'tipo_gv' => 'Pagare',
            'id_cliente' =>  $request->id_cliente,
            'usuario_creador' => $usuario_creador,
            'fecha_creacion' => $fecha_creacion,
            'monto' => $validatedData['monto'],
            'numero_credito' => $validatedData['numero_credito'],
            'usuario_posee' => 0,
        ]);

        DB::table('actividad_guardavalores')->insert([
            'id_documento' => $id_pagare,
            'id_usuario' => $usuario_creador,
            'fecha_ingreso' => $fecha_creacion,
            'fecha_actividad' => $fecha_creacion, 
            'estado' => 'Ingreso',
            'movimiento' => 'Ingreso',
            'motivo' => 'Ingreso',
            'tipo_gv' => 'Pagare'
        ]);

        session()->flash('success', 'El pagare '.$validatedData['numero_pagare'].' del contrato '.$validatedData['numero_contrato'].' se registro correctamente.');
        return redirect()->route('homeGV');

    }


    public function storeContrato(Request $request){
        $validatedData = $request->validate([
            'numero_contrato' => 'required',
            'nombre_contrato' => 'required',
            'fechaEntrega' => 'required',
            'funcionario' => 'required',
            'vigencia' => 'required',
        ]);

        $observaciones = $request->observaciones ?? "Sin observaciones";


        $fecha_creacion = Carbon::now('America/Mexico_City')->toDateTimeString();

        $usuario_creador = DB::table('users')
            ->where('idUsuarioSistema', auth()->id()) // Filtrar por el ID del usuario autenticado
            ->value('idUsuarioSistema');

        $id_c = DB::table('guardavalores')->insertGetId([
            'nombre' => $validatedData['nombre_contrato'],
            'numero_contrato' => $validatedData['numero_contrato'],
            'fecha_entrega' => $validatedData['fechaEntrega'],
            'funcionario' => $validatedData['funcionario'],
            'estado' => 'Disponible',
            'descripcion' => $observaciones,
            'tipo_gv' => 'Contrato',
            'id_cliente' =>  $request->id_cliente,
            'usuario_creador' => $usuario_creador,
            'fecha_creacion' => $fecha_creacion,
            'vigencia' => $validatedData['vigencia'],
            'usuario_posee' => 0,
        ]);

        DB::table('actividad_guardavalores')->insert([
            'id_documento' => $id_c,
            'id_usuario' => $usuario_creador,
            'fecha_ingreso' => $fecha_creacion,
            'fecha_actividad' => $fecha_creacion, 
            'estado' => 'Ingreso',
            'movimiento' => 'Ingreso',
            'motivo' => 'Ingreso',
            'tipo_gv' => 'Contrato'
        ]);        
        session()->flash('Success', 'El contrato '.$validatedData['numero_contrato'].' se registro correctamente.');

        return redirect()->route('homeGV')->with('success', 'Documento asignado correctamente');

    }
       

    public function documentoGVX($id_cliente, $nombre){
        $cliente = DB::table('clientes_guardavalores')
        ->where('id_cliente',$id_cliente)
        ->first();

        if ($cliente) {
            $fecha = now()->format('Y-m-d');
            return view('guardavalores.guardavalores.crearDV', ['cliente' => $cliente, 'fecha'=>$fecha, 'nombreTipo' =>$nombre]);
    }
   }

   public function storeDV(Request $request) {
    // Obtén los datos directamente del objeto Request
    $tipoCredito = $request->input('tipoCredito');
    $fechaEntrega = $request->input('fechaEntrega');
    $fechaActa = $request->input('fechaActa');
    $funcionario = $request->input('funcionario');
    $folioReal = $request->input('folioReal');
    $descripcion = $request->input('descripcion');
    $id_cliente = $request->input('id_cliente');
    $numeroEscritura = $request->input('numeroEscritura');
    $cantidad = $request->input('cantidad');
    $nombreTipo = $request->input('nombreTipo');
    $nombreDocumento = $request->input('nombreDocumento');

    if (empty($nombreDocumento)) {
        $nombreDocumento = $nombreTipo;
    }

    $vigencia = $request->input('vigencia');
    $numeroContrato =  $request->input('numeroContrato');
    $persona_expide = $request->input('personaExpide');
    $numero_cheque = $request->input('numeroCheque');
    $fecha_cheque = $request->input('fechaCheque');
    $monto = $request->input('monto');
    $idCredito = $request->input('idCredito');
    $rfc= $request->input('rfc');
    $folioFiscal= $request->input('folioFiscal');
    $concepto= $request->input('concepto');
    $numeroCertificado= $request->input('numeroCertificado');
    $kilos= $request->input('kilos');
    $aFavor = $request->input('aFavor');

    // Obtén la fecha de creación
    $fecha_creacion = Carbon::now('America/Mexico_City')->toDateTimeString();

    // Obtén el ID del usuario creador
    $usuario_creador = auth()->id();

    // Inserta los datos en la base de datos
    $id_guardavalores = DB::table('guardavalores')->insertGetId([
        'nombre' => $nombreDocumento,
        'numero_contrato' => $numeroContrato,
        'folio_real' => $folioReal,
        'tipo_credito' => $tipoCredito,
        'fecha_entrega' => $fechaEntrega,
        'fecha_acta' => $fechaActa,
        'funcionario' => $funcionario,
        'fecha_creacion' => $fecha_creacion,
        'estado' => 'Disponible',
        'tipo_gv' => $nombreTipo,
        'id_cliente' => $id_cliente,
        'usuario_creador' => $usuario_creador,
        'numeroEscritura' => $numeroEscritura,
        'cantidad' => $cantidad,
        'vigencia' => $vigencia,
        'numero_cheque' => $numero_cheque,
        'fechaCheque' => $fecha_cheque,
        'persona_expide' => $persona_expide,
        'descripcion' => $descripcion,
        'monto' => $monto,
        'idCredito' => $idCredito,
        'rfc' => $rfc,
        'folioFiscal' => $folioFiscal,
        'concepto' => $concepto,
        'aFavor' => $aFavor,
        'numeroCertificado' => $numeroCertificado,
        'kilos' => $kilos,
    ]);

    // Inserta actividad de guardavalores
    DB::table('actividad_guardavalores')->insert([
        'id_documento' => $id_guardavalores,
        'id_usuario' => $usuario_creador,
        'fecha_ingreso' => $fecha_creacion,
        'fecha_actividad' => $fecha_creacion, 
        'estado' => 'Ingreso',
        'movimiento' => 'Ingreso',
        'motivo' => 'Ingreso',
        'tipo_gv' => $nombreTipo,
    ]);

    session()->flash('success', 'El documento de valor se registró correctamente.');

    return redirect()->route('homeGV')->with('success', 'Documento asignado correctamente');
}


    public function retirarGV($id_d) {
    
        $idUser = DB::table('users')
            ->where('idUsuarioSistema', auth()->id()) // Filtrar por el ID del usuario autenticado
            ->value('idUsuarioSistema');
            
        $user = DB::table('users')->where('idUsuarioSistema', $idUser)->first();

        $idRol = $user->rol;

        $elementos = DB::table('guardavalores')
        ->where('id_documento', $id_d)
        ->first();

    if(!$elementos) {
        return redirect()->route('homeGV');
    } else {

        $nombreCliente = DB::table('clientes_guardavalores')
            ->where('id_cliente', $elementos->id_cliente)
            ->value('nombre');

        // Actualizar los campos necesarios
        $elementos->id_cliente = $nombreCliente;

        return view('guardavalores.guardavalores.retirar', ['idRol' => $idRol, 'expediente' => $elementos]);
    }

    }

        
    public function reingresar($id_documento){

        $idUser = DB::table('users')
        ->where('idUsuarioSistema', auth()->id()) // Filtrar por el ID del usuario autenticado
        ->value('idUsuarioSistema');
        
    $user = DB::table('users')->where('idUsuarioSistema', $idUser)->first();

    $idRol = $user->rol;

    $elementos = DB::table('guardavalores')
    ->where('id_documento', $id_documento)
    ->first();

    $usuario = DB::table('users')
    ->where('idUsuarioSistema', $elementos->usuario_posee)
    ->select('nombre', 'apellidos') // Selecciona nombre y apellidos
    ->first();

    if ($usuario) {
    // El usuario se encontró, por lo que se usa el nombre y los apellidos
    $nombrePoseedor = $usuario->nombre . ' ' . $usuario->apellidos;
    } else {
    // El usuario no se encontró, por lo que se utiliza "General"
    $nombrePoseedor = "General";
    }


    if(!$elementos) {
    return redirect()->route('homeGV');
    } else {

    $nombreCliente = DB::table('clientes_guardavalores')
        ->where('id_cliente', $elementos->id_cliente)
        ->value('nombre');

    // Actualizar los campos necesarios
    $elementos->id_cliente = $nombreCliente;
    $elementos->usuario_posee = $nombrePoseedor;

    return view('guardavalores.guardavalores.reingresar', ['idRol' => $idRol, 'expediente' => $elementos]);
    }
    
   }

   
   public function reingresoActividad(Request $request){

    $idUsuario = DB::table('users')
    ->where('idUsuarioSistema', auth()->id()) // Filtrar por el ID del usuario autenticado
    ->value('idUsuarioSistema');

    $tipo_gv = $request->tipo_gv;
    $movimiento = 'Reingreso';
    $id_doc = $request->id_documento;
    $motivo = $request->motivo;
    $fecha = Carbon::now('America/Mexico_City')->toDateTimeString();

    $id_actGV= DB::table('actividad_guardavalores')->insertGetId([
        'tipo_gv' => $tipo_gv,
        'id_documento' => $id_doc,
        'estado' => 'Disponible',
        'movimiento' => $movimiento,
        'motivo' => $motivo,
        'id_usuario' => $idUsuario,
        'fecha_actividad' => $fecha,
        'usuario_solicita' => $idUsuario,
      ]);

      DB::table('guardavalores')
      ->where('id_documento', $id_doc)
      ->update([
          'estado' => 'Disponible',
          'usuario_posee' => 0
      ]);
  
      session()->flash('info', 'Se reingreso el documento');
      return redirect()->route('homeGV');


   }
    
    public function almacenarActividadGV(Request $request){
      $tipo_gv = $request->tipo_gv;

      $idUsuario = DB::table('users')
      ->where('idUsuarioSistema', auth()->id()) // Filtrar por el ID del usuario autenticado
      ->value('idUsuarioSistema');
      
      $estado = 'Retirado';
      $movimiento = 'Retiro';
      $usuario_posee = $idUsuario; //USUARIO QUE RETIRO EL DOCUMENTO GV, ES QUE LO SOLICITO Y CONFIRMO CON HUELLA
      $id_doc = $request->id_documento;
      $motivo = $request->motivo;

      $fecha = Carbon::now('America/Mexico_City')->toDateTimeString();

      $id_actGV= DB::table('actividad_guardavalores')->insertGetId([
        'tipo_gv' => $tipo_gv,
        'id_documento' => $id_doc,
        'estado' => $estado,
        'movimiento' => $movimiento,
        'motivo' => $motivo,
        'id_usuario' => $idUsuario,
        'fecha_actividad' => $fecha,
        'usuario_solicita' => $usuario_posee
      ]);

      DB::table('guardavalores')
    ->where('id_documento', $id_doc)
    ->update([
        'estado' => 'Retirado',
        'usuario_posee' => $usuario_posee
    ]);

    session()->flash('info', 'Se retiró el documento.');
    return redirect()->route('homeGV');

    }


    public function consultarGV($id_c) {

        $idUser = DB::table('users')
        ->where('idUsuarioSistema', auth()->id()) // Filtrar por el ID del usuario autenticado
        ->value('idUsuarioSistema');
        
        $user = DB::table('users')->where('idUsuarioSistema', $idUser)->first();
        $idRol = $user->rol;
    
        $consulta = DB::table('users')
            ->select('registrarGuardavalores', 'retirarGuardavalores', 'editarGuardavalores', 'consultarGuardavalores', 'reportesGuardavalores')
            ->where('idUsuarioSistema', $idUser)
            ->first();
    
        $permisos = (array) $consulta;
        $permisosUsuario = [];
    
        foreach ($permisos as $indice => $valor) {
            $permisosUsuario[] = ['indice' => $indice, 'valor' => $valor];
        }
    
        $elementos = DB::table('guardavalores')
            ->where('id_documento', $id_c)
            ->first();
    
        if(!$elementos) {
            return redirect()->route('homeGV')->with('error', 'No se encontró ningún documento.');
        } else {
    
            $nombreCliente = DB::table('clientes_guardavalores')
                ->where('id_cliente', $elementos->id_cliente)
                ->value('nombre');
    
            $datosUsuario = DB::table('users')
                ->where('idUsuarioSistema', $elementos->usuario_creador)
                ->select('nombre', 'apellidos', 'idUsuarioSistema')
                ->first();

            $datosUsuario_poseee = DB::table('users')
                ->where('idUsuarioSistema', $elementos->usuario_posee)
                ->select('nombre', 'apellidos', 'idUsuarioSistema')
                ->first();
                
                if (!$datosUsuario_poseee) {
                    $datosUsuario_poseee = "En almacen";
                } else {
                    $datosUsuario_poseee = $datosUsuario_poseee->nombre . ' ' . $datosUsuario_poseee->apellidos . ' (' . $datosUsuario_poseee->idUsuarioSistema . ')';
                }
    
            //$nombreCliente = $datosUsuario->nombre . ' ' . $datosUsuario->apellidos . ' (' . $datosUsuario->idUsuarioSistema . ')';
            $nombreCliente = $nombreCliente;
  
            $campos = ['fecha_creacion', 'fecha_entrega', 'fecha_terminacion'];

            foreach ($campos as $campo) {
                if (!empty($elementos->$campo)) {
                    $elementos->$campo = date('d-m-Y', strtotime($elementos->$campo));
                } else {
                    $elementos->$campo = '';
                }
            }
    
            // Actualizar los campos necesarios
            $elementos->id_cliente = $nombreCliente;
            $elementos->usuario_posee = $datosUsuario_poseee;
            $elementos->usuario_creador = $datosUsuario;
    
            return view('guardavalores.guardavalores.detalles', ['idRol' => $idRol, 'expediente' => $elementos, 'permisosUsuario' => $permisosUsuario]);
    
        }
    
    }
    










}
