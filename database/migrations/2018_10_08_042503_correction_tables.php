<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CorrectionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Se agrega un username para autentificar instituciones
            $table->string('username')->unique()->nullable();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->string('estado')->default('A');  // Activa o Inactiva (Para revisiones)
        });

        Schema::table('municipios', function (Blueprint $table) {
            // Se agrega un campo adicional, dada las relaciones en la tabla remota
            $table->string('codigo_departamento')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
