<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInitialTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Tabla de usuarios
         * 
         * @model \App\Models\Usuario
         */
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('sexo', 2);  // Label: Género, opciones: Masculino, Femenino
            $table->integer('user_id');  // Relación al modelo de usuario de laravel
            $table->string('nombres', 255);
            $table->string('apellidos', 255);
            $table->string('tipo_usuario', 2);  // Maestro, Estudiante, Administrador, Asesor, Secretaria, Investigador, Institución
            $table->string('tipo_documento', 5);
            $table->string('numero_documento', 255);
            $table->string('profile_photo')->nullable();
            $table->string('grupo_etnico', 2)->nullable();
            $table->timestamp('fecha_nacimiento')->nullable();
        });

        /**
         * Tabla de solicitudes de amistad
         * 
         * @model \App\Models\SolicitudAmistad
         */
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();  // updated_at será la fecha a partir de la cual son amigos
            $table->boolean('aceptada');  // Estado de la solicitud
            $table->integer('usuario_id')->unsigned();  // Usuario que envía la solicitud
        });

        /**
         * Tabla de relación solicitudes de amistad con usuario
         * 
         * @model null
         */
        Schema::create('solicitudes_usuario', function (Blueprint $table) {
            $table->integer('usuario_id')->unsigned()->index();
            $table->integer('solicitud_id')->unsigned()->index();
            $table->primary(array('usuario_id', 'solicitud_id'));
        });

        /**
         * Tabla de notificaciones
         * 
         * @model \App\Models\Notificacion
         */
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('mensaje');
            $table->string('tipo')->nullable();  // definidos en el modelo
            $table->string('link')->nullable();
            $table->boolean('leida')->default(false);
            $table->integer('usuario_id')->unsigned();  // usuario destino
            $table->integer('usuario_sender_id')->unsigned()->nullable();  // usuario envía, emite
        });

        /**
         * Tabla de posts
         * 
         * @model \App\Models\Post
         */
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('tipo');  // A quien va dirigido, Usuario o Grupo
            $table->text('mensaje');  // contenido
            $table->string('photo')->nullable();
            $table->integer('autor_id')->unsigned();  // Usuario
            $table->integer('usuario_destino_id')->unsigned()->nullable();  // Usuario de destino
            $table->integer('grupo_destino_id')->unsigned()->nullable();  // GrupoInvestigación de destino
        });

        /**
         * Tabla de comentarios de posts
         * 
         * @model \App\Models\ComentarioPost
         */
        Schema::create('comentarios_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('mensaje');
            $table->integer('post_id')->unsigned();
            $table->boolean('like')->default(false);
            $table->string('estado', 1)->default('A');  // Activo, Inactivo
            $table->integer('usuario_id')->unsigned();
        });

        /**
         * Tabla de reportes de comentarios y posts
         * 
         * @model \App\Models\ReporteComentarioPost
         */
        Schema::create('reportes_comentarios_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('razon')->nullable();
            $table->integer('usuario_id')->unsigned();
            $table->integer('post_id')->unsigned()->nullable();
            $table->integer('comentario_id')->unsigned()->nullable();
        });

        /**
         * Tabla de departamentos
         * 
         * @model null
         */
        Schema::create('departamentos', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('codigo');
            $table->string('nombre');
        });

        /**
         * Tabla de municipios
         * 
         * @model null
         */
        Schema::create('municipios', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('codigo');
            $table->string('nombre');
            $table->integer('departamento_id')->unsigned();
        });

        /**
         * Tabla de instituciones
         * 
         * @model \App\Models\Institucion
         */
        Schema::create('instituciones', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('dane');
            $table->string('nombre');
            $table->string('codigo');
            $table->string('director');
            $table->string('telefono');
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->integer('usuario_id')->unsigned();
            $table->integer('municipio_id')->unsigned();
            $table->string('tipo_institucion')->nullable();
        });

        /**
         * Tabla de solicitudes ingreso a institución
         * 
         * @model \App\Models\SolicitudInstitucion
         */
        Schema::create('solicitudes_institucion', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('usuario_id')->unsigned();
            $table->boolean('aceptada')->default(false);
            $table->integer('institucion_id')->unsigned();
        });

        /**
         * Tabla de líneas de investigación
         * 
         * @model \App\Models\LineaInvestigacion
         */
        Schema::create('lineas_investigacion', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('nombre');
            $table->string('codigo');
            $table->string('tipo', 1);  // Temático (Redes temáticas), Investigación (Grupos Investigación)
            $table->text('descripcion')->nullable();
        });

        /**
         * Tabla de grupos de investigación
         * 
         * @model \App\Models\GrupoInvestigacion
         */
        Schema::create('grupos_investigacion', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('nombre');
            $table->string('tipo', 1);  // Temático (Redes temáticas), Investigación (Grupos Investigación)
            $table->string('photo')->nullable();
            $table->text('descripcion')->nullable();
            $table->integer('institucion_id')->unsigned();
            $table->integer('linea_investigacion_id')->unsigned();
        });

        /**
         * Tabla de relacion entre coordinadores y grupos de investigacion
         * 
         * @model null
         */
        Schema::create('coordinadores_grupos_investigacion', function (Blueprint $table) {
            $table->integer('usuario_id')->unsigned()->index();
            $table->integer('linea_investigacion_id')->unsigned()->index();
            $table->primary(array('usuario_id', 'linea_investigacion_id'));
        });

        /**
         * Tabla de solicitudes de ingreso de los grupos de investigación
         * 
         * @model null
         */
        Schema::create('solicitudes_grupo_investigacion', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('usuario_id')->unsigned();
            $table->boolean('aceptada')->default(false);
            $table->integer('grupo_investigacion_id')->unsigned();
        });

        /**
         * Tabla de repositorio de los grupos de investigación
         * 
         * @model null
         */
        Schema::create('repositorios_grupo_investigacion', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('archivo');
            $table->integer('usuario_id')->unsigned();
            $table->integer('grupo_investigacion_id')->unsigned();
        });

        /**
         * Tabla de caja de herramientas
         * 
         * @model null
         */
        Schema::create('caja_herramientas', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('archivo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('solicitudes');
        Schema::dropIfExists('solicitudes_usuario');
        Schema::dropIfExists('notificaciones');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('comentarios_posts');
        Schema::dropIfExists('reportes_comentarios_posts');
        Schema::dropIfExists('departamentos');
        Schema::dropIfExists('municipios');
        Schema::dropIfExists('instituciones');
        Schema::dropIfExists('solicitudes_institucion');
        Schema::dropIfExists('lineas_investigacion');
        Schema::dropIfExists('grupos_investigacion');
        Schema::dropIfExists('coordinadores_grupos_investigacion');
        Schema::dropIfExists('solicitudes_grupo_investigacion');
        Schema::dropIfExists('repositorios_grupo_investigacion');
        Schema::dropIfExists('caja_herramientas');
    }
}
