<?php

namespace App\Http\Controllers\expedientes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientesController extends Controller
{



    
    public function asignadosUBasico($id_cliente,$idUser) {

        if (!is_numeric($id_cliente)) {
            return redirect()->route('homeClientesSuper')->with('error', 'El campo nùmero de cliente debe ser un número.');
        }

        $cliente = DB::table('clientes_expedientes')
            ->where('id_cliente', $id_cliente)
            ->first();
        $nombre = $cliente->nombre;
    
        $expedientes = DB::table('expedientes')
            ->where('id_cliente', $id_cliente)
            ->get();
    
        if ($expedientes->isEmpty()) { // Verifica si la colección de expedientes está vacía
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


    public function searchBasico(Request $request) {
        $id_cliente = $request->input('id_cliente');
        $id_usuario = $request->input('id_usuario');

        $usuario = DB::table('users')->where('idUsuarioSistema', $id_usuario)->first();

    
        if (!is_numeric($id_cliente)) {
            return redirect()->route('homeClientesSuper')->with('error', 'El campo id_cliente debe ser un número.');
        }
    
        $cliente = DB::table('clientes_expedientes')
            ->where('id_cliente', $id_cliente)
            ->first();
    
        if (is_null($cliente)) {
            //return redirect()->route('homeClientesUsuario',$id_usuario)->with('error', 'No se encontró ningún cliente con el ID proporcionado.');
            return redirect()->route('homeClientesUsuario', $id_usuario);

        }
    
        $elementos = [];
        array_push($elementos, $cliente);

        $consulta = DB::table('users')
        ->select('registrarExpediente', 'consultarExpediente', 'editarExpediente', 'eliminarExpediente', 'reportesExpediente')
        ->where('idUsuarioSistema', $id_usuario)
        ->first();
    
        $permisos = (array) $consulta;
        $permisosUsuario = [];
    
        foreach ($permisos as $indice => $valor) {
        $permisosUsuario[] = ['indice' => $indice, 'valor' => $valor];
        }

        echo "Permisos de lectura: ";
                    foreach ($permisos as $permiso) {
                        echo $permiso . " ";
                    }
    
        return view('expedientes.clientes.homeClientesBasico', ['elementos' => $elementos, 'permisosUsuario'=>$permisosUsuario,'usuario' => $usuario]);
    }
    
    public function inicioClientesUsuarioX($idUser) {
    
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
        ->get();

                    echo "Permisos de lectura: ";
                    foreach ($permisos as $permiso) {
                        echo $permiso . " ";
                    }

        return view('expedientes.clientes.homeClientesBasico', ['elementos' => $clientes,'permisosUsuario' => $permisosUsuario, 'usuario' => $user]);


    }

    public function inicioClientes(){
        $elementos = DB::table('clientes_expedientes')
                        ->where('nombre', '!=', '') // Solo registros donde el nombre no está vacío
                        ->get();
    
        return view('expedientes.clientes.homeClientesSuper', ['elementos' => $elementos]);
    }
    
    
    public function search(Request $request) {
        $id_cliente = $request->input('id_cliente');
    
        if (!is_numeric($id_cliente)) {
            return redirect()->route('homeClientesSuper')->with('error', 'El campo id_cliente debe ser un número.');
        }
    
        $cliente = DB::table('clientes_expedientes')
            ->where('id_cliente', $id_cliente)
            ->first();
    
        if (is_null($cliente)) {
            return redirect()->route('homeClientesSuper')->with('error', 'No se encontró ningún cliente con el ID proporcionado.');
        }
    
        $elementos = [];
        array_push($elementos, $cliente);
    
        return view('expedientes.clientes.homeClientesSuper', ['elementos' => $elementos]);
    }

    public function clienteNuevoExpediente($id_cliente){
        date_default_timezone_set('America/Mexico_City');
        $fecha_actual = date("Y-m-d h:m:s");
        //$stringDate = date("Y-m-d",strtotime($fecha_actual."+ 3 days"));
        $Cliente = DB::table('clientes_expedientes')
        ->where('id_cliente', $id_cliente)
        ->first();

        return view('expedientes.expedientes.asignarExpedienteCrear',['fechaRegistro'=>$fecha_actual,'cliente'=>$Cliente]);
        
        }

        public function storeExpediente(Request $request) {
            
            $idCliente = $request->id_ciente;
            $nombreExpediente = $request->nombreExpediente;
            $descripcion = $request->descripcion;
            $folioReal = $request->folioReal;
            $otrosDatos = $request->otrosDatos;
            $fechaCreacion = now();
            $usuarioCreador = 1; // Cambia esto según tu lógica
            $estado = 'Disponible'; // Cambia esto según tu lógica
        
            DB::table('expedientes')->insert([
                'id_cliente' => $idCliente,
                'nombre' => $nombreExpediente,
                'descripcion' => $descripcion,
                'folio_real' => $folioReal,
                'otros_datos' => $otrosDatos,
                'fecha_creacion' => $fechaCreacion,
                'usuario_creador' => $usuarioCreador,
                'estado' => $estado,
            ]);

            return redirect()->route('homeExpedientes');
        }

        public function nuevo(){
            return view('expedientes.clientes.clientesCrear');
        }

        public function store(Request $request){
            $nombre = $request->nombre;
            DB::table('clientes_expedientes')->insert([
                'nombre' => $nombre,
            ]);
            return redirect()->route('homeClientesSuper');
        }

        public function storeUsuarioClienteBasico(Request $request){
            $nombre = $request->nombre;
            $id_usuario = $request->id_usuario;
            DB::table('clientes_expedientes')->insert([
                'nombre' => $nombre,
            ]);
            return redirect()->route('homeClientesUsuario', $id_usuario);

        }

        
        

        /*public function asignados($id_cliente){
        
            if (!is_numeric($id_cliente)) {
                return redirect()->route('homeClientesSuper')->with('error', 'El campo id_cliente debe ser un número.');
            }
        
            $expedientes = DB::table('expedientes')
                ->where('id_cliente', $id_cliente)
                ->get();
        
            if (is_null($expedientes)) {
                return redirect()->route('homeClientesSuper')->with('error', 'No se encontró ningún expediente de ese cliente.');
            }
        
            $elementos = [];
            array_push($elementos, $expedientes);
        
            return view('expedientes.clientes.asignadosCliente', ['elementos' => $elementos]);
        }*/

        public function asignados($id_cliente){
        
            if (!is_numeric($id_cliente)) {
                return redirect()->route('homeClientesSuper')->with('error', 'El campo nùmero de cliente debe ser un número.');
            }

            $cliente = DB::table('clientes_expedientes')
                ->where('id_cliente', $id_cliente)
                ->first();
            $nombre = $cliente->nombre;
        
            $expedientes = DB::table('expedientes')
                ->where('id_cliente', $id_cliente)
                ->get();
        
            if ($expedientes->isEmpty()) { // Verifica si la colección de expedientes está vacía
                return redirect()->route('homeClientesSuper')->with('error', 'No se encontró ningún expediente de ese cliente.');
            }
        
            return view('expedientes.clientes.asignadosCliente', ['elementos' => $expedientes, 'nombre' => $nombre]);
        }

        public function nuevoClienteBasico($id_usuario){
            return view('expedientes.clientes.clientesCrearBasico', ['id_usuario' => $id_usuario]);
        }
        

        
    

    
}
