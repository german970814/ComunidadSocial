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

Auth::routes();
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');


// Route::get('/home', 'HomeController@index')->name('home');
Route::prefix('json')->group(function () {
    Route::post(
        '/usuario/remoto/',
        'UsuarioController@get_remote_usuario_data'
    )->name('usuario.remoto');

    Route::middleware('auth')->get(
        '/reportes/comentario/{id}',
        '\App\Http\Controllers\ComentarioPostController@reportar_comentario'
    )->name('comentario.reportar');

    Route::get(
        '/notificacion/{id}/leer/',
        '\App\Http\Controllers\NotificacionController@read_notificacion'
    )->name('notificacion.leer');

    Route::get(
        '/departamentos/{id}/municipios/',
        '\App\Http\Controllers\MunicipioController@municipio_by_departamento'
    )->name('departamento.municipios');

    Route::middleware('auth')->get(
        '/institucion/{id}/enviar_solicitud',
        '\App\Http\Controllers\InstitucionController@solicitud_ingreso_institucion'
    )->name('institucion.solicitud-ingreso-institucion');

    Route::middleware('auth')->post(
        '/aula/examen/{id}/respuesta',
        '\App\Http\Controllers\AulaVirtualController@guardar_respuesta_estudiante'
    )->name('aula.guardar-respuesta-estudiante');

    Route::middleware('auth')->post(
        '/mensajes/conversacion/get/{id}',
        'MensajeController@get_conversacion'
    )->name('mensajes.get-conversacion');
    Route::middleware('auth')->post(
        '/mensajes/conversacion/{id}/mensaje/nuevo',
        'MensajeController@guardar_mensaje'
    )->name('mensajes.guardar-mensaje');
    Route::middleware('auth')->get(
        '/mensajes/conversacion/{id}',
        'MensajeController@ver_conversacion'
    )->name('mensajes.ver-conversacion');
    Route::get(
        '/usuarios/buscar/amigos',
        'UsuarioController@buscar_amigos'
    )->name('usuario.buscar-amigos');
});

Route::middleware('notificacion')->group( function () {
    Route::get('/', function () {
        return view('home');
    });

    // Vistas con autentificacion
    Route::middleware('auth')->group(function () {
        // Lineas investigación
        Route::resource('linea-investigacion', 'LineaInvestigacionController');

        Route::prefix('/admin')->group(function () {
            Route::get(
                '/create/asesor',
                'AdministradorController@create_usuario_asesor'
            )->name('admin.create-usuario-asesor');
            Route::post(
                '/create/asesor',
                'AdministradorController@store_usuario_asesor'
            )->name('admin.store-usuario-asesor');
            Route::post(
                '/asesor/remote-data',
                'AdministradorController@get_asesor_data'
            )->name('admin.asesor-remote-data');
            Route::get(
                '/grupos/{id}/asignar-asesor',
                'AdministradorController@asignar_asesor_grupo'
            )->name('admin.asignar-asesor-grupo');
            Route::post(
                '/grupos/{id}/asignar-asesor',
                'AdministradorController@guardar_asesor_grupo'
            )->name('admin.guardar-asesor-grupo');
        });


        // Instituciones
        Route::prefix('/institucion')->group(function () {
            Route::get(
                '/{id_solicitud}/aceptar_solicitud_ingreso_institucion',
                'InstitucionController@aceptar_solicitud_ingreso_institucion'
            )->name('institucion.aceptar-solicitud-ingreso-institucion');
            Route::get(
                '/{id_solicitud}/rechazar_solicitud_ingreso_institucion',
                'InstitucionController@rechazar_solicitud_ingreso_institucion'
            )->name('institucion.rechazar-solicitud-ingreso-institucion');
            Route::get('/editar', 'InstitucionController@editar')->name('institucion.editar');
            Route::get('/{id}/integrantes', 'InstitucionController@integrantes')->name('institucion.integrantes');
            Route::get(
                '/{id}/solicitudes',
                'InstitucionController@solicitudes_ingreso_institucion'
            )->name('institucion.solicitudes-ingreso-institucion');
        });
        Route::resource('institucion', 'InstitucionController');

        // Posts
        Route::prefix('/posts')->group(function() {
            Route::get('/{id}', 'PostController@show')->name('post.show');
            Route::get('/{id}/photo', 'PostController@post_photo')->name('post.photo');
            Route::post('/create/{tipo}', 'PostController@store')->name('post.store');

            Route::post('/{id}/comment/', 'PostController@comment')->name('post.comment');
            Route::get('/{id}/like/', 'PostController@like_post')->name('post.like');
        });

        Route::prefix('/grupos')->group(function() {
            Route::get('/{id}', 'GrupoInvestigacionController@show')->name('grupos.show');
            Route::get('/{id}/solicitudes', 'GrupoInvestigacionController@solicitudes_ingreso')->name('grupos.solicitudes');
            Route::get('/{id}/solicitar', 'GrupoInvestigacionController@solicitar_unirse_grupo')->name('grupos.solicitar');
            Route::get('/{id}/integrantes', 'GrupoInvestigacionController@integrantes')->name('grupos.integrantes');
            Route::get('/solicitud/{id}/aceptar', 'GrupoInvestigacionController@aceptar_solicitud')->name('grupos.solicitud-aceptar');
            Route::get('/solicitud/{id}/rechazar', 'GrupoInvestigacionController@rechazar_solicitud')->name('grupos.solicitud-rechazar');
            Route::get('/create/{tipo}/{institucion_id}', 'GrupoInvestigacionController@create')->name('grupos.create');
            Route::post('/store/{tipo}/{institucion_id}', 'GrupoInvestigacionController@store')->name('grupos.store');
            Route::get(
                '/{tipo}/usuario/{usuario_id}',
                'GrupoInvestigacionController@grupos_investigacion_usuario'
            )->name('grupos.grupos-investigacion-usuario');
            Route::get(
                '/{tipo}/institucion/{usuario_id}',
                'GrupoInvestigacionController@grupos_investigacion_institucion'
            )->name('grupos.grupos-investigacion-institucion');
            Route::get(
                '{id}/foros/',
                'GrupoInvestigacionController@ver_foros'
            )->name('grupos.ver-foros');
            Route::get(
                '{id}/foros/crear',
                'GrupoInvestigacionController@crear_foro'
            )->name('grupos.crear-foro');
            Route::post(
                '{id}/foros/crear',
                'GrupoInvestigacionController@guardar_foro'
            )->name('grupos.guardar-foro');
            Route::get(
                'foros/{id}',
                'GrupoInvestigacionController@ver_foro'
            )->name('grupos.ver-foro');
            Route::get(
                'foros/{id}/editar',
                'GrupoInvestigacionController@editar_foro'
            )->name('grupos.editar-foro');
            Route::post(
                'foros/{id}/editar',
                'GrupoInvestigacionController@actualizar_foro'
            )->name('grupos.actualizar-foro');
            Route::post(
                'foros/{id}/respuesta',
                'GrupoInvestigacionController@guardar_respuesta_foro'
            )->name('grupos.guardar-respuesta-foro');
        });

        Route::prefix('/aula')->group(function() {
            Route::get(
                '/tareas/grupo/{id}',
                'AulaVirtualController@listar_tareas_grupo'
            )->name('aula.tareas-grupo');
            Route::get(
                '/tareas/grupo/{id}/crear',
                'AulaVirtualController@crear_tarea'
            )->name('aula.crear-tarea-grupo');
            Route::post(
                '/tareas/grupo/{id}/crear',
                'AulaVirtualController@guardar_tarea'
            )->name('aula.guardar-tarea-grupo');
            Route::post(
                '/tareas/{id}/entrega',
                'AulaVirtualController@agregar_entrega'
            )->name('aula.agregar-entrega');
            Route::get(
                '/tareas/{id}',
                'AulaVirtualController@ver_tarea'
            )->name('aula.ver-tarea');
            Route::get(
                '/tareas/{id}/editar',
                'AulaVirtualController@editar_tarea'
            )->name('aula.editar-tarea');
            Route::post(
                '/tareas/{id}/actualizar',
                'AulaVirtualController@actualizar_tarea'
            )->name('aula.actualizar-tarea');
            Route::get(
                '/tareas/{id}/entregas',
                'AulaVirtualController@ver_entregas_tarea'
            )->name('aula.ver-entregas-tarea');
            Route::get(
                '/tareas/entregas/{id}',
                'AulaVirtualController@ver_entrega_tarea'
            )->name('aula.ver-entrega');
            Route::get(
                '/tareas/documentos/{id}',
                'AulaVirtualController@get_documento'
            )->name('aula.ver-documento');
            Route::get(
                '/tareas/documentos/entrega/{id}',
                'AulaVirtualController@get_documento_entrega'
            )->name('aula.ver-documento-entrega');
            Route::get(
                '/tareas/documentos/{id}/eliminar',
                'AulaVirtualController@eliminar_documento'
            )->name('aula.eliminar-documento');
            Route::get(
                '/examenes/grupo/{id}/crear',
                'AulaVirtualController@crear_examen'
            )->name('aula.crear-examen');
            Route::post(
                '/examenes/grupo/{id}/crear',
                'AulaVirtualController@guardar_examen'
            )->name('aula.guardar-examen');
            Route::get(
                '/examenes/{id}',
                'AulaVirtualController@ver_examen'
            )->name('aula.ver-examen');
            Route::get(
                '/examenes/{id}/editar',
                'AulaVirtualController@editar_examen'
            )->name('aula.editar-examen');
            Route::post(
                '/examenes/{id}/editar',
                'AulaVirtualController@actualizar_examen'
            )->name('aula.actualizar-examen');
            Route::get(
                '/examenes/grupo/{id}',
                'AulaVirtualController@listar_examenes_grupo'
            )->name('aula.examenes-grupo');
            Route::get(
                '/examenes/{id}/prueba',
                'AulaVirtualController@examen_estudiante'
            )->name('aula.examen-estudiante');
            Route::get(
                '/examenes/{id}/entregas',
                'AulaVirtualController@entregas_examen'
            )->name('aula.entregas-examen');
            Route::get(
                '/examenes/entrega/{id}',
                'AulaVirtualController@entrega_examen'
            )->name('aula.entrega-examen');
        });
    });

    // Usuarios
    Route::prefix('/usuario')->group(function() {
        Route::get(
            '/{id}/notificaciones',
            'NotificacionController@user_has_new_notificacion'
        )->name('notificaciones');
        Route::get('/profile', 'UsuarioController@profile')->name('usuario.profile');
        Route::get('/{id}/informacion', 'UsuarioController@detail')->name('usuario.detail');
        Route::get('/{id}/amigos', 'UsuarioController@amigos')->name('usuario.amigos');
        Route::get('/amigos', 'UsuarioController@amigos')->name('usuario.self-amigos');
        Route::post('/profile/photo', 'UsuarioController@change_profile_photo')->name('usuario.change-profile-photo');
        Route::get('/{id}/photo', 'UsuarioController@get_user_profile_photo')->name('usuario.profile-photo');
        Route::get('/buscar', 'UsuarioController@buscar_usuarios')->name('usuario.buscar_usuarios');
        Route::get('/solicitudes-amistad/{id?}', 'UsuarioController@solicitudes_amistad_usuario')->name('usuario.solicitudes-amistad');
        Route::get('/mensajes', 'UsuarioController@mensajes_usuario')->name('usuario.mensajes');
        Route::middleware('auth')->get(
            '/{id}/solicitud-amistad/',
            'SolicitudAmistadController@solicitud_amistad'
        )->name('usuario.solicitar-amistad');
        Route::middleware('auth')->get(
            '/{id}/aceptar-solicitud-amistad/',
            '\App\Http\Controllers\SolicitudAmistadController@aceptar_solicitud_amistad'
        )->name('usuario.aceptar-solicitud-amistad');
        Route::middleware('auth')->get(
            '/{id}/rechazar-solicitud-amistad/',
            '\App\Http\Controllers\SolicitudAmistadController@rechazar_solicitud_amistad'
        )->name('usuario.rechazar-solicitud-amistad');
    });
    Route::resource('usuario', 'UsuarioController');
});

