<?php

use Illuminate\Database\Seeder;

class DepartamentoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departamentos = DB::connection('remote')->table('geo_departamentos')->get()->all();
        foreach ($departamentos as $departamento) {
            DB::table('departamentos')->insert([
                'nombre' => $departamento->nombre,
                'codigo' => $departamento->cod
            ]);
        }
    }
}
