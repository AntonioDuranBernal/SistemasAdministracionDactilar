<?php

namespace App\Http\Controllers\guardavalores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Auth;
use Auth;
use Carbon\Carbon;


class GuardavaloresController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
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
            'fecha_creacion' => $fecha_creacion
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

    public function homeAdminGuardavalores(){

        //$usuario = Auth::user();
        //$idUser = $usuario->idUsuarioSistema;
        //$idUser = auth()->user()->idUsuarioSistema;

        $idUser = DB::table('users')
        ->where('idUsuarioSistema', auth()->id()) // Filtrar por el ID del usuario autenticado
        ->value('idUsuarioSistema');

        $user = DB::table('users')->where('idUsuarioSistema', $idUser)->first();

        $idRol = $user->rol;

        echo 'EL ID DEL USUARIO ES: '.$idUser. 'CON EL ROL'. $idRol.' EN GUARDAVALORES CONTROLLER';

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

            /*
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
            */
            // Agregar el registro actualizado al arreglo
            
            $elementosActualizados[] = (object) $elementoOriginal;
        }

        return view('guardavalores.home', ['idRol' => $idRol, 'elementos' => $elementosActualizados, 'listadoPermisos' => $permisosUsuario]);
    
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
