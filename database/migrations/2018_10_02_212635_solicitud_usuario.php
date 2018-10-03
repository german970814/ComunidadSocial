<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SolicitudUsuario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitudes_usuario', function (Blueprint $table) {
            $table->integer('usuario_id')->unsigned()->index();
            $table->integer('solicitud_id')->unsigned()->index();
            $table->primary(array('usuario_id', 'solicitud_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitudes_usuario');
    }
}
