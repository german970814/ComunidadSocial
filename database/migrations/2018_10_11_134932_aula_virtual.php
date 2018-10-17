<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AulaVirtual extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Tabla para guardar las tareas asociadas a un grupo de investigación
         * 
         * @model \App\Models\TareaGrupoInvestigacion
         */
        Schema::create('tareas_grupo_investigacion', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('titulo');
            $table->datetime('fecha_fin');
            $table->datetime('fecha_inicio');
            $table->text('descripcion')->nullable();
            $table->integer('maestro_id')->unsigned();
            $table->integer('grupo_investigacion_id')->unsigned();
        });

        /**
         * Tabla para guardar los documentos asociados a una tarea de un grupo de investigación
         * 
         * @model \App\Models\DocumentoTareaGrupoInvestigacion
         */
        Schema::create('documentos_tareas_grupo_investigacion', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('archivo');
            $table->integer('tarea_id')->unsigned();
        });

        /**
         * Tabla para guardar las entregas de las tareas de un grupo de investigación
         * 
         * @model \App\Models\EntregaTareaEstudiante
         */
        Schema::create('entregas_tarea_estudiante', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('archivo')->nullable();
            $table->text('descripcion')->nullable();
            $table->integer('tarea_id')->unsigned();
            $table->integer('usuario_id')->unsigned();
            $table->integer('calificacion')->nullable();
        });

        /**
         * Tabla para guardar los examenes que se hacen en un grupo de investigación
         * 
         * @model \App\Models\ExamenGrupoInvestigacion
         */
        Schema::create('examen_grupo_investigacion', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('titulo');
            $table->text('preguntas');
            $table->integer('duracion');
            $table->datetime('fecha_fin');
            $table->datetime('fecha_inicio');
            $table->text('descripcion')->nullable();
            $table->integer('maestro_id')->unsigned();
            $table->integer('grupo_investigacion_id')->unsigned();
        });

        /**
         * Tabla para guardar las entregas de los examenes de un grupo de investigación
         * 
         * @model \App\Models\EntregaExamenEstudiante
         */
        Schema::create('entregas_examen_estudiante', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('respuestas');
            $table->integer('examen_id')->unsigned();
            $table->integer('usuario_id')->unsigned();
            $table->boolean('cerrada')->default(false);  // Para saber si un usuario puede modificar sus respuestas
        });

        /**
         * Tabla para guardar los foros de un grupo
         * 
         * @model \App\Models\ForoGrupo
         */
        Schema::create('foros_grupo', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('tema');
            $table->integer('usuario_id')->unsigned();
            $table->integer('grupo_investigacion_id')->unsigned();
        });

        /**
         * Tabla para guardar las respuestas de un foro de grupo
         * 
         * @model \App\Models\RespuestaForoGrupo
         */
        Schema::create('respuestas_foro_grupo', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('descripcion');
            $table->integer('foro_id')->unsigned();
            $table->integer('usuario_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tareas_grupo_investigacion');
        Schema::dropIfExists('documentos_tareas_grupo_investigacion');
        Schema::dropIfExists('entregas_tarea_estudiante');
        Schema::dropIfExists('examen_grupo_investigacion');
        Schema::dropIfExists('entregas_examen_estudiante');
        Schema::dropIfExists('foros_grupo');
        Schema::dropIfExists('respuestas_foro_grupo');
    }
}
