<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;


class LoginController extends Controller
{
    //use AuthenticatesUsers;

    protected $redirectTo = '/home';

    /*public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }*/


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

        $idUsuarioSistema = $request->idUsuarioSistema;
        $password = $request->password;

        $sql = DB::table('users')->select('idUsuarioSistema')->where('idUsuarioSistema', $idUsuarioSistema)->first();
        
        if (!$sql || $password!=12345) {
            $mensaje = 'Credenciales no validas.';
            return view('welcome', compact('mensaje'));
        }

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
        
        // Redirigir a la vista 'opciones' con los permisos
        return view('opcionesArea', ['permisos' => $permisos, 'idUsuarioSistema' => $idUsuarioSistema]);
        
    }




    /*protected function attemptLogin(Request $request){

    $credentials = $request->validate([
        'idUsuarioSistema' => ['required', 'numeric'], 
        'password' => ['required', 'numeric'],
    ]); 

    $idUsuarioSistema = 2;

    /*if (Auth::attempt($credentials)) {
        // Si la autenticación es exitosa, el usuario estará autenticado
        $request->session()->regenerate();
        $user = Auth::user();

        // Obtener el ID del usuario logeado
        $idUsuarioSistema = $user->idUsuarioSistema;

        // Redirigir a la ruta 'actividadInicio' con el parámetro 'idUsuarioSistema'
        return redirect()->route('actividadInicio', ['idUsuarioSistema' => $idUsuarioSistema]);
    } else {
        // Si la autenticación falla, puedes manejar el error aquí
        throw ValidationException::withMessages([
            'idUsuarioSistema' => [trans('auth.failed')],
        ]);

    }

    return redirect()->route('actividadInicio', ['idUsuarioSistema' => $idUsuarioSistema]);

    }*/



}