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
        $this->middleware('auth');
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
        $idRol = $user->rol;

        echo 'EL ID DEL USUARIO ES: '.$idUser. 'CON EL ROL'. $idRol.' EN GUARDAVALOREES CONTROLLER';

        $consulta = DB::table('users')
        ->select('registrarGuardavalores', 'retirarGuardavalores', 'editarGuardavalores', 'consultarGuardavalores', 'reportesGuardavalores')
        ->where('idUsuarioSistema', $idUser)
        ->first();
    
        $permisos = (array) $consulta;
        $permisosUsuario = [];
    
        foreach ($permisos as $indice => $valor) {
        $permisosUsuario[] = ['indice' => $indice, 'valor' => $valor];
        }

        $elementos = DB::table('actividad_guardavalores')->get();
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

        return view('guardavalores.home', ['idRol' => $idRol, 'elementos' => $elementosActualizados, 'listadoPermisos' => $permisosUsuario]);
    
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
            return redirect()->route('homeGV')->with('error', 'No se encontró ningún documento con el ID proporcionado.');
        }
    
        return view('guardavalores.guardavalores.home', ['elementos' => $gv]);
    }


    public function homeGV(){

        $elementos = DB::table('guardavalores')
        ->where('nombre', '!=', '') // Solo registros donde el nombre no está vacío
        ->get();

        $idUser = 1;

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
        ]);

        $fecha_creacion = Carbon::now('America/Mexico_City')->toDateTimeString();
        $usuario_creador = 1;

        $id_pagare = DB::table('guardavalores')->insertGetId([
            'nombre' => 'Pagare '.$validatedData['numero_pagare'],
            'numero_contrato' => $validatedData['numero_contrato'],
            'numero_pagare' => $validatedData['numero_pagare'],
            'fecha_terminacion' => $validatedData['fechaTerminacion'],
            'fecha_terminacion' => $validatedData['fechaEntrega'],
            'funcionario' => $validatedData['funcionario'],
            'estado' => 'Disponible',
            'tipo_gv' => 'Pagare',
            'id_cliente' =>  $request->id_cliente,
            'usuario_creador' => $usuario_creador,
            'fecha_creacion' => $fecha_creacion,
            'monto' => $validatedData['monto']
        ]);

        DB::table('actividad_guardavalores')->insert([
            'id_documento' => $id_pagare,
            'id_usuario' => 1,
            'fecha_ingreso' => $fecha_creacion,
            'fecha_actividad' => $fecha_creacion, 
            'estado' => 'Ingreso',
            'movimiento' => 'Ingreso',
            'motivo' => 'Ingreso',
            'tipo_gv' => 'Pagare'
        ]);        

        return redirect()->route('homeClientesGV')->with('success', 'Documento asignado correctamente');


    }


    public function storeContrato(Request $request){
        $validatedData = $request->validate([
            'numero_contrato' => 'required',
            'nombre_contrato' => 'required',
            'fechaEntrega' => 'required',
            'funcionario' => 'required',
            'observaciones' => 'required',
        ]);

        $fecha_creacion = Carbon::now('America/Mexico_City')->toDateTimeString();
        $usuario_creador = 1;

        $id_c = DB::table('guardavalores')->insertGetId([
            'nombre' => $validatedData['nombre_contrato'],
            'numero_contrato' => $validatedData['numero_contrato'],
            'fecha_entrega' => $validatedData['fechaEntrega'],
            'funcionario' => $validatedData['funcionario'],
            'estado' => 'Disponible',
            'descripcion' => 'observaciones',
            'tipo_gv' => 'Contrato',
            'id_cliente' =>  $request->id_cliente,
            'usuario_creador' => $usuario_creador,
            'fecha_creacion' => $fecha_creacion
        ]);

        DB::table('actividad_guardavalores')->insert([
            'id_documento' => $id_c,
            'id_usuario' => 1,
            'fecha_ingreso' => $fecha_creacion,
            'fecha_actividad' => $fecha_creacion, 
            'estado' => 'Ingreso',
            'movimiento' => 'Ingreso',
            'motivo' => 'Ingreso',
            'tipo_gv' => 'Contrato'
        ]);        

        return redirect()->route('homeClientesGV')->with('success', 'Documento asignado correctamente');

    }
       

    public function storeDV(Request $request){

        $validatedData = $request->validate([
            'nombreExpediente' => 'required',
            'descripcion' => 'required',
            'tipoCredito' => 'string',
            'fecha_devolucion' => 'required',
            'funcionario' => 'string',
            'folioReal' => 'string',
        ]);

        //$fecha_creacion = date("Y-m-d H:i:s");
        $fecha_creacion = Carbon::now('America/Mexico_City')->toDateTimeString();

        $usuario_creador = 1;

        $id_guardavalores = DB::table('guardavalores')->insertGetId([
            'nombre' => $validatedData['nombreExpediente'],
            'numero_contrato' => $validatedData['descripcion'],
            'folio_real' => $validatedData['folioReal'],
            'tipo_credito' => $validatedData['tipoCredito'],
            'fecha_entrega' => $validatedData['fecha_devolucion'],
            'funcionario' => $validatedData['funcionario'],
            'fecha_creacion' => $fecha_creacion,
            'estado' => 'Disponible',
            'tipo_gv' => 'Contrato',
            'id_cliente' =>  $request->id_cliente,
            'usuario_creador' => $usuario_creador,
        ]);

        DB::table('actividad_guardavalores')->insert([
            'id_documento' => $id_guardavalores,
            'id_usuario' => 1,
            'fecha_ingreso' => $fecha_creacion,
            'fecha_actividad' => $fecha_creacion, 
            'estado' => 'Ingreso',
            'movimiento' => 'Ingreso',
            'motivo' => 'Ingreso',
            'tipo_gv' => 'Contrato'
        ]);        

        return redirect()->route('homeClientesGV')->with('success', 'Documento asignado correctamente');

    }

    public function retirarGV($id_d) {
    
        $idUser = 1;
        $idRol = 3;

        $elementos = DB::table('guardavalores')
        ->where('id_documento', $id_d)
        ->first();

    if(!$elementos) {
        return redirect()->route('homeGV')->with('error', 'No se encontró ningún documento con el contrato proporcionado.');
    } else {

        $nombreCliente = DB::table('clientes_guardavalores')
            ->where('id_cliente', $elementos->id_cliente)
            ->value('nombre');

        // Actualizar los campos necesarios
        $elementos->id_cliente = $nombreCliente;

        return view('guardavalores.guardavalores.retirar', ['idRol' => $idRol, 'expediente' => $elementos]);
    }

    }

    
    public function almacenarActividadGV(Request $request){
      $tipo_gv = $request->tipo_gv;
      $idUsuario =1;
      $estado = 'Retirado';
      $movimiento = 'Retiro';
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
        'fecha_actividad' => $fecha
      ]);

    DB::table('guardavalores')
    ->where('id_documento', $id_doc)
    ->update(['estado' => 'Retirado']);

    return redirect()->route('homeGV')->with('error', 'Documento retirado.');

    }


    public function consultarGV($id_c) {

        $idUser = 1;
        $idRol = 3;
    
        echo 'EL ID DEL USUARIO ES: '.$idUser;
    
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
            return redirect()->route('homeGV')->with('error', 'No se encontró ningún documento con el contrato proporcionado.');
        } else {
    
            $nombreCliente = DB::table('clientes_guardavalores')
                ->where('id_cliente', $elementos->id_cliente)
                ->value('nombre');
    
            $datosUsuario = DB::table('users')
                ->where('idUsuarioSistema', $elementos->usuario_creador)
                ->select('nombre', 'apellidos', 'idUsuarioSistema')
                ->first();
    
            $nombreCliente = $datosUsuario->nombre . ' ' . $datosUsuario->apellidos . ' (' . $datosUsuario->idUsuarioSistema . ')';
    
  
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
            $elementos->usuario_creador = $nombreCliente;
    
            return view('guardavalores.guardavalores.detalles', ['idRol' => $idRol, 'expediente' => $elementos, 'permisosUsuario' => $permisosUsuario]);
    
        }
    
    }
    










}
