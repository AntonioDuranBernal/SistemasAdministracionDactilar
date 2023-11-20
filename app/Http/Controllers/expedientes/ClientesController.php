<?php

namespace App\Http\Controllers\expedientes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;


class ClientesController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->except('storeAPI_clientes_expedientes', 'storeAPI_clientes_guardavalores');
    }

        
    public function storeAPI_clientes_expedientes(Request $request) {

        $request->validate([
            'nombre' => 'required',
            'idgiro' => 'nullable',
        ]);

        $nombre = $request->nombre;
        $id_cliente_giro = $request->id_cliente_giro;

        $existingClient = DB::table('clientes_expedientes')->where('nombre', $nombre)->first();
        
        if ($existingClient) {
            return response()->json(['message' => 'Actualmente existe un expediente con el mismo nombre.'], 409);
        } else {

            DB::table('clientes_expedientes')->insert([
                'nombre' => $nombre,
                'id_clienteGiro' => $id_cliente_giro,

            ]);
            
            return response()->json(['message' => 'Cliente guardado con éxito en sistema Alamos'], 201);
        }

    }

    public function storeAPI_clientes_guardavalores(Request $request) {

        $request->validate([
            'nombre' => 'required',
            'idgiro' => 'nullable',
        ]);

        $nombre = $request->nombre;
        $id_cliente_giro = $request->id_cliente_giro;

        $existingClient = DB::table('clientes_guardavalores')->where('nombre', $nombre)->first();
        
        if ($existingClient) {
            return response()->json(['message' => 'Actualmente existe un expediente con el mismo nombre.'], 409);
        } else {

            DB::table('clientes_guardavalores')->insert([
                'nombre' => $nombre,
                'id_clienteGiro' => $id_cliente_giro,

            ]);
            
            return response()->json(['message' => 'Cliente guardado con éxito en sistema Alamos'], 201);
        }

    }

    public function storeGV(Request $request){
        $nombre = $request->nombre;
        // Inserta el cliente en la base de datos y recupera su ID
        $clienteId = DB::table('clientes_guardavalores')->insertGetId([
            'nombre' => $nombre,
        ]);
        
        // Construye el mensaje con el ID del cliente
        $mensaje = 'Registrado con éxito.';
        
        session()->flash('success', $mensaje);
        return redirect()->route('homeClientesGV');
    }
    
    public function storeUsuarioClienteBasico(Request $request){
        $request->validate([
            'nombre' => 'required'
        ]);

        $id_cliente = DB::table('clientes_expedientes')->insertGetId([
            'nombre' => $request->nombre,
            'id_clienteGiro' => empty($request->idClienteGiro) ? 0 : $request->idClienteGiro,
            'id_consecutivo' => empty($request->numeroExpediente) ? 0 : $request->numeroExpediente,
        ]);
        
        $id_usuario = $request->id_usuario;
    
        session()->flash('success', "Registro exitoso.");
    
        return redirect()->route('homeClientesUsuario', $id_usuario);
    }

    public function edit($id) {
        $existingClient = DB::table('clientes_expedientes')->where('id_cliente', $id)->first();
        return view('expedientes.clientes.edit',['cliente'=>$existingClient]);
    }

    public function update(Request $request, $id) {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required',
            'numeroExpediente' => 'required'
        ]);
    
        // Actualizar el registro en la base de datos
        DB::table('clientes_expedientes')
            ->where('id_cliente', $id)
            ->update([
                'nombre' => $request->nombre,
                'id_clienteGiro' => $request->idClienteGiro ?: 0, // Usar el operador de fusión de null (??)
                'id_consecutivo' => $request->numeroExpediente ?: 0, // Usar el operador de fusión de null (??)            
            ]);
    
        // Redirige de vuelta con un mensaje de éxito
        return redirect()->route('homeClientesSuper')->with('success', 'Expediente actualizado con éxito.');
    }    

        public function store(Request $request){
            $nombre = $request->nombre;
            
            // Verificar si ya existe un cliente con el mismo nombre
            $existingClient = DB::table('clientes_expedientes')->where('nombre', $nombre)->first();
            
            if ($existingClient) {
                // Cliente duplicado, enviar un mensaje de error a la vista
                session()->flash('error', 'Actualmente existe un expediente con el mismo nombre.');
            } else {
                // Insertar el nuevo cliente solo si no existe uno con el mismo nombre
                DB::table('clientes_expedientes')->insert([
                    'nombre' => $nombre,
                ]);
        
                // Mensaje de éxito si la inserción se realizó con éxito
                session()->flash('success', 'Expediente registrado con éxito.');
            }
        
            return redirect()->route('homeClientesSuper');
        }


    public function clienteNuevoGV($id_cliente){
    
        $cliente = DB::table('clientes_guardavalores')
        ->where('id_cliente', $id_cliente)
        ->first();
        
        $id = 0;

        //MODIFICAR PARA GV
        return view('guardavalores.guardavalores.opcionesCrear',['id'=>$id,'cliente'=>$cliente]);
        
        }

    public function asignadosUBasico($id_cliente,$idUser) {

        if (!is_numeric($id_cliente)) {
            session()->flash('error', 'El campo nùmero de cliente debe ser un número.');
            return redirect()->route('homeClientesSuper');
        }

        $cliente = DB::table('clientes_expedientes')
            ->where('id_cliente', $id_cliente)
            ->first();
        $nombre = $cliente->nombre;
    
        $expedientes = DB::table('expedientes')
            ->where('id_cliente', $id_cliente)
            ->get();
    
        if ($expedientes->isEmpty()) { // Verifica si la colección de expedientes está vacía
            session()->flash('info', 'El cliente no cuenta con tomos asignados.');
            return redirect()->route('homeClientesUsuario',$idUser);
        }

        $consulta = DB::table('users')
        ->select('registrarExpediente', 'consultarExpediente', 'editarExpediente', 'eliminarExpediente', 'reportesExpediente')
        ->where('idUsuarioSistema', $idUser)
        ->first();

        $permisos = (array) $consulta;
        $permisosUsuario = [];
    
        foreach ($permisos as $indice => $valor) {
        $permisosUsuario[] = ['indice' => $indice, 'valor' => $valor];
        }
    
        return view('expedientes.clientes.asignadosClienteBasico', ['elementos' => $expedientes, 'nombre' => $nombre, 'id_usuario' => $idUser]);
    
    }
    

    public function inicioClientesGV(){

        $elementos = DB::table('clientes_guardavalores')
        ->where('nombre', '!=', '')->get();

        $user = DB::table('users')
        ->where('idUsuarioSistema', auth()->id())->first();
        $idUser = $user->id;
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

        return view('guardavalores.clientes.homeClientesGV', ['elementos' => $elementos, 'permisosUsuario' => $permisosUsuario]);
    }


    public function searchBasico(Request $request) {
        
        $id_cliente = $request->input('id_cliente');

        $usuario = DB::table('users')->where('idUsuarioSistema', auth()->id())->first();
        $id_usuario = $usuario->idUsuarioSistema;

        $cliente = [];
        $elementos = [];

        $consulta = DB::table('users')
        ->select('registrarExpediente', 'consultarExpediente', 'editarExpediente', 'eliminarExpediente', 'reportesExpediente')
        ->where('idUsuarioSistema', $id_usuario)
        ->first();
    
        $permisos = (array) $consulta;
        $permisosUsuario = [];
    
        foreach ($permisos as $indice => $valor) {
        $permisosUsuario[] = ['indice' => $indice, 'valor' => $valor];
        }
        
        if (is_numeric($id_cliente)) {
    $cliente = DB::table('clientes_expedientes')
        ->where('id_consecutivo', $id_cliente)
        ->orWhere('id_clienteGiro', $id_cliente)
        ->get();
} else {
    $cliente = DB::table('clientes_expedientes')
        ->where('nombre', 'LIKE', "%$id_cliente%")
        ->get();
}

    
        if ($cliente->isEmpty()) {
            $cliente = DB::table('clientes_expedientes')
            ->orderByRaw('CASE WHEN id_consecutivo IS NULL OR id_consecutivo = 0 THEN 1 ELSE 0 END, id_consecutivo ASC')
            ->get();
        
            $elementos=$cliente;
            session()->flash('info', 'No se encontró expediente con dichos datos.');
            return view('expedientes.clientes.homeClientesBasico', ['elementos' => $elementos, 'permisosUsuario'=>$permisosUsuario,'usuario' => $usuario]);
        }
        $elementos=$cliente;
        return view('expedientes.clientes.homeClientesBasico', ['elementos' => $elementos, 'permisosUsuario'=>$permisosUsuario,'usuario' => $usuario]);

    }
    
    public function inicioClientesUsuarioX($idUser) {

            $idUser = DB::table('users')
            ->where('idUsuarioSistema', auth()->id()) // Filtrar por el ID del usuario autenticado
            ->value('idUsuarioSistema');
            
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

        $clientes = DB::table('clientes_expedientes')
        ->orderByRaw('CASE WHEN id_consecutivo IS NULL OR id_consecutivo = 0 THEN 1 ELSE 0 END, id_consecutivo ASC')
        ->get();
    
        return view('expedientes.clientes.homeClientesBasico', ['elementos' => $clientes,'permisosUsuario' => $permisosUsuario, 'usuario' => $user]);

    }
    
    public function inicioClientes(){
        
        $elementos = DB::table('clientes_expedientes')
    ->orderByRaw('CASE WHEN id_consecutivo IS NULL OR id_consecutivo = 0 THEN 1 ELSE 0 END, id_consecutivo ASC')
    ->get();

    
        return view('expedientes.clientes.homeClientesSuper', ['elementos' => $elementos]);
    }

    public function searchGV(Request $request) {
        $searchInput = $request->input('id_cliente');
    
        // Verifica si el input es numérico
        if (is_numeric($searchInput)) {
            $cliente = DB::table('clientes_guardavalores')
                ->where('id_cliente', $searchInput)
                ->first();
        } else {
            $cliente = DB::table('clientes_guardavalores')
                ->where('nombre', 'like', '%' . $searchInput . '%')
                ->first();
        }
    
        if (is_null($cliente)) {
            session()->flash('error', 'No se encontró ningún expediente con la información proporcionada.');
            return redirect()->route('homeClientesGV');
        }
    
        $elementos = [];
        array_push($elementos, $cliente);
    
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
    
        return view('guardavalores.clientes.homeClientesGV', ['elementos' => $elementos, 'permisosUsuario' => $permisosUsuario]);
    }
    
    
    
    public function search(Request $request) {
        $id_cliente = $request->input('id_cliente');
        
        if (is_numeric($id_cliente)) {
            $cliente = DB::table('clientes_expedientes')
                ->where('id_consecutivo', $id_cliente)
                ->orWhere('id_clienteGiro', $id_cliente)
                ->get();
        } else {
            $cliente = DB::table('clientes_expedientes')
                ->where('nombre', 'LIKE', "%$id_cliente%")
                ->get();
        }
    
        if ($cliente->isEmpty()) {
            session()->flash('error', 'No se encontró expediente con dichos datos.');
            return redirect()->route('homeClientesSuper');
        }
    
        return view('expedientes.clientes.homeClientesSuper', ['elementos' => $cliente]);
    }

    public function clienteNuevoExpediente($id_cliente){

        $fecha_actual = date("Y-m-d h:m:s");

        $cliente = DB::table('clientes_expedientes')
        ->where('id_consecutivo', $id_cliente)
        ->first();

        $nuevoNumero = DB::table('expedientes')
        ->where('id_cliente', $id_cliente)
        ->count();

        $nuevoNumero++;

        return view('expedientes.expedientes.asignarExpedienteCrear',['fechaRegistro'=>$fecha_actual,'cliente'=>$cliente, 'numTomo' => $nuevoNumero]);
        
        }

        public function storeExpediente(Request $request) {

            $request->validate([
                'descripcion' => 'required',
            ]);

            $tomo = $request->tomo;
            $idCliente = $request->id_consecutivo;
            $descripcion = $request->descripcion;
            $folioReal = $request->folioReal;
            $otrosDatos = $request->otrosDatos;
            $fechaCreacion = now();
            $nombreExpediente = $idCliente."-".$tomo;

            $usuarioCreador = DB::table('users')
            ->where('idUsuarioSistema', auth()->id()) // Filtrar por el ID del usuario autenticado
            ->value('idUsuarioSistema');
            
            $nombreusuariorealiza = DB::table('users')
            ->where('idUsuarioSistema', auth()->id()) // USUARIO QUE SOLICITA
            ->select(DB::raw('CONCAT(nombre, " ", apellidos) as nombreCompleto'))
            ->value('nombreCompleto');
            
            $usuarioPosee = DB::table('users')
            ->where('idUsuarioSistema', auth()->id()) // Filtrar por el ID del usuario autenticado
            ->value('idUsuarioSistema');

            $estado = 'Disponible'; // Cambia esto según tu lógica
        
            $expedienteId = DB::table('expedientes')->insertGetId([
                'id_cliente' => $idCliente,
                'nombre' => $nombreExpediente,
                'descripcion' => $descripcion,
                'folio_real' => $folioReal,
                'otros_datos' => $otrosDatos,
                'fecha_creacion' => $fechaCreacion,
                'usuario_creador' => $usuarioCreador,
                'usuario_posee' => null,
                'estado' => $estado,
                'tomo' => $tomo
            ]);

                    // Crea un nuevo registro en la tabla actividad_expediente
        DB::table('actividad_expedientes')->insert([
            'movimiento' => 'Ingreso',
            'id_expediente' => $expedienteId,
            'motivo' => 'Ingreso',
            'estado'=>'Disponible',
            'id_usuario_realiza'=> $nombreusuariorealiza,
            'fecha_actividad' => $fechaCreacion,
        ]);
            
            session()->flash('success', 'El tomo fue asignado con éxito.');
            
            return redirect()->route('homeExpedientes');
        }

        public function nuevo(){
            return view('expedientes.clientes.clientesCrear');
        }

        public function nuevoGV(){
            return view('guardavalores.clientes.clientesCrearGV');
        }
        

        public function asignados($id_cliente){
        
            if (!is_numeric($id_cliente)) {
                return redirect()->route('homeClientesSuper')->with('error', 'El campo debe ser un número.');
            }

            $cliente = DB::table('clientes_expedientes')
                ->where('id_consecutivo', $id_cliente)
                ->first();
                
            $nombre = $cliente->nombre;
        
            $expedientes = DB::table('expedientes')
                ->where('id_cliente',$id_cliente)
                ->get();
        
            if ($expedientes->isEmpty()) { // Verifica si la colección de expedientes está vacía
                session()->flash('info', 'El cliente no cuenta con tomos asignados.');
                return redirect()->route('homeClientesSuper');
            }
        
            return view('expedientes.clientes.asignadosCliente', ['elementos' => $expedientes, 'nombre' => $nombre]);
        }

        public function asignadosGV($id_cliente){
        
            if (!is_numeric($id_cliente)) {
                return redirect()->route('homeClientesGV')->with('error', 'El campo debe ser un número.');
            }

            $cliente = DB::table('clientes_guardavalores')
                ->where('id_cliente', $id_cliente)
                ->first();
            $nombre = $cliente->nombre;
        
            $guardavalores = DB::table('guardavalores')
                ->where('id_cliente',$id_cliente)
                ->get();

                $idUser = DB::table('users')
            ->where('idUsuarioSistema', auth()->id()) // Filtrar por el ID del usuario autenticado
            ->value('idUsuarioSistema');
            
            $user = DB::table('users')->where('idUsuarioSistema', $idUser)->first();
            $idRol = $user->rol;
            
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
        
            if ($guardavalores->isEmpty()) { // Verifica si la colección de gv está vacía
                session()->flash('info', 'El expediente no cuenta con documentos asignados.');
                return redirect()->route('homeClientesGV');
            }
            //CAMBIAR A GV
            return view('guardavalores.guardavalores.asignadosACliente', ['listadoPermisos'=> $permisosUsuario, 'elementos' => $guardavalores, 'nombre' => $nombre]);
        }

        public function nuevoClienteBasico($id_usuario){
            return view('expedientes.clientes.clientesCrearBasico', ['id_usuario' => $id_usuario]);
        }
    
}
