<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Conciliacion;
use App\ConciliacionEstado;
use App\Expediente;
use App\Mail\Firma;
use App\Mail\RegConciliacionSuccess;
use App\Notifications\NotificarDirector;
use App\Notifications\SolicitudRadicarConciliacion;
use App\Periodo;
use App\Segmento;
use App\TablaReferencia;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('webservice','WebServicesController@index');

Route::post('webservice','WebServicesController@index');
Route::get('pruebasocket','WebServicesController@pruebaSocket');
Route::get('autorizacion', 'AutorizacionesController@verificar');
Route::post('autorizacion/verificar', 'AutorizacionesController@verificarPdf');

//se usa de manera general para emitir mensajes desde javascript a los sockets
Route::post('msg/socketjs', 'MsgSocketOriginJsController@postSend');

Route::resource('logout', 'LogoutController');

Route::get('terminosycondiciones', function () {
  return view('auth.terminosycondiciones');
});
Route::get('conciliaciones/download/file/{file_id}', 'ConciliacionesController@downloadFile'); 
Route::post('conciliaciones/enviar/correo', 'ConciliacionesController@enviarCorreo'); 
Route::get('conciliaciones/get/comentarios', 'ConciliacionesController@getComentarios'); 



Route::get('videos', function () {
  return view('videos');
});

Route::get('audiencia/{code}','AudienciaController@ExternoSalaAudiencia');
Route::post('audiencia/{code}','AudienciaController@ExternoSalaAudiencia');
Route::get('audiencia/salaalaterna/{code}','AudienciaController@getSalaAlternaAudciencia');
Route::get('/firmar/digital/{token}', 'ConciliacionesFirmasController@firmaVerify');
Route::get('/firmar/pdf/verify/{token}', 'ConciliacionesFirmasController@showFormVerifyDocument');
Route::get('/firmar/digital/confirm/{token}/{codigo}', 'ConciliacionesFirmasController@firmaConfirm');
Route::post('/firmar/pdf/verify', 'ConciliacionesFirmasController@storeReportDescargado')->name("store.rpdescargado");
Route::get('/firmar/pdf/verify/show/{token}', 'ConciliacionesFirmasController@showVerifyDocument')->name("show.documents");
Route::post('/firmar/digital/', 'ConciliacionesFirmasController@tokenVerify')->name('firmar.verify');
Route::post('/firmar/ok', 'ConciliacionesFirmasController@firmaAccept')->name('firma.ok');
Route::get('/firmar/digital/show/doc', 'ConciliacionesFirmasController@showFirmaAccept')->name('firmar.accept');
Route::get('/firmar/get/status', 'ConciliacionesFirmasController@getStatus');
Route::get('/firmar/digital/revocar/{token}/{codigo}', 'ConciliacionesFirmasController@firmaRevocar');
Route::post('/firmar/revocar/ok', 'ConciliacionesFirmasController@firmaRevocarOk');
Route::get('/firmar/revocar/get/status', 'ConciliacionesFirmasController@getFirmaRevocar');


///rutas que requieren atenticación
Route::group(['middleware' => ['auth']], function() {
//Nuevo usuarios
Route::resource('usuarios', 'UsersController');
Route::get("usuarios/buscar/persona","UsersController@findUserWithFilter");
Route::get("usuarios/get/by/idnumber","UsersController@getUsersByIdNumber");
Route::get("usuarios/find/by/name","UsersController@findUserByNameOrLastNameAndRole");
Route::get("usuarios/find/by/role","UsersController@getUsersByRoleName");
Route::post("usuarios/add/sede","UsersController@addSede");
Route::post("usuarios/update/profile/picture","UsersController@uploadProfilePicture");

Route::post('mail', 'MailController@store')->name('mail.store');


//Citaciones estudiante
Route::resource('citaciones/estudiante', 'CitacionEstudiantesController');
Route::post('/citaciones/search/forday', 'CitacionEstudiantesController@searchCitasForDay');



Route::resource('notifications', 'NotificationsController');
Route::get('/admin/users/view/notifications','NotificationsController@index');
Route::put('/admin/users/mark/read','NotificationsController@markAsRead');

Route::get('dashboard/search', 'HomeController@search');

Route::resource('users', 'MyusersController');
Route::get('users/confirm/email/{token}', 'MyusersController@confirm_email');
Route::get('users/find/us', 'MyusersController@findUserWithFilter');
Route::post('users/store', 'MyusersController@userStore');

Route::group(['middleware' => ['confirm_email','perfil']], function() {

Route::get('pruebas/mail',function(){

  $colorArray = ['#FF6633', '#FFB399', '#FF33FF', '#FFFF99', '#00B3E6', 
  '#E6B333', '#3366E6', '#999966', '#99FF99', '#B34D4D',
  '#80B300', '#809900', '#E6B3B3', '#6680B3', '#66991A', 
  '#FF99E6', '#CCFF1A', '#FF1A66', '#E6331A', '#33FFCC',
  '#66994D', '#B366CC', '#4D8000', '#B33300', '#CC80CC', 
  '#66664D', '#991AFF', '#E666FF', '#4DB3FF', '#1AB399',
  '#E666B3', '#33991A', '#CC9999', '#B3B31A', '#00E680', 
  '#4D8066', '#809980', '#E6FF80', '#1AFF33', '#999933',
  '#FF3380', '#CCCC00', '#66E64D', '#4D80CC', '#9900B3', 
  '#E64D66', '#4DB380', '#FF4D4D', '#99E6E6', '#6666FF'];

  $upadte = TablaReferencia::where(['categoria'=>'type_status',"tabla_ref"=>'conciliaciones'])->get();
  
  foreach ($upadte as $key => $ref) {
    $ref->color =  $colorArray[$key];
    $ref->save();
  }
 $upadte = TablaReferencia::where(['categoria'=>'type_status',"tabla_ref"=>'conciliaciones'])->get();
 
dd($upadte);
  return  $upadte ;

  $conciliacion = Conciliacion::find(17);
  $user = $conciliacion->getUser(205);
  Mail::to("darioj99@gmail.com")->send(new RegConciliacionSuccess($user));

  $mensaje = "Se ha registrado con exito";
  $url = 'https://iurisapp.udenar.edu.co/solicitudes/recepcion/conciliacion/$2y$10$IU16lRViqmYzIrQPUEBkieqXbdS.ecmPbFanSgHZPB6j6UpnuLiPm?id=17&paso=2';
  return view('myforms.mails.formato_correo_',compact('mensaje','url'));
});

Route::get('home',function(){
  return redirect('/dashboard');
});
/* Route::get('/dashboard', function () {
  if(auth()->user()->hasRole("solicitante")){
    return Redirect::to('oficina/solicitante');
  }
    return view('myforms.frm_bienvenida');
}); */
Route::get('/dashboard',"SedesController@selectSede");
Route::get('/change/sedes',"SedesController@changeSede");
//Route::get('/change/sedes/cambiar/{sede_id}',"SedesController@changeSede");//->name('change.sede');
 
//Rutas bibliotecas
Route::resource('bibliotecas', 'BibliotecaController'); 
Route::get('bibliotecas/inactivas/view', 'BibliotecaController@showBibliotecaOff')->name('biblioteca.inactivas');
Route::get('bibliotecas/pdf/{id}', 'BibliotecaController@bibliodowpdf')->name('biblioteca.pdf');
Route::get('bibliotecas/change/{id}', 'BibliotecaController@change')->name('biblioteca.change');
Route::post('bibliotecas/update', 'BibliotecaController@update')->name('biblioteca.update');


 
//Usuarios
Route::get('usuarios', function () {
    return view('myforms.frm_myusers');
});
Route::get('usuarios/index/page','MyusersController@index_page');
Route::get('students/', 'MyusersController@indexEst')->name('students.index');
Route::get('curso/empty', 'MyusersController@cursoEmpty')->name('curso.empty');
Route::post('students/get', 'MyusersController@getEstudiantes');
Route::post('docentes/get', 'MyusersController@getDocentes');
Route::post('solicitantes/get', 'MyusersController@getSolicitantes');
Route::get('users/get/{id}', 'MyusersController@getAllusers');

//rutas para el manejo de roles y permisos
Route::group(['prefix' => 'admin'], function() {
  Route::resource('/permisos','PermissionsController');
	Route::resource('/roles','RolesController');
	Route::get('/asig','RolesController@admin')->name('roles.admin');
	Route::post('/sync/permission','RolesController@syncPermissionRole');
	Route::post('/get/sync/permissions','RolesController@getPermissionsRole');
	Route::post('/permissions/change','RolesController@change_permissions'); 

});

Route::post('users/change/state','MyusersController@changeStateUser');

Route::get('users/destroy/{id}',[
    'uses'=>'MyusersController@destroy',
    'as'=>'users.destroy' 
]);

Route::get('turnos/docentes', 'TurnosDocentesController@index');
Route::get('turnos/docentes/{id}', 'TurnosDocentesController@store');
Route::get('turnos/docentes/reporte/asis', 'TurnosDocentesController@show');
Route::post('turnos/acdocentes', 'TurnosDocentesController@updateinfo');

//Graficas
Route::resource('graficas','GraficasController');
Route::post('graficas/search','GraficasController@search_data');   

//Asignaciones Estudiantes Docente
Route::resource('docentes/asigest','AsigDocentEstController');
Route::post('docentes/asigest/confirm','AsigDocentEstController@confAsigDoc');

//Asignaciones casos Docente
Route::resource('docentes/casos','AsigDocenteCasoController');
//Route::post('docentes/asigest/confirm','AsigDocenteCaso@confAsigDoc');

 //Horario docente
 Route::resource('docentes/horario','HorarioDocenteController'); 
 Route::post('docentes/search/horario','HorarioDocenteController@searchHorasDocente');   
 Route::post('docentes/horario/delete/all','HorarioDocenteController@deleteAllHorarioDocentes');
//Route::get('docentes/horario/search/estudiante','HorarioDocenteController@searchEstud');

//Turnos
Route::get('turnos/asistencia', 'TurnosController@reporasistencia');
Route::get('turnos/asistencia/detalles/{idnum}', 'TurnosController@reporAsistenciaDetalles');
Route::resource('turnos','TurnosController');
Route::delete('turnos/delete/all','TurnosController@deleteAllTurnos');
Route::get('turnos/descargar/curso','TurnosController@descargarTurnosExcel');

//Excel usuarios
Route::get('usuarios/importar', 'ExcelusuariosController@getImport');
Route::post('usuarios/importar/iniciar', 'ExcelusuariosController@postImport');

//Excel 
Route::resource('excel', 'ExcelController');
Route::post('excel/search', 'ExcelController@search_data');
Route::post('excel/download', 'ExcelController@generate_data');
Route::post('excel/search/options', 'ExcelController@search_options');
Route::get('excel/notas/download', 'ExcelController@notas_download'); 
Route::get('excel/exp/user/download/', 'ExcelController@descargarExpUser'); 

//Asignaciones
Route::resource('asignaciones', 'AsignacionesController');
Route::post('asignaciones/update/{id}', 'AsignacionesController@updateDocenteAsignado');

//Files
Route::get('file/download/{id}', 'FilesController@download')->name('file.download');  


//Expedientes
Route::resource('expedientes', 'ExpedienteController');
Route::get('expedientes/historial/{exp}/{tipo}', 'ExpedienteController@historialDatosCaso');
Route::get('expedientes/selectconest/{texcon}', 'ExpedienteController@selectest');
Route::post('expedientes/coordinador/update/{id}', 'ExpedienteController@update');
Route::get('expedientes/index/', 'ExpedienteController@index');
Route::get('expediactuacion/', 'ExpedienteController@listarActuaciones');
Route::post('expedientes/reasigcaso/', 'ExpedienteController@reasigcaso');
Route::post('expedientes/sustituircasos/', 'ExpedienteController@sustcasos')->name('expedientes.sustcasos');
Route::get('expediente/replacecaso/', 'ExpedienteController@replacecaso');
Route::post('expedientes/getestudiantes/', 'ExpedienteController@getEstudiantes');
Route::get('expediente/casos/reasignados', 'ExpedienteController@casosreasig');
Route::post('expedientes/anteriorestudiante/', 'ExpedienteController@anteriorEstudiante');
Route::post('expedientes/buscarexpasig/', 'ExpedienteController@searchExpAsig'); 
Route::post('expedientes/dar/baja', 'ExpedienteController@darBaja'); 
Route::post('expedientes/cambiar/docente', 'ExpedienteController@cambiarDocente'); 
Route::post('expedientes/store/proceso/judicial', 'ExpedienteController@storeProcJudicial'); 
Route::post('expedientes/cambiar/proceso/judicial', 'ExpedienteController@cambiarProcesoJuridico'); 
Route::get('expedientes/proceso/judicial/{id}/edit', 'ExpedienteController@editExpProcJudicial'); 



Route::get('expediente/createstream/{id}', 'ExpedienteController@createStream'); 
Route::get('expediente/sharestream/{id}', 'ExpedienteController@shareStream');  

Route::post('expedientes/asignar/conciliacion', 'ExpedienteController@asigConciliacion'); 
 
//Ediar usuarios desde Expedientes
Route::resource('expuser', 'ExpedienteUserController');

//cierre de caso/expedientes
Route::resource('expcierrecaso', 'ExpedienteCierreController');

//estados caso
Route::resource('estados/caso', 'EstadosCasoController');
Route::post('/estado/search/', 'EstadosCasoController@search');
Route::post('/estado/cerrar/caso', 'EstadosCasoController@cerrarCaso');
Route::post('/estado/caso/volver/abrir', 'EstadosCasoController@abrir_caso'); 

//Defensas de Oficio
Route::resource('defensas/oficio', 'DefensaOficioController');

//Autorizaciones
Route::resource('autorizaciones', 'AutorizacionesController');
Route::get('autorizaciones/descargar/{id}', 'AutorizacionesController@descargarPdf');

//Oficinas
Route::resource('oficinas', 'OficinaController');
Route::get('oficinas/users', 'OficinaController@getUsers');
Route::get('oficinas/ver/{id}', 'OficinaController@ver');
Route::get('oficinas/user/delete', 'OficinaController@deleteUser');

//Notas ext
Route::resource('notasext', 'NotaExtController');
//Route::get('notasext/find', 'NotaExtController@find');

//Sedes
Route::resource('sedes', 'SedesController');


//Actuaciones
Route::get('listaractuaciones', 'ExpedienteController@listarActuaciones');

Route::resource('actuaciones', 'ActuacionController');
Route::post('/actuaciones/update/doc/{id}', 'ActuacionController@updoc');
Route::post('/actuaciones/store/revision', 'ActuacionController@storeRevision');
Route::post('/actuaciones/update/{id}', 'ActuacionController@update');
Route::post('/actuaciones/revisar/{id}', 'ActuacionController@revisiones');
Route::get('/actuaciones/search/previous', 'ActuacionController@get_act_ant');
Route::post('/actuaciones/set/notas', 'ActuacionController@set_notas');

Route::get('/actuaciones/create/pru', 'ActuacionController@create');

Route::get('actpdfdownload/{id}/{user_doc}' , 'ActuacionController@actpdfdownload');


//requerimientos
Route::resource('requerimientos', 'RequerimientoController');
Route::get('reqpdfgen/{id}',  'RequerimientoController@reqpdfgen');
Route::post('requerimientos/update/{id}',  'RequerimientoController@updateReq');




//notas // Calificaciones
Route::resource('notas', 'NotaController'); 
Route::post('/notas/update', 'NotaController@updateNota');
Route::get('/notas/ver/estudiante', 'NotaController@notas_ver');
Route::post('/notas/delete', 'NotaController@delete'); 
Route::post('/notas/search', 'NotaController@searchNotas');
//Route::get('/notas/search/get', 'NotaController@searchNotas');

//Asesorias
Route::resource('asesorias', 'AsesoriasDocenteController');
Route::post('asesorias/change/shared', 'AsesoriasDocenteController@changeShared');

//Segmentos
Route::resource('segmentos', 'SegmentosController');
Route::post('segmentos/change/state/{id}','SegmentosController@changeState');
Route::get('segmentos/change/fc','SegmentosController@change_state_segfc');
Route::get('segmentos/close/{id}','SegmentosController@closeSegmento');
//Periodos
Route::resource('periodos', 'PeriodosController');
Route::post('periodos/change/state/{id}','PeriodosController@changeState');
Route::post('periodos/buscar/segmentos/{id}','PeriodosController@searchSegmentos');

//Auditoria
Route::resource('auditoria', 'AuditoriaController');

//Documentos
Route::resource('documentos', 'CaseLogController');
Route::get('documentos/get', 'CaseLogController@getDocuments');
Route::post('documentos/{id}', 'CaseLogController@update');
Route::get('/descargar/documento/{id}','CaseLogController@downloadFileLog');

//Conciliaciones
Route::resource('conciliaciones', 'ConciliacionesController');
//Solicitudes
Route::post('conciliaciones/add/user', 'ConciliacionesController@addUser');

Route::post('conciliaciones/insert/data', 'ConciliacionesController@insertData');
Route::post('conciliaciones/generate/documents', 'ConciliacionesController@generateDocuments'); 
Route::post('conciliaciones/insert/estado', 'ConciliacionesController@insertEstado'); 
Route::post('conciliaciones/insert/comentario', 'ConciliacionesController@insertComentario'); 
Route::delete('conciliaciones/delete/comentario', 'ConciliacionesController@deleteComentario'); 
Route::get('conciliaciones/edit/comentario', 'ConciliacionesController@editComentario');
Route::post('conciliaciones/update/comentario', 'ConciliacionesController@updateComentario'); 
Route::post('conciliaciones/store/anexo', 'ConciliacionesController@storeAnexo');
Route::get('conciliaciones/delete/anexo', 'ConciliacionesController@deleteAnexo');
Route::post('conciliaciones/update/anexo', 'ConciliacionesController@updateAnexo');
//Route::get('conciliaciones/download/file/{file_id}', 'ConciliacionesController@downloadFile'); 
Route::get('conciliaciones/delete/estado', 'ConciliacionesController@deleteEstado');
Route::get('conciliaciones/edit/estado', 'ConciliacionesController@editEstado');
Route::get('audiencias', 'AudienciaController@calendarAudiencias');
Route::post('conciliaciones/update/estado', 'ConciliacionesController@updateEstado');
Route::get('conciliaciones/get/estado/pdf', 'ConciliacionesController@getEstadosReportesPdf');
Route::get('conciliacion/user/{idnumber}', 'ConciliacionesController@getUser');
Route::get('conciliacion/detalles/user/{idnumber}', 'ConciliacionesController@getDetallesUser');
Route::get('conciliacion/delete/user', 'ConciliacionesController@deleteUser');
Route::get('conciliaciones/get/status/files', 'ConciliacionesController@getEstadosFiles');
Route::post('conciliaciones/store/conc/shared/files', 'ConciliacionesController@storeSharedConcFiles');
Route::post('conciliaciones/asignar/expediente', 'ConciliacionesController@asigExpediente');
Route::get('conciliacion/sancionar/user', 'ConciliacionesController@sancionarUser');
Route::get('conciliacion/send/notification/mail', 'ConciliacionesController@enviarNotificacionesCorreo');

Route::post('conciliacion/audiencia/create', 'AudienciaController@audienciaCreate');
Route::get('conciliacion/users/salasalternasaudiencia/{id}/{cont}', 'AudienciaController@getSalasAudiencia');
Route::post('conciliacion/create/salasalternasaudiencia', 'AudienciaController@postSalasAudienciaCreate');
Route::get('conciliacion/numusers/salasalternasaudiencia/{id}', 'AudienciaController@getUsersSalasAudiencia');
Route::get('conciliacion/est/rol/{idconciliacion}', 'AudienciaController@getEstudianteRol');
Route::get('conciliacion/estados/rol', 'AudienciaController@getconciliacionRolList');
Route::post('conciliacion/update/est/rolconciliacion', 'AudienciaController@postConciliacionEstRolUpate');
Route::get('conciliacion/turnos/estudiantes/asig/{data}/{id}', 'AudienciaController@getConciliacionTurnosEst');
Route::get('conciliacion/chat/{chatroom}', 'AudienciaController@getChangeChatRoom');


//PDF >Reportes

Route::get('pdf/reportes/get', 'PdfReportesController@getReportes');
Route::get('pdf/reportes/by/category', 'PdfReportesController@getReportesByCategory');
Route::get('pdf/reportes/for/destinos', 'PdfReportesController@getDestinosForReport');

Route::get('pdf/reportes/generate/{conciliacion}/{reporte}/{estado}', 'PdfReportesController@loadPdf')->name('pdf.generate');
Route::post('pdf/reportes/preview', 'PdfReportesController@loadPdfPreview')->name('pdf.generate');
Route::post('pdf/reportes/asignar', 'PdfReportesController@asignarReporte');
Route::get('pdf/reportes/editar/asignacion', 'PdfReportesController@editAsignacionReporte');
Route::resource('pdf/reportes', 'PdfReportesController');
Route::post('pdf/reportes/{id}', 'PdfReportesController@update')->name('pdf.update');
//Conciliaciones >Reportes
Route::resource('conciliaciones/pdf', 'ConciliacionesReportesController');
Route::post('conciliaciones/pdf/{id}', 'ConciliacionesReportesController@update');
Route::post('conciliaciones/get/all/pdf', 'ConciliacionesReportesController@getAllPdf');
Route::get('conciliacion/reportes/get', 'ConciliacionesReportesController@getPdfReportesConciliacion');
Route::get('conciliacion/reportes/for/status', 'ConciliacionesReportesController@getPdfReportForStatus');

Route::get('pdf/reportes/editar/temporal/{reporte}/{conciliacion}/{estado}', 'ConciliacionesReportesController@editReporteTemporal');
Route::get('conciliacion/reporte/firmantes', 'ConciliacionesReportesController@getFirmantes');
Route::post('conciliacion/reporte/firmantes', 'ConciliacionesReportesController@setFirmantes');
Route::post('conciliacion/reporte/revocar/firmas', 'ConciliacionesReportesController@revocarFirmas');
Route::post('conciliacion/reporte/firmantes/reenviar/mails', 'ConciliacionesReportesController@reenviarMails');
Route::get('categorias/get/from/reports', 'ConciliacionesReportesController@getFromReports'); 
Route::post('/conciliacion/reporte/store/personalized/values', 'ConciliacionesReportesController@insertPersonalizedReportValues');
Route::post('/conciliacion/reporte/revock/firma', 'ConciliacionesReportesController@revockFirma');

//Conciliaciones
Route::resource('conciliaciones/hechos/pretenciones', 'ConcHechosPretencionesController');


//imagen perfil
Route::resource('thumbnail', 'ThumbnailController');


//configuración
Route::resource('config_roles', 'ConfigRoleController');


//////////////Calendario

//Calendario
Route::get('horarios/{id}', 'HorarioController@calendario')->name('horarios.index');
Route::resource('horarios', 'HorarioController');

//ReferencesData
Route::resource('categorias', 'ReferencesDataController');  
Route::post('categorias/store/from/reports', 'ReferencesDataController@storeFromReports');  
 
//ReferencesStaticData
Route::resource('categories', 'ReferencesStaticDataController'); 


//consulta calendario
Route::get('consultahor/{clbd}/{hrbd}/{datev}','HorarioController@consultach');
Route::get('consultahordoc/{clbd}/{hrbd}/{datev}','HorarioController@consultahordoc');
Route::get('consultahordocasis/{clbd}/{hrbd}/{datev}','HorarioController@consultahordocasis');
Route::post('horario/updatehordocasis','HorarioController@updatehordocasis');
Route::post('horario/regishordocasis','HorarioController@regishordocasis');



//prueba
Route::get('prueba/expedienteasig','ExpedienteController@pruebaasig');
Route::get('prueba/citas','CitacionEstudiantesController@citasAutomatic');
Route::get('prueba/citas/correo','CitacionEstudiantesController@listCorreoCitasGen');
Route::get('/mail/html', function () {
  $mensaje = getMessagesForPro(245,"aad2012"); 
  return view('myforms.mails.frm_notificaciones_procjudexp',compact('mensaje'));

});


});//fin middleware perfil
Route::group(['middleware' =>'front'], function() { 

Route::group(['prefix' =>'oficina'], function() { 
  Route::get('solicitante/conciliaciones','FrontController@conciliaciones')->name("front.conciliaciones");
  Route::get('solicitante/conciliaciones/solicitud','FrontController@conciliaciones_solicitud')->name("front.conciliaciones.solicitud");
  Route::get('solicitante/conciliaciones/{id}/edit','FrontController@conciliacion_edit')->name("front.conciliacion.edit");
  Route::get('solicitante/conciliaciones/create','FrontController@conciliacion_store')->name("front.conciliacion.store");
  Route::resource('solicitante','FrontController');

  Route::get('solicitante/solicitud/{id}','FrontController@solicitud_show');
  

});


//solicitudes
Route::post('solicitudes/store/documento','SolicitudesController@storeDocument');
Route::get('solicitudes/files/{id}/edit','SolicitudesController@editDocumento');
Route::post('solicitudes/update/documento','SolicitudesController@updateDocument');
Route::get('solicitudes/files/delete/{id}','SolicitudesController@deleteDocumento');



});//fin middleware front
Route::get('/', function () {
  return redirect('/dashboard');
});
//Referencias
Route::get('obtener/estados/expedientes','ReferencesController@getEstadosForExpediente');


});//fin middleware auth

Route::get('solicitudes/recepcion/conciliacion/{token}','SolicitudesController@recepcion_conciliacion');

Route::post('usuarios', 'UsersController@store');

Route::resource('solicitudes','SolicitudesController');
Route::get('solicitudes/view/{token}','SolicitudesController@waitRoom');
Route::post('solicitudes/user/register','SolicitudesController@userRegister');
Route::get('solicitudes/find/e','SolicitudesController@find');
Route::get('login',array('as'=>'login',function(){
    return view('myforms.login');
}));
Route::get('/solicitudes/conciliacion/registro', 'SolicitudesController@registro'); 

Route::get('/solicitudes/conciliacion/recepcion', 'SolicitudesController@solicitar');
Route::post('/solicitudes/conciliacion/recepcion', 'SolicitudesController@solicitar_store')->name("solicitudes.conciliacion"); 
Route::get('recepcion',"SolicitudesController@recepcion");
/* 
Route::get('recepcion',function(){
 
  return view('myforms.recepcion.frm_solicitud');
});  */

Route::get('pdf/reportes/generate/{conciliacion}/{reporte}/{estado}', 'PdfReportesController@loadPdf')->name('pdf.generate');










/*
//mantenimiento

Route::get('/', function () {
   return view('mantenimiento');
  //  return view('welcome');
});
Route::get('/login', function () {
   return view('mantenimiento');
  //  return view('welcome');
});


//fin mantenimiento//
*/


Auth::routes();

Route::post('/login', 'LoginController@store')->name('login'); 
Route::get('/login', function () {
        Auth::logout();
       // Session::flush();
        return view('myforms.login');
});
/* Route::get('/', function () {
  return redirect('/dashboard');
}); */

Route::get('/pruebaaj', 'ConciliacionesController@prueba');

Route::get('/prueba/filter/{id}', 'ExpedienteController@pruebaasig');

Route::get('/prueba', function () {
 $message = "Espero que te encuentres bien. <br><br>
 Hemos recibido tu solicitud de conciliación jurídica y queremos asegurarte que estamos aquí para ayudarte.
 Entendemos lo importante que es resolver este asunto de manera justa y equitativa, por lo que nos comprometemos a trabajar de cerca contigo para buscar una solución amigable y satisfactoria para todas las partes involucradas.<br>
 Nuestro equipo legal está preparado para guiarte a lo largo de todo el proceso de conciliación, brindándote el apoyo necesario y respondiendo todas tus preguntas o inquietudes.
 
 ";

  return view('myforms.mails.formato_correo',[
    'mensaje'=>$message,
    'url'=>url("/solicitudes/recepcion/conciliacion/123?id=10&paso=2"),
    'user_created'=>"Dario Narvaez"
]);

 /*  $user = User::where('idnumber',3030)->first();
  $request = ['cursando_id' => 115];
  $user->asignarTurno($request); */
/*   $expediente = Expediente::find(23282);
  $message = "<h3>Se ha creado un nuevo expediente!</h3>";

  $message .= "<h4>Número: ".$expediente->expid."<br>";
  $message .= "Rama del Derecho: ".$expediente->rama_derecho->ramadernombre."<br>";
 
  $message .= "Estudiante: ".$expediente->estudiante->name." ".$expediente->estudiante->lastname."<br>";
  $message .= "Docente: ".$expediente->getDocenteAsig()->name." ".$expediente->getDocenteAsig()->lastname."<br></h4>";
  $user = User::where("email",env('NOTIFICATION_DIR_EMAIL'))->first();
 // dd(env('NOTIFICATION_DIR_EMAIL'));
  Notification::send($user,new NotificarDirector($expediente, $message));
  return view('myforms.mails.formato_correo',[
    'mensaje'=>$message,
    'url'=>url('/expedientes/'.$expediente->expid.'/edit')
]); */

  $estu = DB::select("SELECT est.id, est.idnumber, concat(est.name,' ',est.lastname) as name,
   roles.name as role FROM `users` as est JOIN role_user on role_user.user_id = est.id 
   join roles on roles.id = role_user.role_id WHERE (roles.id = 6)
   and est.id > 1
   order by est.id asc limit 30");
 // dd($estu);
//

foreach ($estu as $key => $est) {
  $user = User::with('curso')->where('idnumber',$est->idnumber)->first();
  $tr = 117;//strval(rand(114, 117));
  $request = ['cursando_id' => $tr];
  $user->asignarTurno($request);
  $user->cursando_id = $tr;
 $user->save();
}
dd($estu);
  //$user->asignarTurno($request);
  $user->cursando_id = 114;
  //$user->save();
//  $h1 = strval(rand(114, 117));
 
/*   $dateString = date('Y-m-d');
  $date = DateTime::createFromFormat('Y-m-d', $dateString);

  if ( $date instanceof \DateTime) {
    dd( $date);
  }

  dd('no'); */

   return view('myforms.mails.recovery_password',[
    'token'=>"heols",
    'url'=>url('/conciliaciones/1/edit')
]);;
   $doceWithRama = DB::table('users')
            ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftjoin('roles', 'role_user.role_id', '=', 'roles.id')
            ->leftjoin('user_has_ramasderecho', 'user_has_ramasderecho.user_id', '=', 'users.id')
            ->leftjoin('rama_derecho', 'rama_derecho.id', '=', 'ramaderecho_id')
            ->leftjoin('sede_usuarios', 'sede_usuarios.user_id', '=', 'users.id')
            ->where('role_id', '4')
            ->where('rama_derecho.subrama', "SEGURIDAD SOCIAL")
            ->where('users.active', true)
            ->where('users.active_asignacion', true)
            ->where('sede_usuarios.sede_id', session('sede')->id_sede)
            ->select('users.name','users.id', 'users.idnumber','rama_derecho.subrama')
            ->orderBy('users.created_at', 'desc')->get();

            $segmento = Segmento::where('estado', true)
            ->join('sede_segmentos as sg', 'sg.segmento_id', '=', 'segmentos.id')
            ->where('sg.sede_id', session('sede')->id_sede)->first();

        $asig_doc = DB::select(
            DB::raw("SELECT `name`, `docidnumber`, COUNT(`docidnumber`) AS num_casos FROM `asignacion_docente_caso`
        JOIN asignacion_caso ON `asignacion_docente_caso`.asig_caso_id = asignacion_caso.id
        JOIN expedientes ON asignacion_caso.asigexp_id = expedientes.expid
        JOIN users ON `asignacion_docente_caso`.`docidnumber` = users.idnumber
        JOIN periodo ON asignacion_caso.periodo_id = periodo.id
        JOIN segmentos ON periodo.id = segmentos.perid
        JOIN sede_usuarios ON sede_usuarios.user_id = users.id
        WHERE expedientes.exptipoproce_id = '2' 
        AND sede_usuarios.sede_id = " . session('sede')->id_sede . "         
        AND users.active=1 AND users.active_asignacion=1 
        AND segmentos.id = $segmento->segmento_id 
        GROUP BY `docidnumber` ORDER BY num_casos ASC
         ")
        );

        //dd($doceWithRama); 
 // NotaExt::message(); 

        $arraydocentescompleto = [];
        $casoasignado = 0;
        foreach ($doceWithRama as $key1 => $docenterama) {
            $docexiste = 0;
            foreach ($asig_doc as $key2 => $docentecasos) {
                 //echo $docenterama->idnumber."=".$docentecasos->docidnumber."<br>";
                if ($docenterama->idnumber == $docentecasos->docidnumber) {
                    $docexiste = 1;
                    $arraydocentescompleto[$docenterama->idnumber] = $docentecasos->num_casos;
                }
            }

            if ($docexiste == 0) {
                $casoasignado = 1;
             //  dd($docenterama->idnumber);
                /* $asignacion = new AsigDocenteCaso();
                $asignacion->docidnumber = $docenterama->idnumber;
                $asignacion->asig_caso_id = $asignacion_caso->id;
                $asignacion->user_created_id = \Auth::user()->idnumber;
                $asignacion->user_updated_id = \Auth::user()->idnumber; */
               // $asignacion->save();
                $asignado = true;
                break;
            }
        }
        if ($casoasignado == 0) {
          
            asort($arraydocentescompleto);
            foreach ($arraydocentescompleto as $key => $numecasos) {
            //  dd($doceWithRama,$asig_doc, $key); 
               /*  $asignacion = new AsigDocenteCaso();
                $asignacion->docidnumber = $key;
                $asignacion->asig_caso_id = $asignacion_caso->id;
                $asignacion->user_created_id = \Auth::user()->idnumber;
                $asignacion->user_updated_id = \Auth::user()->idnumber; */
               // $asignacion->save();
                $asignado = true;
                break;
            }
        }

        dd($doceWithRama,$asig_doc); 
 // NotaExt::message(); 
  // dd(N
});
Route::get('/pruebas/users', 'UsersController@pruebas');
Route::get('/pruebas', function () {
  $periodo = Periodo::where('estado','1')
  ->first();
  $con_ul = Conciliacion::where('periodo_id',$periodo->id)
  ->where('num_conciliacion','<>','CCEAH-000-00-00')
  ->orderBy('created_at','desc')->first();            
  if($con_ul==null){
      $id_num ='001';
  }else{
      $id_num = intval(explode('-',$con_ul->num_conciliacion)[1]) + 1;
      //dd($id_num);

      if($id_num<10)  $id_num =  '00'.$id_num;
      if($id_num>10 and $id_num<100)  $id_num =  '0'.$id_num;
  }

 // $porciones = explode("-", $con_ul->num_conciliacion);

dd($id_num);

  $segmento = Segmento::where('estado', true)
            ->join('sede_segmentos as sg', 'sg.segmento_id', '=', 'segmentos.id')
            ->where('sg.sede_id', session('sede')->id_sede)
            ->first();


    
            
        $asig_doc = DB::select(
            DB::raw("SELECT `docidnumber`,`name`, COUNT(`docidnumber`) AS num_casos FROM `asignacion_docente_caso`
            JOIN asignacion_caso ON `asignacion_docente_caso`.asig_caso_id = asignacion_caso.id
            JOIN expedientes ON asignacion_caso.asigexp_id = expedientes.expid
            JOIN users ON `asignacion_docente_caso`.`docidnumber` = users.idnumber
            JOIN periodo ON asignacion_caso.periodo_id = periodo.id
            JOIN segmentos ON periodo.id = segmentos.perid
            JOIN sede_usuarios ON sede_usuarios.user_id = users.id
            WHERE expedientes.exptipoproce_id = '1' AND users.active=1
            AND users.idnumber != '79504911' 
            AND users.active_asignacion=1 AND segmentos.id = $segmento->segmento_id
            AND sede_usuarios.sede_id = " . session('sede')->id_sede . "
            GROUP BY `docidnumber` ORDER BY num_casos ASC
             ")
            );

             $docentes = DB::table('users')
                ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
                ->leftjoin('roles', 'role_user.role_id', '=', 'roles.id')
                ->leftjoin('referencias_tablas', 'referencias_tablas.id', '=', 'users.cursando_id')
                ->leftjoin('sede_usuarios', 'sede_usuarios.user_id', '=', 'users.id')
                ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_usuarios.sede_id')
                ->where('role_id', '4')
                ->where('users.active', true)
                ->where('users.idnumber', '<>','79504911')
                ->where('users.active_asignacion', true)
                ->where('sedes.id_sede', session('sede')->id_sede)
                ->select(
                    'users.active',
                    'users.id',
                    'ref_nombre',
                    'users.idnumber',
                    DB::raw('CONCAT(users.name," ",users.lastname) as full_name'),
                    'role_user.role_id',
                    'roles.display_name'
                )->orderBy('users.created_at', 'desc')->get(); 
                dd($docentes,$asig_doc); 
            if (count($docentes) > 0 and count($asig_doc) > 0) {
            if (count($docentes) == count($asig_doc)) {
              /*   $asignacion = new AsigDocenteCaso();
                $asignacion->docidnumber = $asig_doc[0]->docidnumber;
                $asignacion->asig_caso_id = $asignacion_caso->id;
                $asignacion->user_created_id = \Auth::user()->idnumber;
                $asignacion->user_updated_id = \Auth::user()->idnumber;
                $asignacion->save(); */
            } else {
                foreach ($docentes as $key => $docente) {
                    $found_key = array_search($docente->idnumber, array_column($asig_doc, 'docidnumber'));
                    if ($found_key === false) {
                      dd($docente);
                       /*  $asignacion = new AsigDocenteCaso();
                        $asignacion->docidnumber =  $docente->idnumber;
                        $asignacion->asig_caso_id = $asignacion_caso->id;
                        $asignacion->user_created_id = \Auth::user()->idnumber;
                        $asignacion->user_updated_id = \Auth::user()->idnumber;
                        $asignacion->save(); */
                        break;
                    }
                }
            }
        } elseif (count($docentes) > 0) {
            foreach ($docentes as $key => $docente) {
              dd($docente);
               /*  $asignacion = new AsigDocenteCaso();
                $asignacion->docidnumber =  $docente->idnumber;
                $asignacion->asig_caso_id = $asignacion_caso->id;
                $asignacion->user_created_id = \Auth::user()->idnumber;
                $asignacion->user_updated_id = \Auth::user()->idnumber;
                $asignacion->save(); */
                break;
            }
        }

  dd($docentes,$asig_doc); 
 // NotaExt::message(); 
  // dd(N
}

);

/* Route::get('/', function () {
  return view('mantenimiento');
 //  return view('welcome');
});
*/
Route::get('/mantenimiento', function () {
  return view('mantenimiento');
 //  return view('welcome');
}); 