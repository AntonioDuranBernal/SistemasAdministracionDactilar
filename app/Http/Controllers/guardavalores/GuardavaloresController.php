<?php

namespace App\Http\Controllers\guardavalores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GuardavaloresController extends Controller
{
    

    public function homeAdminGuardavalores(){

        //$usuario = Auth::user();
        //$idUser = $usuario->idUsuarioSistema;
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

        $elementos = DB::table('actividad_guardavalores')->get();
        $elementosActualizados = [];
    
        foreach ($elementos as $elemento) {
            $nombreContrato = DB::table('contrato')
                ->where('id_docuemento', $elemento->id_documento)
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
            $elementoOriginal['id_contrato'] = $nombreContrato;
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

        return view('guardavalores.home', ['idRol' => 3, 'elementos' => $elementosActualizados, 'listadoPermisos' => $permisosUsuario]);
    
    }


}
