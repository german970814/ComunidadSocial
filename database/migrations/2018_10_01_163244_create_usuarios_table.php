<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sexo', 2);
            $table->integer('user_id');
            $table->string('nombres', 255);
            $table->string('apellidos', 255);
            $table->string('tipo_usuario', 2);
            $table->string('tipo_documento', 5);
            $table->string('numero_documento', 255);
            $table->string('grupo_etnico', 2)->nullable();
            $table->timestamp('fecha_nacimiento')->nullable();
            $table->timestamps();
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
    }
}
