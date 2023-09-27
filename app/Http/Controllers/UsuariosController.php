<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UsuariosController extends Controller
{
    
    public function actividadInicio($idUser) {

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

        $elementos = DB::table('actividad_expedientes')
        ->join('expedientes', 'actividad_expedientes.id_expediente', '=', 'expedientes.id_expediente')
        ->select('actividad_expedientes.*', 'expedientes.nombre AS nombre')
        ->where('id_usuario_solicita', $idUser)
        ->get();

        foreach ($permisos as $permiso) {
            echo $permiso . " ";
        }

        if ($user) {
            // Verificamos el valor del rol (supongamos que el rol se almacena en un campo llamado 'rol' en la tabla 'users')
            switch ($user->rol) {
                case 1:

                    return view('expedientes.super.homeAdmin', ['elementos' => $elementos,'permisosUsuario' => $permisosUsuario, 'usuario' => $idUser]);
                    break;

                case 2:
                    
                    return view('expedientes.super.homeAdmin', ['elementos' => $elementos,'permisosUsuario' => $permisosUsuario, 'usuario' => $idUser]);
                    break;
                    
                case 3:
                    return redirect()->route('homeAdminExpedientes');
                    break;
                default:
                    // Código a ejecutar cuando el rol no coincide con ninguno de los casos anteriores
                    echo "Rol desconocido";
            }
        } else {
            // NO SE ENCONTRO USUARIO
            echo "Usuario no encontrado";
        }

    }


    /*public function clientesUsuarioBasico($idUser) {

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

        $elementos = DB::table('actividad_expedientes')
        ->join('expedientes', 'actividad_expedientes.id_expediente', '=', 'expedientes.id_expediente')
        ->where('actividad_expedientes.id_usuario_solicita', $idUser)
        ->select('actividad_expedientes.*', 'expedientes.nombre AS nombre')
        ->get();


        if ($user) {
            // Verificamos el valor del rol (supongamos que el rol se almacena en un campo llamado 'rol' en la tabla 'users')
            switch ($user->rol) {
                case 1:
                    echo "Permisos de lectura: ";
                    foreach ($permisos as $permiso) {
                        echo $permiso . " ";
                    }
                    return view('expedientes.usuarios.inicioUsuario', ['elementos' => $elementos,'permisosUsuario' => $permisosUsuario, 'usuario' => $user]);
                    break;
                case 2:
                    // Código a ejecutar cuando el rol es 2
                    echo "Ejecutar código para ADMINISTRADOR";
                    break;
                case 3:
                    
                    return redirect()->route('homeAdminExpedientes');
                    break;
                default:
                    // Código a ejecutar cuando el rol no coincide con ninguno de los casos anteriores
                    echo "Rol desconocido";
            }
        } else {
            // NO SE ENCONTRO USUARIO
            echo "Usuario no encontrado";
        }

    }

    */

    
}
