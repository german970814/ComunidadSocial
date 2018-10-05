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

Route::get('/', function () {
    return view('home');
});

Auth::routes();
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');


// Route::get('/home', 'HomeController@index')->name('home');
Route::prefix('json')->group(function () {
    Route::middleware('auth')->get(
        '/usuario/{id}/solicitar-amistad/',
        '\App\Http\Controllers\SolicitudAmistadController@enviar_solicitud_amistad'
    )->name('usuario.solicitar-amistad');

    Route::middleware('auth')->get(
        '/usuario/{id}/aceptar-solicitud-amistad/',
        '\App\Http\Controllers\SolicitudAmistadController@aceptar_solicitud_amistad'
    )->name('usuario.aceptar-solicitud-amistad');

    Route::get(
        '/notificacion/{id}/leer/',
        '\App\Http\Controllers\NotificacionController@read_notificacion'
    )->name('notificacion.leer');
});

Route::middleware('notificacion')->group( function () {
    Route::prefix('/usuario')->group(function() {
        Route::get('/profile', 'UsuarioController@profile')->name('usuario.profile');
        Route::get('/{id}/amigos', 'UsuarioController@amigos')->name('usuario.amigos');
        Route::get('/amigos', 'UsuarioController@amigos')->name('usuario.self-amigos');
        Route::post('/profile/photo', 'UsuarioController@change_profile_photo')->name('usuario.change-profile-photo');
        Route::get('/{id}/photo', 'UsuarioController@get_user_profile_photo')->name('usuario.profile-photo');
    });

    Route::resource('usuario', 'UsuarioController');

    Route::prefix('/posts')->group(function() {
        Route::get('/{id}', 'PostController@show')->name('post.show');
        Route::post('/create', 'PostController@store')->name('post.store');

        Route::post('/{id}/comment/', 'PostController@comment')->name('post.comment');
        Route::get('/{id}/like/', 'PostController@like_post')->name('post.like');
    });
});

