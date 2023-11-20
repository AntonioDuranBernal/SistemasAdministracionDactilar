<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class UsuariosController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }
    

    public function actividadInicioGV($idUser) {

            //if (Auth::check()) {
                $idUser = DB::table('users')
                ->where('idUsuarioSistema', auth()->id()) // Filtrar por el ID del usuario autenticado
                ->value('idUsuarioSistema');
    
            $user = DB::table('users')->where('idUsuarioSistema', $idUser)->first();
    
            $consulta = DB::table('users')
            ->select('registrarGuardavalores', 'retirarGuardavalores', 'editarGuardavalores', 'consultarGuardavalores', 'reportesGuardavalores')
            ->where('idUsuarioSistema', $idUser)
            ->first();
        
            $permisos = (array) $consulta;
            $permisosUsuario = [];
            
            DB::table('users')     //AISGANCION DE AREA EN USO
            ->where('idUsuarioSistema', $idUser)
            ->update(['area' => 2]);
    
            foreach ($permisos as $indice => $valor) {
            $permisosUsuario[] = ['indice' => $indice, 'valor' => $valor];
            }
    
            $elementos = [];        
    
            foreach ($permisos as $permiso) {
                echo $permiso . " ";
            }
    
            if ($user) {
                return redirect()->route('homeAdminGuardavalores');
            }
            else {
            echo "Auth Check Falló, no logueado.";
            return redirect()->route('home');
            }

    }
    
    public function actividadInicio($idUser) {

        //$usuario = Auth::user();
        
        $idUser = DB::table('users')
                ->where('idUsuarioSistema', auth()->id()) // Filtrar por el ID del usuario autenticado
                ->value('idUsuarioSistema');

        $nombre = ' ES BASICO EN USUARIO CONTROLLER ';
        echo "USUARIO LOGEADOO: ".$idUser. " NOMBRE: ".$nombre;

        DB::table('users')     //AISGANCION DE AREA EN USO
        ->where('idUsuarioSistema', $idUser)
        ->update(['area' => 1]);

        $user = DB::table('users')->where('idUsuarioSistema', $idUser)->first();
        
        if ($user) {
            return redirect()->route('homeAdminExpedientes');
        } else {
            // NO SE ENCONTRO USUARIO
            //SALIR Y ENVIAR A LOGIN
            return redirect()->route('home');            
            echo "Usuario no encontrado";
        }

    }
    
    
}
