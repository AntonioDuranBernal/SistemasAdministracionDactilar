<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;


class LoginController extends Controller
{

    public function showLoginForm()
    {
        return view('login');
    }

    protected function attemptLogin(Request $request)
    {
        
        $credentials = $request->validate([
        'idUsuarioSistema' => ['required', 'numeric'], 
        'password' => ['required', 'numeric'],
        ]);

        if (Auth::attempt($credentials)) {

            // Autenticación exitosa
            $idUsuarioSistema = $request->idUsuarioSistema;
            $password = $request->password;

        $sql = DB::table('users')->select('idUsuarioSistema')->where('idUsuarioSistema', $idUsuarioSistema)->first();

        $permisos = DB::table('users')
            ->select('registrarExpediente', 'consultarExpediente', 'editarExpediente', 'eliminarExpediente', 'reportesExpediente',
                'registrarGuardavalores', 'retirarGuardavalores', 'editarGuardavalores', 'consultarGuardavalores', 'reportesGuardavalores')
            ->where('idUsuarioSistema', $idUsuarioSistema)
            ->first();
        
        // Verificar si el usuario tiene permisos sobre expedientes o guardavalores
        if ($permisos) {
            $tienePermisosExpediente = $permisos->registrarExpediente || $permisos->consultarExpediente || $permisos->editarExpediente || $permisos->eliminarExpediente || $permisos->reportesExpediente;
            $tienePermisosGuardavalores = $permisos->registrarGuardavalores || $permisos->retirarGuardavalores || $permisos->editarGuardavalores || $permisos->consultarGuardavalores || $permisos->reportesGuardavalores;
        
            // Guardar los permisos en un array
            $permisos = [
                'expediente' => $tienePermisosExpediente,
                'guardavalores' => $tienePermisosGuardavalores,
            ];
        
            if (!$tienePermisosExpediente && !$tienePermisosGuardavalores) {
                $mensaje = 'No se tienen permisos asignados.';
                return view('welcome', compact('mensaje'));
            }
        }
        
        $request->session()->regenerate();

        $usuario = Auth::user();
        $idUsuarioSistema = $usuario->idUsuarioSistema;
        $nombre = $usuario->nombre;

        echo "USUARIO LOGEADOO: ".$idUsuarioSistema. " NOMBRE LOGIN CONTROLLER: ".$nombre;

        // Redirigir a la vista 'opciones' con los permisos
        return view('opcionesArea', ['permisos' => $permisos, 'idUsuarioSistema' => $idUsuarioSistema]);
            
        } else {
            // Autenticación fallida
            $mensaje = 'Credenciales no válidas.';
            return view('welcome', compact('mensaje'));
        }
  
    }



}