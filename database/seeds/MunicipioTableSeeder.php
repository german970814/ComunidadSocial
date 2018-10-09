<?php

use Illuminate\Database\Seeder;

class MunicipioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $municipios = DB::connection('remote')->table('geo_municipios')->get()->all();
        foreach($municipios as $municipio) {
            $departamento = DB::table('departamentos')
                ->where('codigo', $municipio->coddepartamento)
                ->first();

            DB::table('municipios')->insert([
                'nombre' => $municipio->nombre,
                'codigo' => $municipio->cod,
                'departamento_id' => $departamento->id,
                'codigo_departamento' => $municipio->coddepartamento
            ]);
        }
    }
}
