<?php

namespace App\Http\Controllers;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Style\Alignment;



class reportesController extends Controller
{    

    public function exportarExpedientesR4(Request $request)
    {
        $elementos = $request->input('elementos');

        $archivo = public_path('exports/reporteAtrasos_formato.xlsx');
        $spreadsheet = IOFactory::load($archivo);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Reporte Atrasos');        

        // Llenar el archivo Excel con los datos del arreglo
        $row = 7;
        foreach ($elementos as $elemento) {
            $motivo = isset($elemento['motivo']) ? $elemento['motivo'] : 'Sin motivo';
            $sheet->setCellValue('C' . $row, $elemento['id_expediente']);
            $sheet->setCellValue('D' . $row, $elemento['id_usuario_otorga']);
            $sheet->setCellValue('E' . $row, $elemento['id_usuario_solicita']);
            $sheet->setCellValue('F' . $row, $motivo);
            $sheet->setCellValue('G' . $row, $elemento['fecha_solicitud']);
            $sheet->setCellValue('H' . $row, $elemento['fecha_devolucion']);
            $sheet->setCellValue('I' . $row, $elemento['estado']);
            $row++;
        }

        // Crear el archivo Excel
        $writer = new Xlsx($spreadsheet);

        // Configurar las cabeceras para la descarga
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="atrasosExpedientes.xlsx"');
        header('Cache-Control: max-age=0');

        // Enviar el archivo al cliente
        $writer->save('php://output');

    } 

    public function ejecutarExpedienteAtrasosSU(Request $request)
    {
    $fecha_inicio = $request->input('fecha_inicio');
    $fecha_fin = $request->input('fecha_fin');
    $filtro = $request->input('filtro');

    if ($filtro == 1) {
        $elementos = DB::table('actividad_expedientes')
            ->whereBetween('fecha_solicitud', [$fecha_inicio, $fecha_fin])
            ->where('estado', 'En uso')
            ->get();
    } elseif ($filtro == 2) {
        $elementos = DB::table('actividad_expedientes')
            ->whereBetween('fecha_solicitud', [$fecha_inicio, $fecha_fin])
            ->where('estado', 'Devolución atrasada')
            ->get();
    } else {
        $elementos = DB::table('actividad_expedientes')
            ->whereBetween('fecha_solicitud', [$fecha_inicio, $fecha_fin])
            ->whereIn('estado', ['En uso', 'Devolución atrasada'])
            ->get();
    }

    // Inicializa la variable $registros como un arreglo vacío
    $registros = [];

    // Verifica si la consulta no devolvió resultados
    if ($elementos->isEmpty()) {
        $elementosActualizados = [];
    } else {
        $elementosActualizados = [];

        foreach ($elementos as $elemento) {
            $nombreExpediente = DB::table('expedientes')
                ->where('id_expediente', $elemento->id_expediente)
                ->value('nombre');

            $nombreUsuario = DB::table('users')
                ->where('idUsuarioSistema', $elemento->id_usuario_solicita)
                ->value('nombre');

            // Obtener los datos originales del elemento
            $elementoOriginal = (array) $elemento;

            // Formatear las fechas en día, mes y año
            $elementoOriginal['fecha_solicitud'] = date('d-m-Y', strtotime($elemento->fecha_solicitud));
            $elementoOriginal['fecha_devolucion'] = date('d-m-Y', strtotime($elemento->fecha_devolucion));

            // Actualizar los campos necesarios
            $elementoOriginal['id_usuario_otorga'] = $nombreExpediente;
            $elementoOriginal['id_usuario_solicita'] = $nombreUsuario;

            // Agregar el registro actualizado al arreglo
            $elementosActualizados[] = (object) $elementoOriginal;
        }

        // Configurar los registros con los elementos actualizados
        $registros = $elementosActualizados;
       }

        return view('expedientes.reportes.homeReportes4', [
        'elementos' => $elementosActualizados,
        'registros' => json_encode($registros),
       ]);
    }

    public function exportarExpedientes(Request $request)
    {
        $elementos = $request->input('elementos');

        $archivo = public_path('exports/reporteGeneral_formato.xlsx');
        $spreadsheet = IOFactory::load($archivo);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Reporte General');        

        // Llenar el archivo Excel con los datos del arreglo
        $row = 7;
        foreach ($elementos as $elemento) {
            $motivo = isset($elemento['motivo']) ? $elemento['motivo'] : 'Sin motivo';
            $sheet->setCellValue('C' . $row, $elemento['id_expediente']);
            $sheet->setCellValue('D' . $row, $elemento['id_usuario_solicita']);
            $sheet->setCellValue('E' . $row, $motivo);
            $sheet->setCellValue('F' . $row, $elemento['fecha_solicitud']);
            $sheet->setCellValue('G' . $row, $elemento['fecha_devolucion']);
            $sheet->setCellValue('H' . $row, $elemento['estado']);
            $row++;
        }

        // Crear el archivo Excel
        $writer = new Xlsx($spreadsheet);

        // Configurar las cabeceras para la descarga
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="expedientes.xlsx"');
        header('Cache-Control: max-age=0');

        // Enviar el archivo al cliente
        $writer->save('php://output');

    }

    public function exportarExpedientesR3(Request $request)
    {
        $elementos = $request->input('elementos');
        $id_usuario = $request->input('id_usuario');

        $archivos = public_path('exports/reporteUsuario_formato.xlsx');
        $spreadsheet = IOFactory::load($archivos);
        $sheet = $spreadsheet->getActiveSheet(); 
        $sheet->setTitle('Usuario '.$id_usuario);     

                // Llenar el archivo Excel con los datos del arreglo
                $row = 7;
                foreach ($elementos as $elemento) {
                    $motivo = isset($elemento['motivo']) ? $elemento['motivo'] : 'Sin motivo';

                    $sheet->setCellValue('C' . $row, $elemento['id_expediente']); 
                    $sheet->setCellValue('D' . $row, $elemento['id_usuario_solicita']);
                    $sheet->setCellValue('E' . $row, $motivo);
                    $sheet->setCellValue('F' . $row, $elemento['fecha_solicitud']);
                    $sheet->setCellValue('G' . $row, $elemento['fecha_devolucion']);
                    $sheet->setCellValue('H' . $row, $elemento['estado']);
                    $row++;
                }

                        // Crear el archivo Excel
        $writer = new Xlsx($spreadsheet);

        // Configurar las cabeceras para la descarga
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="documento.xlsx"');
        header('Cache-Control: max-age=0');

        // Enviar el archivo al cliente
        $writer->save('php://output');
    
    }
    

    public function exportarExpedientesR2(Request $request)
    {
        $elementos = $request->input('elementos');

        $id_expediente = $request->input('id_expediente');


        $archivos = public_path('exports/reporteDocumento_formato.xlsx');
        $spreadsheet = IOFactory::load($archivos);
        $sheet = $spreadsheet->getActiveSheet(); 
        $sheet->setTitle('Documento '.$id_expediente);        

        // Llenar el archivo Excel con los datos del arreglo
        $row = 7;
        foreach ($elementos as $elemento) {
            $motivo = isset($elemento['motivo']) ? $elemento['motivo'] : 'Sin motivo';
            $sheet->setCellValue('C' . $row, $elemento['id_usuario_solicita']);
            $sheet->setCellValue('D' . $row, $elemento['id_expediente']); 
            $sheet->setCellValue('E' . $row, $motivo);
            $sheet->setCellValue('F' . $row, $elemento['fecha_solicitud']);
            $sheet->setCellValue('G' . $row, $elemento['fecha_devolucion']);
            $sheet->setCellValue('H' . $row, $elemento['estado']);
            $row++;
        }

        // Crear el archivo Excel
        $writer = new Xlsx($spreadsheet);

        // Configurar las cabeceras para la descarga
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="documento.xlsx"');
        header('Cache-Control: max-age=0');

        // Enviar el archivo al cliente
        $writer->save('php://output');

    }

    public function ejecutarExpedienteDocumentoSU(Request $request)
    {
        $id_expediente = $request->input('id_expediente');
        $registros = [];

        $elementos = DB::table('actividad_expedientes')
        ->where('id_expediente', $id_expediente)
        ->get();
        
        // Verifica si la consulta no devolvió resultados
        if ($elementos->isEmpty()) {
            $elementosActualizados = [];
        } else {
            $elementosActualizados = [];
    
            foreach ($elementos as $elemento) {

                $nombreExpediente = DB::table('expedientes')
                    ->where('id_expediente', $elemento->id_expediente)
                    ->value('nombre');
    
                /*$nombreUsuario = DB::table('users')
                    ->where('idUsuarioSistema', $elemento->id_usuario_solicita)
                    ->value('nombre');*/

                    $nombreUsuario = DB::table('users')
                    ->where('idUsuarioSistema', $elemento->id_usuario_solicita)
                    ->select(DB::raw('CONCAT(nombre, " ", apellidos) as nombreCompleto'))
                    ->value('nombreCompleto');
                
    
                // Obtener los datos originales del elemento
                $elementoOriginal = (array) $elemento;
    
                // Formatear las fechas en día, mes y año
                $elementoOriginal['fecha_solicitud'] = date('d-m-Y', strtotime($elemento->fecha_solicitud));
                $elementoOriginal['fecha_devolucion'] = date('d-m-Y', strtotime($elemento->fecha_devolucion));
    
                // Actualizar los campos necesarios
                $elementoOriginal['id_expediente'] = $nombreUsuario;
                //$elementoOriginal['id_usuario_solicita'] = $nombreUsuario;
    
                // Agregar el registro actualizado al arreglo
                $elementosActualizados[] = (object) $elementoOriginal;
            }
    
            // Configurar los registros con los elementos actualizados
            $registros = $elementosActualizados;
           }
                                             
        return view('expedientes.reportes.reportesSuper2', [
        'elementos' => $elementosActualizados,
        'registros' => json_encode($registros),
       ]);


    }

    public function ejecutarExpedienteUsuarioSU(Request $request)
    {
        $id_usuario = $request->input('id_usuario');
        $registros = [];
        $elementos = DB::table('actividad_expedientes')
        ->where('id_usuario_solicita', $id_usuario)
        ->get();

        if ($elementos->isEmpty()) {
            $elementosActualizados = [];
        } else {
            $elementosActualizados = [];
    
            foreach ($elementos as $elemento) {
                $nombreExpediente = DB::table('expedientes')
                    ->where('id_expediente', $elemento->id_expediente)
                    ->value('nombre');
    
                $nombreUsuario = DB::table('users')
                    ->where('idUsuarioSistema', $elemento->id_usuario_solicita)
                    ->value('nombre');
    
                // Obtener los datos originales del elemento
                $elementoOriginal = (array) $elemento;
    
                // Formatear las fechas en día, mes y año
                $elementoOriginal['fecha_solicitud'] = date('d-m-Y', strtotime($elemento->fecha_solicitud));
                $elementoOriginal['fecha_devolucion'] = date('d-m-Y', strtotime($elemento->fecha_devolucion));
    
                // Actualizar los campos necesarios
                //$elementoOriginal['id_expediente'] = $nombreExpediente;
                $elementoOriginal['id_usuario_solicita'] = $nombreExpediente;
    
                // Agregar el registro actualizado al arreglo
                $elementosActualizados[] = (object) $elementoOriginal;
            }
    
            // Configurar los registros con los elementos actualizados
            $registros = $elementosActualizados;
           }

           return view('expedientes.reportes.homeReportes3', [
            'elementos' => $elementosActualizados,
            'registros' => json_encode($registros),
           ]);

    }

    
    public function ejecutarExpedienteGeneralSU(Request $request)
    {
    $fecha_inicio = $request->input('fecha_inicio');
    $fecha_fin = $request->input('fecha_fin');

    // Inicializa la variable $registros como un arreglo vacío
    $registros = [];

    $elementos = DB::table('actividad_expedientes')
        ->whereBetween('fecha_solicitud', [$fecha_inicio, $fecha_fin])
        ->get();

    // Verifica si la consulta no devolvió resultados
    if ($elementos->isEmpty()) {
        $elementosActualizados = [];
    } else {
        $elementosActualizados = [];

        foreach ($elementos as $elemento) {
            $nombreExpediente = DB::table('expedientes')
                ->where('id_expediente', $elemento->id_expediente)
                ->value('nombre');

            $nombreUsuario = DB::table('users')
                ->where('idUsuarioSistema', $elemento->id_usuario_solicita)
                ->value('nombre');

            // Obtener los datos originales del elemento
            $elementoOriginal = (array) $elemento;

            // Formatear las fechas en día, mes y año
            $elementoOriginal['fecha_solicitud'] = date('d-m-Y', strtotime($elemento->fecha_solicitud));
            $elementoOriginal['fecha_devolucion'] = date('d-m-Y', strtotime($elemento->fecha_devolucion));

            // Actualizar los campos necesarios
            $elementoOriginal['id_expediente'] = $nombreExpediente;
            $elementoOriginal['id_usuario_solicita'] = $nombreUsuario;

            // Agregar el registro actualizado al arreglo
            $elementosActualizados[] = (object) $elementoOriginal;
        }

        // Configurar los registros con los elementos actualizados
        $registros = $elementosActualizados;
       }

        return view('expedientes.reportes.homeReportesSuper', [
        'elementos' => $elementosActualizados,
        'registros' => json_encode($registros),
       ]);
    }


    public function homeReportesSuper() {
    return view('expedientes.reportes.homeReportesSuper',['elementos' => []]);
    }

    public function homeDocumentoSuper() {
        return view('expedientes.reportes.reportesSuper2',['elementos' => []]);
    }

    public function homeUsuarioSuper() {
        return view('expedientes.reportes.homeReportes3',['elementos' => []]);
    }

    public function homeAtrasosSuper() {
        return view('expedientes.reportes.homeReportes4',['elementos' => []]);
    }

    



    public function homeReportesBasico($id_u) {
        return view('expedientes.reportes.homeReportesBasico',['elementos' => [], 'id_usuario'=>$id_u]);
    }
    



}
