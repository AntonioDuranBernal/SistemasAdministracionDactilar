<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class UsuariosController extends Controller
{

    public function actividadInicioGV($idUser) {

        //if (Auth::check()) {
            
        //$usuario = Auth::user();
        //$idUser = $usuario->idUsuarioSistema;        
        $idUser = 1;

        $user = DB::table('users')->where('idUsuarioSistema', $idUser)->first();

        $consulta = DB::table('users')
        ->select('registrarGuardavalores', 'retirarGuardavalores', 'editarGuardavalores', 'consultarGuardavalores', 'reportesGuardavalores')
        ->where('idUsuarioSistema', $idUser)
        ->first();
    
        $permisos = (array) $consulta;
        $permisosUsuario = [];
    
        foreach ($permisos as $indice => $valor) {
        $permisosUsuario[] = ['indice' => $indice, 'valor' => $valor];
        }

        $elementos = [];        

        foreach ($permisos as $permiso) {
            echo $permiso . " ";
        }

        if ($user) {
            // Verificamos el valor del rol (supongamos que el rol se almacena en un campo llamado 'rol' en la tabla 'users')
            switch ($user->rol) {
                case 1:
                    //return view('expedientes.super.homeAdmin', ['elementos' => $elementos,'permisosUsuario' => $permisosUsuario, 'usuario' => $idUser]);
                    break;
                case 2:
                    //return view('expedientes.super.homeAdmin', ['elementos' => $elementos,'permisosUsuario' => $permisosUsuario, 'usuario' => $idUser]);
                    break;
                case 3:
                    return redirect()->route('homeAdminGuardavalores');
                    break;
                default:
                    echo "Rol desconocido";
            }
        } else {
            // NO SE ENCONTRO USUARIO
            echo "Usuario no encontrado";
        }

        //}else {
          //  echo "Usuario no logeado";
        // }

    }
    
    public function actividadInicio($idUser) {

        $usuario = Auth::user();
        //$idUser = $usuario->idUsuarioSistema;
        //$nombre = $usuario->nombre;
        
        $idUser = 7;
        $nombre = ' ES BASICO EN USUARIO CONTROLLER ';
        echo "USUARIO LOGEADOO: ".$idUser. " NOMBRE: ".$nombre;

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
