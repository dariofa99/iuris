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

use App\AdminEncuestas;
use App\Conciliacion;
use App\ConciliacionEstado;
use App\Expediente;
use App\Jobs\ProcessSendNotificationGeneral;
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

Route::get('webservice', 'WebServicesController@index');

Route::post('webservice', 'WebServicesController@index');
Route::get('autorizacion', 'AutorizacionesController@verificar');
Route::post('autorizacion/verificar', 'AutorizacionesController@verificarPdf');

Route::resource('logout', 'LogoutController');

Route::get('terminosycondiciones', function () {
  return view('auth.terminosycondiciones');
});
Route::get('terminos/conciliacion', function () {
  return view('myforms.recepcion.terminos_conciliacion');
});

Route::get('/validar/cuenta/mensaje', function () {
  return view('myforms.mensaje_validar_cuenta');
});
Route::post('usuarios/update/email/solicitud', "UsersController@validateSolicitudEmail");
Route::post('usuarios/validate/account', "UsersController@validateAccount");
Route::post('usuarios/validate/code/account', "UsersController@validateCodeAccount");
Route::post('usuarios/reset/account', "UsersController@resetAccount");
Route::post('usuarios/reset/password/account', "UsersController@resetPasswordAccount");
Route::get("usuarios/active/account/{token}", "UsersController@activateAccount");
Route::get('conciliaciones/download/file/{file_id}', 'ConciliacionesController@downloadFile');
Route::post('conciliaciones/enviar/correo', 'ConciliacionesController@enviarCorreo');
Route::get('conciliaciones/get/comentarios', 'ConciliacionesController@getComentarios');

Route::get('conciliacion/encuestas/start', 'ConcEncuSatisfaccionController@index');
Route::get('encuestas/find/user', 'ConcEncuSatisfaccionController@findUser');
Route::get('expediente/encuestas/start', 'ExpEncuSatisfaccionController@index');
Route::get('expedientes/evaluar/buscar', 'ExpEncuSatisfaccionController@buscarExpedientes');
Route::post('expedientes/evaluar/store', 'ExpEncuSatisfaccionController@store');
Route::get('expediente/evaluar/encuesta', 'ExpEncuSatisfaccionController@renderForm');
Route::get('expedientes/evaluar/reportes', 'ExpEncuSatisfaccionController@showResultados')->name("expencuesta.index");
Route::get('expedientes/evaluar/data/chart', 'ExpEncuSatisfaccionController@getDataForChart');
Route::post('expedientes/evaluar/update', 'ExpEncuSatisfaccionController@update');






Route::get('videos', function () {
  return view('videos');
});

Route::get('audiencia/{code}', 'AudienciaController@ExternoSalaAudiencia');
Route::post('audiencia/{code}', 'AudienciaController@ExternoSalaAudiencia');
Route::get('audiencia/salaalaterna/{code}', 'AudienciaController@getSalaAlternaAudciencia');
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
Route::get('/recovery/account', 'Auth\ResetPasswordController@showRecoveryForm');

///rutas que requieren atenticación
Route::group(['middleware' => ['auth']], function () {
  //Nuevo usuarios
  Route::resource('usuarios', 'UsersController');
  Route::get("usuarios/buscar/persona", "UsersController@findUserWithFilter");
  Route::get("usuarios/get/by/idnumber", "UsersController@getUsersByIdNumber");
  Route::get("usuarios/find/by/name", "UsersController@findUserByNameOrLastNameAndRole");
  Route::get("usuarios/find/by/role", "UsersController@getUsersByRoleName");

  Route::post("usuarios/add/sede", "UsersController@addSede");
  Route::post("usuarios/update/profile/picture", "UsersController@uploadProfilePicture");

  Route::post('mail', 'MailController@store')->name('mail.store');


  //Citaciones estudiante
  Route::resource('citaciones/estudiante', 'CitacionEstudiantesController');
  Route::post('/citaciones/search/forday', 'CitacionEstudiantesController@searchCitasForDay');



  Route::resource('notifications', 'NotificationsController');
  Route::get('/admin/users/view/notifications', 'NotificationsController@index');
  Route::put('/admin/users/mark/read', 'NotificationsController@markAsRead');

  Route::get('dashboard/search', 'HomeController@search');

  Route::resource('users', 'MyusersController');
  Route::get('users/confirm/email/{token}', 'MyusersController@confirm_email');
  Route::get('users/find/us', 'MyusersController@findUserWithFilter');
  Route::post('users/store', 'MyusersController@userStore');
  ///////////////////////Encuestas de satisfacción
  Route::get('conciliacion/evaluar/buscar', 'ConcEncuSatisfaccionController@buscarConciliaciones');
  Route::post('conciliacion/evaluar/store', 'ConcEncuSatisfaccionController@store');
  Route::get('conciliacion/evaluar/encuesta', 'ConcEncuSatisfaccionController@renderForm')->name("encuestas.conciliacion");
  Route::post('conciliacion/evaluar/update', 'ConcEncuSatisfaccionController@update');
  Route::get('conciliacion/evaluar/reportes', 'ConcEncuSatisfaccionController@showResultados')->name("cencuesta.index");
  Route::get('conciliacion/evaluar/data/chart', 'ConcEncuSatisfaccionController@getDataForChart');

  Route::get('expediente/encuesta/preguntas/{id}', 'EncuestasSatisfaccionController@getQuestionsById');
  //EncuestasSatisfaccionController
  Route::post('encuestas/general/store', 'EncuestasSatisfaccionController@store');
  Route::post('encuesta/insert/categoria', 'EncuestasSatisfaccionController@storeCategoria');
  Route::put('encuesta/general/update/{id}', 'EncuestasSatisfaccionController@update');
  Route::post('encuesta/add/preguntas', 'EncuestasSatisfaccionController@addPreguntasEncuesta');
  Route::post('encuesta/delete/pregunta/{id}', 'EncuestasSatisfaccionController@deletePreguntaEncuesta');
  Route::group(['middleware' => ["vaccount", 'confirm_email', 'perfil']], function () {


    Route::get('home', function () {
      return redirect('/dashboard');
    });
    /* Route::get('/dashboard', function () {
  if(auth()->user()->hasRole("solicitante")){
    return Redirect::to('oficina/solicitante');
  }
    return view('myforms.frm_bienvenida');
}); */
    Route::get('/dashboard', "SedesController@selectSede");
    Route::get('/change/sedes', "SedesController@changeSede");
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
    Route::get('usuarios/index/page', 'MyusersController@index_page');
    Route::get('students/', 'MyusersController@indexEst')->name('students.index');
    Route::get('curso/empty', 'MyusersController@cursoEmpty')->name('curso.empty');
    Route::post('students/get', 'MyusersController@getEstudiantes');
    Route::post('docentes/get', 'MyusersController@getDocentes');
    Route::post('solicitantes/get', 'MyusersController@getSolicitantes');
    Route::get('users/get/{id}', 'MyusersController@getAllusers');

    //rutas para el manejo de roles y permisos
    Route::group(['prefix' => 'admin'], function () {
      Route::resource('/permisos', 'PermissionsController');
      Route::resource('/roles', 'RolesController');
      Route::get('/asig', 'RolesController@admin')->name('roles.admin');
      Route::post('/sync/permission', 'RolesController@syncPermissionRole');
      Route::post('/get/sync/permissions', 'RolesController@getPermissionsRole');
      Route::post('/permissions/change', 'RolesController@change_permissions');
    });

    Route::post('users/change/state', 'MyusersController@changeStateUser');

    Route::get('users/destroy/{id}', [
      'uses' => 'MyusersController@destroy',
      'as' => 'users.destroy'
    ]);

    Route::get('turnos/docentes', 'TurnosDocentesController@index');
    Route::get('turnos/docentes/{id}', 'TurnosDocentesController@store');
    Route::get('turnos/docentes/reporte/asis', 'TurnosDocentesController@show');
    Route::post('turnos/acdocentes', 'TurnosDocentesController@updateinfo');

    //Graficas
    Route::resource('graficas', 'GraficasController');
    Route::post('graficas/search', 'GraficasController@search_data');

    //Asignaciones Estudiantes Docente
    Route::resource('docentes/asigest', 'AsigDocentEstController');
    Route::post('docentes/asigest/confirm', 'AsigDocentEstController@confAsigDoc');

    //Asignaciones casos Docente
    Route::resource('docentes/casos', 'AsigDocenteCasoController');
    //Route::post('docentes/asigest/confirm','AsigDocenteCaso@confAsigDoc');

    //Horario docente
    Route::resource('docentes/horario', 'HorarioDocenteController');
    Route::post('docentes/search/horario', 'HorarioDocenteController@searchHorasDocente');
    Route::post('docentes/horario/delete/all', 'HorarioDocenteController@deleteAllHorarioDocentes');
    //Route::get('docentes/horario/search/estudiante','HorarioDocenteController@searchEstud');

    //Turnos
    Route::get('turnos/asistencia', 'TurnosController@reporasistencia');
    Route::get('turnos/asistencia/detalles/{idnum}', 'TurnosController@reporAsistenciaDetalles');
    Route::resource('turnos', 'TurnosController');
    Route::delete('turnos/delete/all', 'TurnosController@deleteAllTurnos');
    Route::get('turnos/descargar/curso', 'TurnosController@descargarTurnosExcel');
    Route::get('turnos/buscar/index', 'TurnosController@buscar');

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


    //
    Route::resource('incidencias', 'IncidenciasController');
    Route::get('incidencias/by/asignacion/{id}', 'IncidenciasController@getByAsigCaso');
    //Expedientes
    Route::resource('expedientes', 'ExpedienteController');
    Route::post('expedientes/cambiar/fecha/evaluacion', 'ExpedienteController@cambiarFechaEvaluacion');

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
    Route::post('expedientes/pausar', 'ExpedienteController@pausarExpediente');
    Route::get('expedientes/get/pausas', 'ExpedienteController@getPausasExpediente');
    Route::delete('expedientes/delete/pausa/{id}', 'ExpedienteController@deletePausaExpediente');
    Route::delete('expedientes/remove/user/{id}', 'ExpedienteController@deleteUserExpediente');
    Route::put('expedientes/update/pausa/{id}', 'ExpedienteController@updatePausaExpediente');
    Route::post('expediente/add/user', 'ExpedienteController@addUser');
    Route::get('expedientes/get/teachers', 'ExpedienteController@getTeacherCases');
    Route::get('expediente/createstream/{id}', 'ExpedienteController@createStream');
    Route::get('expediente/sharestream/{id}', 'ExpedienteController@shareStream');
    Route::post('expedientes/asignar/conciliacion', 'ExpedienteController@asigConciliacion');
    Route::get('expedientes/get/exp/rama/student/{idnumber}', 'ExpedienteController@getExpedientesRamaEstudiante');
    //Ediar usuarios desde Expedientes
    Route::resource('expuser', 'ExpedienteUserController');

    //cierre de caso/expedientes
    Route::resource('expcierrecaso', 'ExpedienteCierreController');

    //estados caso
    Route::resource('estados/caso', 'EstadosCasoController');
    Route::post('/estado/search/', 'EstadosCasoController@search');
    Route::post('/estado/cerrar/caso', 'EstadosCasoController@cerrarCaso');
    Route::post('/estado/caso/volver/abrir', 'EstadosCasoController@abrir_caso');
    Route::post('/estado/caso/cerrar/minima', 'EstadosCasoController@cerrarCasoNotaMinima');
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

    Route::get('actpdfdownload/{id}/{user_doc}', 'ActuacionController@actpdfdownload');


    //requerimientos
    Route::resource('requerimientos', 'RequerimientoController');
    Route::get('reqpdfgen/{id}',  'RequerimientoController@reqpdfgen');
    Route::post('requerimientos/update/{id}',  'RequerimientoController@updateReq');

  //reparto conciliaciones
  Route::resource('conciliaciones/reparto', 'RepartoConciliacionController');


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
    Route::post('segmentos/change/state/{id}', 'SegmentosController@changeState');
    Route::get('segmentos/change/fc', 'SegmentosController@change_state_segfc');
    Route::get('segmentos/close/{id}', 'SegmentosController@closeSegmento');
    //Periodos
    Route::resource('periodos', 'PeriodosController');
    Route::post('periodos/change/state/{id}', 'PeriodosController@changeState');
    Route::get('periodos/buscar/segmentos/{id}', 'PeriodosController@searchSegmentos');

    //Auditoria
    Route::resource('auditoria', 'AuditoriaController');

    //Documentos
    Route::resource('documentos', 'CaseLogController');
    Route::get('documentos/get', 'CaseLogController@getDocuments');
    Route::post('documentos/{id}', 'CaseLogController@update');
    Route::get('/descargar/documento/{id}', 'CaseLogController@downloadFileLog');

    //Conciliaciones encuestas
    // Route::resource('conciliacion/encuestas', 'ConcEncuSatisfaccionController');


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
    Route::get('audiencias', 'AudienciaController@calendarAudiencias')->name('audiencias.agenda');
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
    Route::get('get/files/by/category', 'ConciliacionesController@getFilesByCategory');
    Route::post('crear/actas/by/status', 'ConciliacionesController@crearActa');
    Route::get('conciliacion/resumen/{id}', 'ConciliacionesController@verResumenPdf');

    Route::post('conciliacion/audiencia/create', 'AudienciaController@audienciaCreate');
    Route::get('conciliacion/users/salasalternasaudiencia/{id}/{cont}', 'AudienciaController@getSalasAudiencia');
    Route::post('conciliacion/create/salasalternasaudiencia', 'AudienciaController@postSalasAudienciaCreate');
    Route::get('conciliacion/numusers/salasalternasaudiencia/{id}', 'AudienciaController@getUsersSalasAudiencia');
    Route::get('conciliacion/est/rol/{idconciliacion}', 'AudienciaController@getEstudianteRol');
    Route::get('conciliacion/estados/rol', 'AudienciaController@getconciliacionRolList');
    Route::post('conciliacion/update/est/rolconciliacion', 'AudienciaController@postConciliacionEstRolUpate');
    Route::get('conciliacion/turnos/estudiantes/asig/{data}/{id}', 'AudienciaController@getConciliacionTurnosEst');
    Route::get('conciliacion/chat/{chatroom}', 'AudienciaController@getChangeChatRoom');



    ///////////////////////Agendas
    Route::get('search/citas/for/calendar', 'AgendasController@searchCitasForCalendar');
    Route::get('search/citas/of/day', 'AgendasController@searchCitasOfDay');
    Route::get('calendar/citas/by/teacher', 'AgendasController@formCitasByTeacher')->name("ag.cedoc");

    Route::get('calendar/citas/by/student', 'AgendasController@formCitasByStudent')->name("ag.cestu");

    Route::get('search/turn/of/teachers', 'AgendasController@searchTurnTeachers');
    //PDF >Reportes

    //turnos estudiantes
    Route::post('/agendar/turnos/estudiantes', 'TurnoEstudianteDocenteController@store');
    Route::post('/notificar/turnos/estudiantes', 'TurnoEstudianteDocenteController@notificarTurnoEstudiante');
    Route::put('/actualizar/turnos/estudiantes/{id}', 'TurnoEstudianteDocenteController@update');
    Route::delete('/eliminar/turnos/estudiantes/{id}', 'TurnoEstudianteDocenteController@destroy');
    /////////////////////////////////////////////////////////////////////////////////////
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
    Route::get('conciliacion/get/actas/creadas', 'ConciliacionesReportesController@getActasCreadas');

    Route::get('pdf/reportes/editar/temporal/{reporte}/{conciliacion}/{estado}', 'ConciliacionesReportesController@editReporteTemporal');
    Route::get('conciliacion/reporte/firmantes', 'ConciliacionesReportesController@getFirmantes');
    Route::post('conciliacion/reporte/firmantes', 'ConciliacionesReportesController@setFirmantes');
    Route::post('conciliacion/reporte/revocar/firmas', 'ConciliacionesReportesController@revocarFirmas');
    Route::post('conciliacion/reporte/firmantes/reenviar/mails', 'ConciliacionesReportesController@reenviarMails');
    Route::get('categorias/get/from/reports', 'ConciliacionesReportesController@getFromReports');
    Route::post('conciliacion/reporte/store/personalized/values', 'ConciliacionesReportesController@insertPersonalizedReportValues');
    Route::post('conciliacion/reporte/revock/firma', 'ConciliacionesReportesController@revockFirma');

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
    Route::get('categorias/get/by/filter', 'ReferencesDataController@getByRefDataFilter');

    //ReferencesStaticData
    Route::resource('categories', 'ReferencesStaticDataController');


    //consulta calendario
    Route::get('consultahor/{clbd}/{hrbd}/{datev}', 'HorarioController@consultach');
    Route::get('consultahordoc/{clbd}/{hrbd}/{datev}', 'HorarioController@consultahordoc');
    Route::get('consultahordocasis/{clbd}/{hrbd}/{datev}', 'HorarioController@consultahordocasis');
    Route::post('horario/updatehordocasis', 'HorarioController@updatehordocasis');
    Route::post('horario/regishordocasis', 'HorarioController@regishordocasis');


    Route::post('solicitudes/store/documento', 'SolicitudesController@storeDocument');

    //prueba
    Route::get('prueba/expedienteasig', 'ExpedienteController@pruebaasig');
    Route::get('prueba/citas', 'CitacionEstudiantesController@citasAutomatic');
    Route::get('prueba/citas/correo', 'CitacionEstudiantesController@listCorreoCitasGen');
    Route::get('/mail/html', function () {
      $mensaje = getMessagesForPro(245, "aad2012");
      return view('myforms.mails.frm_notificaciones_procjudexp', compact('mensaje'));
    });
  }); //fin middleware perfil
  Route::group(['middleware' => 'front'], function () {

    Route::group(['prefix' => 'oficina'], function () {
      Route::get('solicitante/conciliaciones', 'FrontController@conciliaciones')->name("front.conciliaciones");
      Route::get('solicitante/conciliaciones/solicitud', 'FrontController@conciliaciones_solicitud')->name("front.conciliaciones.solicitud");
      Route::get('solicitante/conciliaciones/{id}/edit', 'FrontController@conciliacion_edit')->name("front.conciliacion.edit");
      Route::get('solicitante/conciliaciones/create', 'FrontController@conciliacion_store')->name("front.conciliacion.store");
      Route::resource('solicitante', 'FrontController');

      Route::get('solicitante/solicitud/{id}', 'FrontController@solicitud_show');
    });


    //solicitudes 
    Route::get('solicitudes/files/{id}/edit', 'SolicitudesController@editDocumento');
    Route::post('solicitudes/update/documento', 'SolicitudesController@updateDocument');
    Route::get('solicitudes/files/delete/{id}', 'SolicitudesController@deleteDocumento');
  }); //fin middleware front
  Route::get('/', function () {
    return redirect('/dashboard');
  });
  //Referencias
  Route::get('obtener/estados/expedientes', 'ReferencesController@getEstadosForExpediente');
}); //fin middleware auth

Route::get('solicitudes/recepcion/conciliacion/{token}', 'SolicitudesController@recepcion_conciliacion');

Route::post('usuarios', 'UsersController@store');
Route::resource('solicitudes', 'SolicitudesController');
//Route::post('solicitudes/expediente', 'SolicitudesController@storeExpediente');
/* 
Route::get('solicitudes/view/{token}', 'SolicitudesController@waitRoom');
Route::post('solicitudes/user/register', 'SolicitudesController@userRegister');
Route::get('solicitudes/find/e', 'SolicitudesController@find'); */
Route::get('login', array('as' => 'login', function () {
  return view('myforms.login');
}));
Route::get('/solicitudes/conciliacion/registro', 'SolicitudesController@registro');

Route::get('/solicitudes/conciliacion/recepcion', 'SolicitudesController@solicitar');
Route::post('/solicitudes/conciliacion/recepcion', 'SolicitudesController@solicitar_store')->name("solicitudes.conciliacion");

Route::get('/solicitudes/expedientes/recepcion', 'SolicitudesController@solicitarExpediente');
Route::post('/solicitudes/expedientes/recepcion', 'SolicitudesController@solicitarExpedienteStore');
Route::get('/solicitudes/recepcion/expedientes/{token}', 'SolicitudesController@recepcion_expediente');

Route::post('/solicitudes/registro/usuario', 'SolicitudesController@userRegister');


Route::post('/solicitudes/buscar/number', 'SolicitudesController@buscarSolicitud');

Route::get('recepcion', "SolicitudesController@recepcion");
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

Route::get('/prueba/filter/{id}', 'ExpedienteController@prueba');

Route::get('/prueba', function () {

  $expediente = DB::table('asistencia')
  ->whereDate('asistencia.astfecha', Carbon::now()->format('Y-m-d'))
  ->get();

  // $expediente->setNotActLimit();


  dd($expediente);
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

  return view('myforms.mails.recovery_password', [
    'token' => "heols",
    'url' => url('/conciliaciones/1/edit')
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
    ->select('users.name', 'users.id', 'users.idnumber', 'rama_derecho.subrama')
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

  dd($doceWithRama, $asig_doc);
  // NotaExt::message(); 
  // dd(N
});
Route::get('/pruebas/users', 'UsersController@pruebas');
Route::get(
  '/pruebas',
  function () {

    $user = User::where('idnumber', '1085278208')->first();

    return view('myforms.mails.recovery_account', [
      "user" => $user,
    ]);
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
