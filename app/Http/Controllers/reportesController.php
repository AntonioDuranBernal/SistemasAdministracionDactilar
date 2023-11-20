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

            $exp = DB::table('expedientes')
            ->where('id_expediente', $elemento->id_expediente)
            ->first();
                
            $nombreExpediente  = $exp->nombre;
            $id_cli = $exp->id_cliente;

            $nombreUsuario = DB::table('users')
                ->where('idUsuarioSistema', $elemento->id_usuario_solicita)
                ->value('nombre');

                $Cliente = DB::table('clientes_expedientes')
                    ->where('id_consecutivo', $id_cli)
                    ->first();

            // Obtener los datos originales del elemento
            $elementoOriginal = (array) $elemento;

            if ($Cliente) {
                $nombreCliente = $Cliente->nombre;
                $elementoOriginal['tomo'] = $nombreCliente;

            } else {
            echo "Cliente no encontrado.";
            }

            if (empty($elemento->fecha_entrega)) {
                $elementoOriginal['fecha_entrega'] = null;
            } else {
                $elementoOriginal['fecha_entrega'] = date('d-m-Y', strtotime($elemento->fecha_entrega));
            }
            
            if (empty($elemento->fecha_solicitud)) {
                $elementoOriginal['fecha_solicitud'] = null;
            } else {
                $elementoOriginal['fecha_solicitud'] = date('d-m-Y', strtotime($elemento->fecha_solicitud));
            }
            
            if (empty($elemento->fecha_devolucion)) {
                $elementoOriginal['fecha_devolucion'] = null;
            } else {
                $elementoOriginal['fecha_devolucion'] = date('d-m-Y', strtotime($elemento->fecha_devolucion));
            }

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

    public function exportarExpedientesGV(Request $request)
    {
        $elementos = $request->input('elementos');

        $archivo = public_path('exports/reporteGeneral_formatoGV.xlsx');
        $spreadsheet = IOFactory::load($archivo);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Reporte General GV');     

                // Llenar el archivo Excel con los datos del arreglo
                $row = 7;
                foreach ($elementos as $elemento) {
                    $motivo = isset($elemento['motivo']) ? $elemento['motivo'] : 'Sin motivo';
                    $sheet->setCellValue('B' . $row, $elemento['id_documento']);
                    $sheet->setCellValue('C' . $row, $elemento['estado']);
                    $sheet->setCellValue('D' . $row, $elemento['id_usuario']);
                    $sheet->setCellValue('E' . $row, $elemento['movimiento']);
                    $sheet->setCellValue('F' . $row, $motivo);
                    $sheet->setCellValue('G' . $row, $elemento['fecha_actividad']);
                    $row++;
                }
        
                // Crear el archivo Excel
                $writer = new Xlsx($spreadsheet);
        
                // Configurar las cabeceras para la descarga
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Actividad_Guardavalores.xlsx"');
                header('Cache-Control: max-age=0');
        
                // Enviar el archivo al cliente
                $writer->save('php://output');
        
    }

    public function exportarExpedientes(Request $request)
    {
        $elementos = $request->input('elementos');

        $archivo = public_path('exports/reporteGeneral_formato.xlsx');
        $spreadsheet = IOFactory::load($archivo);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Reporte de movimientos'); 

        // Llenar el archivo Excel con los datos del arreglo
        $row = 7;
        foreach ($elementos as $elemento) {

            $movimiento= isset($elemento['Movimiento']) ? $elemento['Movimiento'] : ' - - - ';
            $id_usuario_solicita = isset($elemento['id_usuario_solicita']) ? $elemento['id_usuario_solicita'] : ' - - - ';
            $fecha_solicitud = isset($elemento['fecha_solicitud']) ? $elemento['fecha_solicitud'] : ' - - - ';
            $fecha_devolucion = isset($elemento['fecha_devolucion']) ? $elemento['fecha_devolucion'] : ' - - - ';
            $fecha_entrega = isset($elemento['fecha_entrega']) ? $elemento['fecha_entrega'] : ' - - - ';

            $sheet->setCellValue('C' . $row, $elemento['tomo']);
            $sheet->setCellValue('D' . $row, $elemento['id_usuario_otorga']);
            $sheet->setCellValue('E' . $row, $movimiento);
            $sheet->setCellValue('F' . $row, $id_usuario_solicita);
            $sheet->setCellValue('G' . $row, $fecha_solicitud);
            $sheet->setCellValue('H' . $row, $fecha_devolucion);
            $sheet->setCellValue('I' . $row, $fecha_entrega);
            $sheet->setCellValue('J' . $row, $elemento['estado']);
            $row++;
        }

        // Crear el archivo Excel
        $writer = new Xlsx($spreadsheet);

        // Configurar las cabeceras para la descarga
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="MovimientosExpedientes.xlsx"');
        header('Cache-Control: max-age=0');

        // Enviar el archivo al cliente
        $writer->save('php://output');

    }

    public function exportarDocumentoGV2(Request $request)
    {
        $elementos = $request->input('elementos');

        $archivos = public_path('exports/documento_formatoGV.xlsx');
        $spreadsheet = IOFactory::load($archivos);
        $sheet = $spreadsheet->getActiveSheet(); 
        $sheet->setTitle('Documento'); 

                        // Llenar el archivo Excel con los datos del arreglo
                        $row = 7;
                        foreach ($elementos as $elemento) {
                            $motivo = isset($elemento['motivo']) ? $elemento['motivo'] : 'Sin motivo';
        
                            $sheet->setCellValue('C' . $row, $elemento['estado']); 
                            $sheet->setCellValue('D' . $row, $elemento['id_usuario']);
                            $sheet->setCellValue('E' . $row, $elemento['movimiento']);
                            $sheet->setCellValue('F' . $row, $motivo);
                            $sheet->setCellValue('G' . $row, $elemento['fecha_actividad']);
                            $row++;
                        }
        
                                // Crear el archivo Excel
                $writer = new Xlsx($spreadsheet);
        
                // Configurar las cabeceras para la descarga
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="ReporteDocumento.xlsx"');
                header('Cache-Control: max-age=0');
        
                // Enviar el archivo al cliente
                $writer->save('php://output');
    
    }

    public function exportarDocumentoGV(Request $request){
        
    $elementos = $request->input('elementos');
    dd($elementos);


    if (is_array($elementos)) {
        $archivos = public_path('exports/documento_formatoGV.xlsx');
        $spreadsheet = IOFactory::load($archivos);
        $sheet = $spreadsheet->getActiveSheet(); 
        $sheet->setTitle('Documento'); 

        // Llenar el archivo Excel con los datos del arreglo
        $row = 7;
        foreach ($elementos as $elemento) {
            $motivo = isset($elemento['motivo']) ? $elemento['motivo'] : 'Sin motivo';

            $sheet->setCellValue('C' . $row, $elemento['estado']); 
            $sheet->setCellValue('D' . $row, $elemento['id_usuario']);
            $sheet->setCellValue('E' . $row, $elemento['movimiento']);
            $sheet->setCellValue('F' . $row, $motivo);
            $sheet->setCellValue('G' . $row, $elemento['fecha_actividad']);
            $row++;
        }

        // Crear el archivo Excel
        $writer = new Xlsx($spreadsheet);

        // Configurar las cabeceras para la descarga
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteDocumento.xlsx"');
        header('Cache-Control: max-age=0');

        // Enviar el archivo al cliente
        $writer->save('php://output');
    }
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

                    
            $movimiento= isset($elemento['Movimiento']) ? $elemento['Movimiento'] : ' - - - ';
            $id_usuario_solicita = isset($elemento['id_usuario_solicita']) ? $elemento['id_usuario_solicita'] : ' - - - ';
            $fecha_solicitud = isset($elemento['fecha_solicitud']) ? $elemento['fecha_solicitud'] : ' - - - ';
            $fecha_devolucion = isset($elemento['fecha_devolucion']) ? $elemento['fecha_devolucion'] : ' - - - ';
            $fecha_entrega = isset($elemento['fecha_entrega']) ? $elemento['fecha_entrega'] : ' - - - ';

                    $sheet->setCellValue('C' . $row, $id_usuario_solicita);
                    $sheet->setCellValue('D' . $row, $movimiento);
                    $sheet->setCellValue('E' . $row, $fecha_solicitud);
                    $sheet->setCellValue('F' . $row, $fecha_devolucion);
                    $sheet->setCellValue('G' . $row, $fecha_entrega);
                    $sheet->setCellValue('H' . $row, $elemento['estado']);
                    $row++;
                }

                        // Crear el archivo Excel
        $writer = new Xlsx($spreadsheet);

        // Configurar las cabeceras para la descarga
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteUsuario.xlsx"');
        header('Cache-Control: max-age=0');

        // Enviar el archivo al cliente
        $writer->save('php://output');
    
    }
    
    public function exportarUsuarioGV(Request $request)
    {
        $elementos = $request->input('elementos');
        //$id_documento = $request->input('id_documento');

        $archivos = public_path('exports/Usuario_formatoGV.xlsx');
        $spreadsheet = IOFactory::load($archivos);
        $sheet = $spreadsheet->getActiveSheet(); 
        $sheet->setTitle('Documento'); 

                // Llenar el archivo Excel con los datos del arreglo
                $row = 7;
                foreach ($elementos as $elemento) {
                    $motivo = isset($elemento['motivo']) ? $elemento['motivo'] : 'Sin motivo';

                    $sheet->setCellValue('C' . $row, $elemento['tipo_gv']);
                    $sheet->setCellValue('D' . $row, $elemento['estado']); 
                    $sheet->setCellValue('E' . $row, $elemento['movimiento']);
                    $sheet->setCellValue('F' . $row, $elemento['fecha_actividad']);
                    $sheet->setCellValue('G' . $row, $motivo);
                    $row++;
                }
        
                // Crear el archivo Excel
                $writer = new Xlsx($spreadsheet);
        
                // Configurar las cabeceras para la descarga
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="ReporteUsuario.xlsx"');
                header('Cache-Control: max-age=0');
        
                // Enviar el archivo al cliente
                $writer->save('php://output');
        
            }


            public function exportarExpedientesR4(Request $request)
            {
                $elementos = $request->input('elementos');

        $archivos = public_path('exports/reporteAtrasos_formato.xlsx');
        $spreadsheet = IOFactory::load($archivos);
        $sheet = $spreadsheet->getActiveSheet(); 
        $sheet->setTitle('ReporteAtrasos');

              // Llenar el archivo Excel con los datos del arreglo
              $row = 7;
              foreach ($elementos as $elemento) {

                  $movimiento= isset($elemento['Movimiento']) ? $elemento['Movimiento'] : ' - - - ';
                  
                  $id_usuario_solicita = isset($elemento['id_usuario_solicita']) ? $elemento['id_usuario_solicita'] : ' - - - ';
                  $fecha_solicitud = isset($elemento['fecha_solicitud']) ? $elemento['fecha_solicitud'] : ' - - - ';
                  $fecha_devolucion = isset($elemento['fecha_devolucion']) ? $elemento['fecha_devolucion'] : ' - - - ';
                  $fecha_entrega = isset($elemento['fecha_entrega']) ? $elemento['fecha_entrega'] : ' - - - ';

                  $sheet->setCellValue('C' . $row, $elemento['tomo']);
                  $sheet->setCellValue('D' . $row, $elemento['id_usuario_otorga']); 
                  $sheet->setCellValue('E' . $row, $id_usuario_solicita); 
                  $sheet->setCellValue('F' . $row, $movimiento);
                  $sheet->setCellValue('G' . $row, $fecha_solicitud);
                  $sheet->setCellValue('H' . $row, $fecha_devolucion);
                  $sheet->setCellValue('I' . $row, $fecha_entrega);
                  $sheet->setCellValue('J' . $row, $elemento['estado']);
                  $row++;
              }
      
              // Crear el archivo Excel
              $writer = new Xlsx($spreadsheet);
      
              // Configurar las cabeceras para la descarga
              header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
              header('Content-Disposition: attachment;filename="ReporteAtrasos.xlsx"');
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

            $movimiento= isset($elemento['Movimiento']) ? $elemento['Movimiento'] : ' - - - ';
            $id_usuario_solicita = isset($elemento['id_usuario_realiza']) ? $elemento['id_usuario_realiza'] : ' - - - ';
            $fecha_solicitud = isset($elemento['fecha_solicitud']) ? $elemento['fecha_solicitud'] : ' - - - ';
            $fecha_devolucion = isset($elemento['fecha_devolucion']) ? $elemento['fecha_devolucion'] : ' - - - ';
            $fecha_entrega = isset($elemento['fecha_entrega']) ? $elemento['fecha_entrega'] : ' - - - ';
            
            $sheet->setCellValue('C' . $row, $elemento['OtroDato']);
            $sheet->setCellValue('D' . $row, $elemento['tomo']); 
            $sheet->setCellValue('E' . $row, $id_usuario_solicita);
            $sheet->setCellValue('F' . $row, $movimiento);
            $sheet->setCellValue('G' . $row, $fecha_solicitud);
            $sheet->setCellValue('H' . $row, $fecha_devolucion);
            $sheet->setCellValue('I' . $row, $fecha_entrega);
            $sheet->setCellValue('J' . $row, $elemento['estado']);
            $row++;
        }

        // Crear el archivo Excel
        $writer = new Xlsx($spreadsheet);

        // Configurar las cabeceras para la descarga
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteExpediente.xlsx"');
        header('Cache-Control: max-age=0');

        // Enviar el archivo al cliente
        $writer->save('php://output');

    }

    public function ejecutarExpedienteDocumentoSU(Request $request)
    {
        $id_consecutivo = $request->input('id_consecutivo');
        $id_expediente = $request->input('id_expediente');

        if($id_expediente){
            echo "Existe expediente, numero: ".$id_expediente;

        $registros = [];

        $elementos = DB::table('actividad_expedientes')
        ->where('id_expediente', $id_expediente)
        ->get();

        $expediente = DB::table('expedientes')
        ->where('id_expediente', $id_expediente)
        ->first();
        
        // Verifica si la consulta no devolvió resultados
        if ($elementos->isEmpty()) {
            $elementosActualizados = [];
        } else {
            $elementosActualizados = [];
    
            foreach ($elementos as $elemento) {

                $nombreExpedienteCliente = DB::table('clientes_expedientes')
                    ->where('id_consecutivo', $expediente->id_cliente)
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
    
                if (empty($elemento->fecha_entrega)) {
                    $elementoOriginal['fecha_entrega'] = null;
                } else {
                    $elementoOriginal['fecha_entrega'] = date('d-m-Y', strtotime($elemento->fecha_entrega));
                }
                
                if (empty($elemento->fecha_solicitud)) {
                    $elementoOriginal['fecha_solicitud'] = null;
                } else {
                    $elementoOriginal['fecha_solicitud'] = date('d-m-Y', strtotime($elemento->fecha_solicitud));
                }
                
                if (empty($elemento->fecha_devolucion)) {
                    $elementoOriginal['fecha_devolucion'] = null;
                } else {
                    $elementoOriginal['fecha_devolucion'] = date('d-m-Y', strtotime($elemento->fecha_devolucion));
                }

                // Actualizar los campos necesarios
                $elementoOriginal['OtroDato'] = $nombreExpedienteCliente;
                $elementoOriginal['id_usuario_solicita'] = $nombreUsuario;

                $elementoOriginal['tomo'] = $expediente->nombre;
    
                // Agregar el registro actualizado al arreglo
                $elementosActualizados[] = (object) $elementoOriginal;
            }
    
            // Configurar los registros con los elementos actualizados
            $registros = $elementosActualizados;
           }
              
           $listaDocumentos = DB::table('expedientes')
           ->get();
        
           $listaClientes = DB::table('clientes_expedientes')
           ->whereNotNull('id_consecutivo') // Filtra aquellos con id_consecutivo no nulo
           ->where('id_consecutivo', '!=', 0) // Filtra aquellos con id_consecutivo diferente de 0
           ->get();

        return view('expedientes.reportes.reportesSuper2', [
        'elementos' => $elementosActualizados,
        'registros' => json_encode($registros),
        'listaDocumentos'=>$listaDocumentos,
        'listaClientes'=>$listaClientes,
       ]);


        }elseif ($id_consecutivo) {
            echo "Buscando por cliente, numero: ".$id_consecutivo;

            $registros = [];
            $elementosActualizados = [];

            $listaClientes = DB::table('clientes_expedientes')
            ->whereNotNull('id_consecutivo') // Filtra aquellos con id_consecutivo no nulo
            ->where('id_consecutivo', '!=', 0) // Filtra aquellos con id_consecutivo diferente de 0
            ->get();
        
            $expedientes = DB::table('expedientes')
            ->where('id_cliente',$id_consecutivo)
            ->get();

            return view('expedientes.reportes.reportesSuper2', [
            'elementos' => $elementosActualizados,
            'registros' => json_encode($registros),
            'listaDocumentos'=>$expedientes,
            'listaClientes'=>$listaClientes,
           ]);
        }else{
            echo "Sin Expediente y sin tomo, general";
            $registros = [];
            $elementosActualizados = [];

            $listaClientes = DB::table('clientes_expedientes')
            ->whereNotNull('id_consecutivo') // Filtra aquellos con id_consecutivo no nulo
            ->where('id_consecutivo', '!=', 0) // Filtra aquellos con id_consecutivo diferente de 0
            ->get();
        
            $expedientes = DB::table('expedientes')
            ->get();

            return view('expedientes.reportes.reportesSuper2', [
            'elementos' => $elementosActualizados,
            'registros' => json_encode($registros),
            'listaDocumentos'=>$expedientes,
            'listaClientes'=>$listaClientes,
           ]);

        }

    }


    public function ejecutarUsuarioGV(Request $request)
    {
        $id_usuario = $request->input('id_usuario');
        $registros = [];

        $elementos = DB::table('actividad_guardavalores')
        ->where('id_usuario', $id_usuario)
        ->get();

        if ($elementos->isEmpty()) {
            $elementosActualizados = [];
        } else {
            $elementosActualizados = [];
    
            foreach ($elementos as $elemento) {
    
                    $Expediente = DB::table('guardavalores')
                    ->where('id_documento', $elemento->id_documento)
                    ->first();
                
                    $nombreExpediente = $Expediente->nombre;
                    $id_cli = $Expediente->id_cliente;

                    
                    $nombreUsuario = DB::table('users')
                    ->where('idUsuarioSistema', $elemento->id_usuario)
                    ->value('nombre');

                    $Cliente = DB::table('clientes_guardavalores')
                    ->where('id_cliente', $id_cli)
                    ->first();
            
    
                // Obtener los datos originales del elemento
                $elementoOriginal = (array) $elemento;

                if ($Cliente) {
                    $nombreCliente = $Cliente->nombre;
                    $elementoOriginal['tipo_gv'] = $nombreCliente;

                } else {
                echo "Cliente no encontrado.";
                }

                // Formatear las fechas en día, mes y año
                $elementoOriginal['fecha_actividad'] = date('d-m-Y', strtotime($elemento->fecha_actividad));
    
                // Actualizar los campos necesarios
                $elementoOriginal['estado'] = $nombreExpediente;
                //$elementoOriginal['id_usuario'] = $nombreExpediente;
    
                // Agregar el registro actualizado al arreglo
                $elementosActualizados[] = (object) $elementoOriginal;
            }
    
            // Configurar los registros con los elementos actualizados
            $registros = $elementosActualizados;
           }

           $listaClientes = DB::table('users')
           ->get();

           return view('guardavalores.reportes.ReportesUsuarioGV', [
            'elementos' => $elementosActualizados,
            'registros' => json_encode($registros),
            'listaUsuarios' => $listaClientes,
           ]);

    }
    

    public function ejecutarExpedienteUsuarioSU(Request $request)
    {
        $id_usuario = $request->input('id_usuario');
        $id_usuario = DB::table('users')
        ->where('idUsuarioSistema', $id_usuario) // USUARIO QUE SOLICITA
        ->select(DB::raw('CONCAT(nombre, " ", apellidos) as nombreCompleto'))
        ->value('nombreCompleto');

        $registros = [];
        $elementos = DB::table('actividad_expedientes')
        ->where('id_usuario_realiza', $id_usuario)
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
                    
                if (empty($elemento->fecha_entrega)) {
                    $elementoOriginal['fecha_entrega'] = null;
                } else {
                    $elementoOriginal['fecha_entrega'] = date('d-m-Y', strtotime($elemento->fecha_entrega));
                }
                
                if (empty($elemento->fecha_solicitud)) {
                    $elementoOriginal['fecha_solicitud'] = null;
                } else {
                    $elementoOriginal['fecha_solicitud'] = date('d-m-Y', strtotime($elemento->fecha_solicitud));
                }
                
                if (empty($elemento->fecha_devolucion)) {
                    $elementoOriginal['fecha_devolucion'] = null;
                } else {
                    $elementoOriginal['fecha_devolucion'] = date('d-m-Y', strtotime($elemento->fecha_devolucion));
                }
    
                // Actualizar los campos necesarios
                //$elementoOriginal['id_expediente'] = $nombreExpediente;
                $elementoOriginal['id_usuario_solicita'] = $nombreExpediente;
    
                // Agregar el registro actualizado al arreglo
                $elementosActualizados[] = (object) $elementoOriginal;
            }
    
            // Configurar los registros con los elementos actualizados
            $registros = $elementosActualizados;
           }

           $listaClientes = DB::table('users')
           ->get();

           return view('expedientes.reportes.homeReportes3', [
            'elementos' => $elementosActualizados,
            'registros' => json_encode($registros),
            'listaClientes' => $listaClientes,
           ]);

    }

    public function ejecutarMovGV(Request $request)
    {
        $fecha_inicio = $request->input('fecha_inicio');
        $fecha_fin = $request->input('fecha_fin');
        $movimiento = $request->input('filtro');
        $registros = [];

        $elementos = DB::table('actividad_guardavalores')
        ->whereBetween('fecha_actividad', [$fecha_inicio, $fecha_fin]);

if ($movimiento == "otros") {
    $elementos->where('movimiento', '!=', 'Ingreso')
              ->where('movimiento', '!=', 'Retiro');
} else {
    $elementos->where('movimiento', $movimiento);
}

$elementos = $elementos->get();

        // Verifica si la consulta no devolvió resultados
        if ($elementos->isEmpty()) {
            $elementosActualizados = [];
        } else {
            $elementosActualizados = [];
    
            foreach ($elementos as $elemento) {

                   $Expediente = DB::table('guardavalores')
                    ->where('id_documento', $elemento->id_documento)
                    ->first();
                
                    $nombreExpediente = $Expediente->nombre;
                    $id_cli = $Expediente->id_cliente;

                    $nombreUsuario = DB::table('users')
                    ->where('idUsuarioSistema', $elemento->id_usuario)
                    ->value('nombre');

                    $Cliente = DB::table('clientes_guardavalores')
                    ->where('id_cliente', $id_cli)
                    ->first();
            
    
                // Obtener los datos originales del elemento
                $elementoOriginal = (array) $elemento;

                if ($Cliente) {
                    $nombreCliente = $Cliente->nombre;
                    $elementoOriginal['tipo_gv'] = $nombreCliente;

                } else {
                    // Si no se encuentra el cliente, puedes manejar esta situación, por ejemplo, mostrando un mensaje de error o tomando alguna otra acción.
                    echo "Cliente no encontrado.";

                }
                
    
                // Formatear las fechas en día, mes y año
                $elementoOriginal['fecha_actividad'] = date('d-m-Y', strtotime($elemento->fecha_actividad));
    
                // Actualizar los campos necesarios
                $elementoOriginal['estado'] = $nombreExpediente;
                $elementoOriginal['id_usuario'] = $nombreUsuario;
    
                // Agregar el registro actualizado al arreglo
                $elementosActualizados[] = (object) $elementoOriginal;
            }
    
            // Configurar los registros con los elementos actualizados
            $registros = $elementosActualizados;
           }
    
            return view('guardavalores.reportes.reporteMovOpciones', [
            'elementos' => $elementosActualizados,
            'registros' => json_encode($registros),
           ]);

    }

    
    public function exportarMovGV(Request $request)
    {
        $elementos = $request->input('elementos');

        $archivo = public_path('exports/reporteMov_formatoGV.xlsx');
        $spreadsheet = IOFactory::load($archivo);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Otros Movimeintos');        

        // Llenar el archivo Excel con los datos del arreglo
        $row = 7;
        foreach ($elementos as $elemento) {
            $motivo = isset($elemento['motivo']) ? $elemento['motivo'] : 'Sin motivo';

            $sheet->setCellValue('B' . $row, $elemento['tipo_gv']);
            $sheet->setCellValue('C' . $row, $elemento['estado']);
            $sheet->setCellValue('D' . $row, $elemento['id_usuario']);
            $sheet->setCellValue('E' . $row, $motivo);
            $sheet->setCellValue('F' . $row, $elemento['fecha_actividad']);
            $sheet->setCellValue('G' . $row, $elemento['movimiento']);
            $row++;
        }

        // Crear el archivo Excel
        $writer = new Xlsx($spreadsheet);

        // Configurar las cabeceras para la descarga
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="MovimientosGuardavalores.xlsx"');
        header('Cache-Control: max-age=0');

        // Enviar el archivo al cliente
        $writer->save('php://output');
    }


    public function ejecutarExpedienteGeneralGV(Request $request)
    {
        $fecha_inicio = $request->input('fecha_inicio');
        $fecha_fin = $request->input('fecha_fin');
        $registros = [];
        
    $elementos = DB::table('actividad_guardavalores')
    ->whereBetween('fecha_actividad', [$fecha_inicio, $fecha_fin])
    ->get();

        // Verifica si la consulta no devolvió resultados
        if ($elementos->isEmpty()) {
            $elementosActualizados = [];
        } else {
            $elementosActualizados = [];
    
            foreach ($elementos as $elemento) {
                $nombreExpediente = DB::table('guardavalores')
                    ->where('id_documento', $elemento->id_documento)
                    ->value('nombre');
    
                $nombreUsuario = DB::table('users')
                    ->where('idUsuarioSistema', $elemento->id_usuario)
                    ->value('nombre');

if (is_null($elemento->usuario_solicita)) {
    $elemento->usuario_solicita = 0;
}

$nombreUsuariolleva = DB::table('users')
    ->where('idUsuarioSistema', $elemento->usuario_solicita)
    ->value('nombre');

if (is_null($nombreUsuariolleva)) {
    $nombreUsuariolleva = $nombreUsuario;
}
    
                // Obtener los datos originales del elemento
                $elementoOriginal = (array) $elemento;
    
                // Formatear las fechas en día, mes y año
                $elementoOriginal['fecha_actividad'] = date('d-m-Y', strtotime($elemento->fecha_actividad));
    
                // Actualizar los campos necesarios
                $elementoOriginal['estado'] = $nombreExpediente;
                $elementoOriginal['id_usuario'] = $nombreUsuario;
                $elementoOriginal['usuario_solicita'] = $nombreUsuariolleva;
    
                // Agregar el registro actualizado al arreglo
                $elementosActualizados[] = (object) $elementoOriginal;
            }
    
            // Configurar los registros con los elementos actualizados
            $registros = $elementosActualizados;
           }
    
            return view('guardavalores.reportes.homeReportesBasico', [
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
    ->where(function ($query) use ($fecha_inicio, $fecha_fin) {
        $query->orWhereBetween('fecha_solicitud', [$fecha_inicio, $fecha_fin])
            ->orWhereBetween('fecha_actividad', [$fecha_inicio, $fecha_fin]);
    })
    ->get();


    // Verifica si la consulta no devolvió resultados
    if ($elementos->isEmpty()) {
        $elementosActualizados = [];
    } else {
        $elementosActualizados = [];

        foreach ($elementos as $elemento) {

            $exp = DB::table('expedientes')
            ->where('id_expediente', $elemento->id_expediente)
            ->first();
                
            $nombreExpediente  = $exp->nombre;
            $id_cli = $exp->id_cliente;
                    

            $nombreUsuario = DB::table('users')
                ->where('idUsuarioSistema', $elemento->id_usuario_solicita)
                ->value('nombre');

                $Cliente = DB::table('clientes_expedientes')
                    ->where('id_consecutivo', $id_cli)
                    ->first();
            

            // Obtener los datos originales del elemento
            $elementoOriginal = (array) $elemento;

            if (empty($elemento->fecha_entrega)) {
                $elementoOriginal['fecha_entrega'] = null;
            } else {
                $elementoOriginal['fecha_entrega'] = date('d-m-Y', strtotime($elemento->fecha_entrega));
            }
            
            if (empty($elemento->fecha_solicitud)) {
                $elementoOriginal['fecha_solicitud'] = null;
            } else {
                $elementoOriginal['fecha_solicitud'] = date('d-m-Y', strtotime($elemento->fecha_solicitud));
            }
            
            if (empty($elemento->fecha_devolucion)) {
                $elementoOriginal['fecha_devolucion'] = null;
            } else {
                $elementoOriginal['fecha_devolucion'] = date('d-m-Y', strtotime($elemento->fecha_devolucion));
            }


            // Actualizar los campos necesarios
            $elementoOriginal['id_usuario_otorga'] = $nombreExpediente;
            $elementoOriginal['id_usuario_solicita'] = $nombreUsuario;

            if ($Cliente) {
                $nombreCliente = $Cliente->nombre;
                $elementoOriginal['tomo'] = $nombreCliente;

            } else {
                $elementoOriginal['tomo'] = 'NE';
            }

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
        $listaDocumentos = DB::table('expedientes')->get();
        $listaClientes = DB::table('clientes_expedientes')->get();

        return view('expedientes.reportes.reportesSuper2',['elementos' => [],'listaDocumentos'=>$listaDocumentos,'listaClientes' => $listaClientes]);
    }

    public function homeUsuarioSuper() {
        $listaClientes = DB::table('users')
        ->get();
        return view('expedientes.reportes.homeReportes3',['elementos' => [],'listaClientes' => $listaClientes]);
    }

    public function homeAtrasosSuper() {
        return view('expedientes.reportes.homeReportes4',['elementos' => []]);
    }

    public function homeReportesBasico($id_u) {
        return view('expedientes.reportes.homeReportesBasico',['elementos' => [], 'id_usuario'=>$id_u]);
    }

    public function homeReportesGV() {
        $user = DB::table('users')
        ->where('idUsuarioSistema', auth()->id())->first();
        $id_u = $user->id;
        return view('guardavalores.reportes.homeReportesBasico',['elementos' => [], 'id_usuario'=>$id_u]);
    }

    public function ReportesDocumentoGV() {
        $clientes = DB::table('clientes_guardavalores')->get();
        $gv = DB::table('guardavalores')->get();
        $actividad = DB::table('actividad_guardavalores')->get();

        return view('guardavalores.reportes.reporteDocumento',
        [
            'clientes' => $clientes,
            'elementos' => [],
            'actividad' => $actividad, 
            'listaDocumentos' => $gv, 
       
        ]);
    }
    
    public function ejecutarDocumentoGV(Request $request) {

        $gv = $request->input('gv');
        $id_cliente = $request->input('id_consecutivo');
        $movimiento = $request->input('movimiento');

        if ($gv) {
            echo "GV es: ".$gv;

            $gvs = DB::table('guardavalores')
            ->get();

            $clientes = DB::table('clientes_guardavalores')->get();

            $elementos = DB::table('actividad_guardavalores')
            ->where('id_documento', $gv)
            //->where('movimiento',$movimiento)
            ->get();

            $registros = [];

            if (!$elementos->isEmpty()) {
                foreach ($elementos as $elemento) {
                    $usuario = DB::table('users')
                        ->where('idUsuarioSistema', $elemento->id_usuario)
                        ->first();
        
                    $nombreUsuario = $usuario->nombre . ' ' . $usuario->apellidos;
        
                    // Actualiza los campos necesarios
                    $elemento->fecha_actividad = date('d-m-Y', strtotime($elemento->fecha_actividad));
                    $elemento->estado = $nombreUsuario;
        
                    $registros[] = $elemento;
                }
            }
                
            return view('guardavalores.reportes.reporteDocumento', [
                'elementos' => $registros,
                'clientes' => $clientes,
                'dcs' => [],
            ]);

        }elseif ($id_cliente and $gv==null) {

            $gvs = DB::table('guardavalores')
            ->where('id_cliente',$id_cliente)
            ->get();

            $registros= [];

            $clientes = DB::table('clientes_guardavalores')->get();

            return view('guardavalores.reportes.reporteDocumento', [
                'elementos' => $registros,
                'clientes' => $clientes,
                'listaDocumentos' => $gvs,
                'dcs' => $gvs,
            ]);

        }else {

            echo 'ultimo else, cliente: '. $id_cliente;
            $gvs = DB::table('guardavalores')
            ->get();

            $registros= [];

            $clientes = DB::table('clientes_guardavalores')->get();

            return view('guardavalores.reportes.reporteDocumento', [
                'elementos' => $registros,
                'clientes' => $clientes,
                'listaDocumentos' => $gvs,
                'dcs' => $gvs,
            ]);

        }

    }
    


    public function ReportesUsuarioGV() {
        $gv = DB::table('users')->get();
        return view('guardavalores.reportes.reportesUsuarioGV',['elementos' => [], 'listaUsuarios' => $gv]);
    }

    public function ReportesMovGV() {
        return view('guardavalores.reportes.reporteMovOpciones',['elementos' => []]);
    }

    public function __construct(){
        $this->middleware('auth');
    }


}
