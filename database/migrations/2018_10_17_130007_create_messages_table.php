<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Modelo para guardar las conversaciones entre usuarios
         * 
         * @model \App\Models\Conversacion
         */
        Schema::create('conversaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('emisor_id')->unsigned();  // Usuario
            $table->integer('receptor_id')->unsigned();  // Usuario
        });

        /**
         * Modelo para guardar los mensajes entre los usuarios
         * 
         * @model \App\Models\Mensaje
         */
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('mensaje');
            $table->integer('usuario_id')->unsigned();
            $table->integer('conversacion_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversaciones');
    }
}
