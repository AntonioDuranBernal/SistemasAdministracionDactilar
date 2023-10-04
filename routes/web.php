<?php

use App\Http\Controllers\expedientes\ClientesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\expedientes\ExpedientesSuperController;
use App\Http\Controllers\expedientes\UsuariosSuperController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\reportesController;
use App\Http\Controllers\guardavalores\GuardavaloresController;


Route::get('/ReporteGeneralSU',[reportesController::class, 'homeReportesSuper'])->name('homeReportesUno');
Route::get('/ReporteDocumentoSU',[reportesController::class, 'homeDocumentoSuper'])->name('homeReportesDos');
Route::get('/ReporteUsuarioSU',[reportesController::class, 'homeUsuarioSuper'])->name('homeReportesTres');
Route::get('/ReporteAtrasosSU',[reportesController::class, 'homeAtrasosSuper'])->name('homeReportesCuatro');

Route::post('/ExpedienteGeneralSU', [reportesController::class, 'ejecutarExpedienteGeneralSU'])->name('ejecutarExpedienteGeneralSU');
Route::post('/ExpedienteDocumentoSU', [reportesController::class, 'ejecutarExpedienteDocumentoSU'])->name('ejecutarExpedienteDocumentoSU');
Route::post('/ExpedienteUsuarioSU', [reportesController::class, 'ejecutarExpedienteUsuarioSU'])->name('ejecutarExpedienteUsuarioSU');
Route::post('/ExpedienteAtrasosSU', [reportesController::class, 'ejecutarExpedienteAtrasosSU'])->name('ejecutarExpedienteAtrasosSU');

Route::get('/exportar-expedientes', [reportesController::class, 'exportarExpedientes'])->name('exportarExpedientes');
Route::get('/exportarExpedientesR2', [reportesController::class, 'exportarExpedientesR2'])->name('exportarExpedientesR2');
Route::get('/exportarExpedientesR3', [reportesController::class, 'exportarExpedientesR3'])->name('exportarExpedientesR3');
Route::get('/exportarExpedientesR4', [reportesController::class, 'exportarExpedientesR4'])->name('exportarExpedientesR4');

//LOGIN LOGOUT
Route::get('/Ingreso',[LoginController::class, 'showLoginForm'])->name('login');
Route::post('/Registro',[LoginController::class, 'attemptLogin'])->name('autenticar');
Route::get('/Opciones/{Permisos}',[LoginController::class, 'opciones'])->name('opciones');

//HOME INICIAL
Route::view('/', 'welcome')->name('home');


Route::view('/Expedientes/Configuracion', 'expedientes.configuracion.homeConfiguracionSuper')->name('homeConfiguracionSuper');


//HOME USUARIO BASICO EXP
Route::get('/InicioExpedientes/{idUser}',[UsuariosController::class, 'actividadInicio'])->name('expedientesBasico');
//CLIENTES BASICO EXP
Route::get('/ClientesEUB/{idUser}',[UsuariosController::class, 'clientesUsuarioBasico'])->name('clientesBasico');


//USUARIOS OPCIONES EXPEDIENTES
//INICIO
Route::get('/Expedientes',[ExpedientesSuperController::class, 'inicioExpedientes'])->name('homeAdminExpedientes');


//HOME USUARIO BASICO GV
Route::get('/InicioGuardavalores/{idUser}',[UsuariosController::class, 'actividadInicioGV'])->name('expedientesGV');

//GUARDAVALORES
//HOME INICIO ADMIN
Route::get('/Guardavalores',[GuardavaloresController::class, 'homeAdminGuardavalores'])->name('homeAdminGuardavalores');
Route::get('/AsignadosAClientesGV/{id_cliente}',[ClientesController::class, 'asignadosGV'])->name('cliente.asignadosGV');
Route::get('/GVSeleccion/{id_cliente}',[ClientesController::class, 'clienteNuevoGV'])->name('clienteNuevoGV');
Route::get('/GVCrear/{cliente}',[GuardavaloresController::class, 'documentoGV'])->name('crearDocumentoValor');
Route::post('/GuardandoDV',[GuardavaloresController::class, 'storeDV'])->name('DVGuardar');
Route::get('/ExpedientesHome',[GuardavaloresController::class, 'homeGV'])->name('homeGV');
Route::post('/BuscarGVD',[GuardavaloresController::class, 'buscarGV'])->name('guardavalores.search');

Route::get('/ContratoCrear/{cliente}',[GuardavaloresController::class, 'contratoGV'])->name('crearContrato');
Route::get('/PagareCrear/{cliente}',[GuardavaloresController::class, 'pagareGV'])->name('crearPagare');
Route::post('/GuardandoPagare',[GuardavaloresController::class, 'storePagare'])->name('pagareGuardar');

Route::post('/GuardandoContrato',[GuardavaloresController::class, 'storeContrato'])->name('contratoGuardar');
Route::get('/consultarGV/{id_C}',[GuardavaloresController::class, 'consultarGV'])->name('consultarGV');
Route::post('/retirarGV/{id_documento}',[GuardavaloresController::class, 'retirarGV'])->name('retirarGV');

Route::post('/almacenarActividadGV',[GuardavaloresController::class, 'almacenarActividadGV'])->name('almacenarActividadGV');







//CLIENTES
//HOME
Route::get('/Guardavalores/Clientes',[ClientesController::class, 'inicioClientesGV'])->name('homeClientesGV');
//BUSCAR
Route::post('/ClienteBuscarGV',[ClientesController::class, 'searchGV'])->name('clientesGV.search');
//PARA CREAR
Route::get('/nuevoClienteGV',[ClientesController::class, 'nuevoGV'])->name('cliente.nuevoGV');
//PARA GUARDAR
Route::post('/GuardandoClienteGV',[ClientesController::class, 'storeGV'])->name('cliente.storeGV');


//CLIENTES
//HOME
Route::get('/Expedientes/Clientes',[ClientesController::class, 'inicioClientes'])->name('homeClientesSuper');
//PARA CREAR
Route::get('/nuevoCliente',[ClientesController::class, 'nuevo'])->name('cliente.nuevo');
//BUSCAR
Route::post('/ClienteBuscar',[ClientesController::class, 'search'])->name('clientes.search');
//DETALLES CLIENTE
Route::get('/Detalles/{id_cliente}',[ClientesController::class, 'clienteDetalles'])->name('clienteDetalles');
//CLIENTE NUEVO EXPEDIENTE ASIGNADO
Route::get('/ExpedientePara/{id_cliente}',[ClientesController::class, 'clienteNuevoExpediente'])->name('clienteNuevoExpediente');
//GUARDAR DATOS EXPEDIENTE ASIGNADO
Route::post('/ExpedientesActualizados',[ClientesController::class, 'storeExpediente'])->name('expedienteGuardar');
//PARA GUARDAR
Route::post('/GuardandoCliente',[ClientesController::class, 'store'])->name('cliente.store');
//BUSCAR ASIGNADOS
//Route::post('/AsignadosAClientes',[ClientesController::class, 'asignados'])->name('cliente.asignados');
Route::get('/AsignadosAClientes/{id_cliente}',[ClientesController::class, 'asignados'])->name('cliente.asignados');

//CLIENTES VISTAS USUARIO BASICO Y ADMIN
Route::get('/Clientes/{usuario}',[ClientesController::class, 'inicioClientesUsuarioX'])->name('homeClientesUsuario');
Route::post('/ClienteBuscarBasico',[ClientesController::class, 'searchBasico'])->name('clientesUsuario.search');
//PARA CREAR
Route::get('/nuevoCliente/{id_usuario}',[ClientesController::class, 'nuevoClienteBasico'])->name('cliente.nuevoBasico');
//PARA GUARDAR
Route::post('/GuardandoClienteUB',[ClientesController::class, 'storeUsuarioClienteBasico'])->name('storeUsuarioClienteBasico');
//CLIENTE ASIGNADOS
//Route::get('/AsignadosClientesUB/{id_cliente,id_usuario}',[ClientesController::class, 'asignadosUBasico'])->name('cliente.asignadosBasico');
Route::get('/AsignadosClientesUB/{id_cliente}/{id_usuario}', [ClientesController::class, 'asignadosUBasico'])->name('cliente.asignadosBasico');
//VER DETALLES EXPEDIENTE BASICO USUARIO
Route::get('/ExpedienteDetallesUB/{id_expediente}/{id_user}',[ExpedientesSuperController::class, 'detallesExpedienteBasico'])->name('detallesExpedienteBasicoUser');
//SOLICITAR
Route::match(['get', 'post'], '/ExpedienteSolicitarUB/{id_expediente}/{id_usuario}', [ExpedientesSuperController::class, 'solicitarExpUB'])->name('solicitarExpedienteUBasico');
//GUARDAR PRESTAMO USUARIO BASICO
Route::post('/almacenarActividadUsuarioBasico', [ExpedientesSuperController::class, 'almacenarActividadUsuarioBasico'])->name('almacenarActividadUsuarioBasico');

//BORRAR EXPEDIENTE
Route::get('/expedientes/eliminar/{id}', [ExpedientesSuperController::class, 'eliminarExpediente'])->name('borrarExpediente');


//ENTREGAR EXPEDIENTE
Route::get('/devolverExpediente/{id_e}/{id_u}/{id_a}',[ExpedientesSuperController::class, 'devolverExpediente'])->name('devolverExpediente');


//REPORTES BASICO
Route::get('/ReporteGeneralBasico/{id_u}',[reportesController::class, 'homeReportesBasico'])->name('homeReportesBasico');




//HOME EXPEDIENTES 
Route::get('/Expedientes/homeExpedientesUB/{usuario}',[ExpedientesSuperController::class, 'homeExpedientesUB'])->name('homeExpedientesUB');


//HOME EXPEDIENTES 
Route::get('/Expedientes/Listado',[ExpedientesSuperController::class, 'listadoExpedientes'])->name('homeExpedientes');
//BUSCAR EXPEDIENTE
Route::post('/ExpedienteBuscar',[ExpedientesSuperController::class, 'search'])->name('expedientes.search');
//VER DETALLES EXPEDIENTE
Route::get('/ExpedienteDetalles/{id_expediente}',[ExpedientesSuperController::class, 'detallesExpediente'])->name('detallesExpediente');
//SOLICITAR
Route::match(['get', 'post'], '/ExpedienteSolicitar/{id_expediente}', [ExpedientesSuperController::class, 'solicitar'])->name('solicitarExpediente');
//GUARDAR PRESTAMO
Route::post('/almacenar-actividad', [ExpedientesSuperController::class, 'almacenarActividad'])->name('almacenarActividad');

//HOME USUARIOS
Route::get('/Usuarios/Listado',[UsuariosSuperController::class, 'listadoUsuarios'])->name('homeUsuarios');
//PARA CREAR
Route::get('/nuevo',[UsuariosSuperController::class, 'nuevo'])->name('usuario.nuevo');
//PARA GUARDAR
Route::post('/GuardandoUsuario',[UsuariosSuperController::class, 'store'])->name('usuario.store');
//BUSCAR USUARIO
Route::post('/UsuarioBuscar',[UsuariosSuperController::class, 'search'])->name('usuario.search');
//VER DETALLES USUARIO
Route::get('/UsuarioDetalles/{id_usuario}',[UsuariosSuperController::class, 'detallesUsuario'])->name('detallesUsuario');
//EDITAR
Route::get('/UsuarioEditar/{id_usuario}',[UsuariosSuperController::class, 'borrar'])->name('borrarUsuario');
