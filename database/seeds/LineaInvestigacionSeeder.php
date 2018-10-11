<?php

use Illuminate\Database\Seeder;

class LineaInvestigacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            \DB::beginTransaction();
            // Investigación
            $lineas_investigacion = \DB::connection('remote')
                ->table('pro_areas')
                ->get()
                ->all();

            foreach ($lineas_investigacion as $linea_investigacion) {
                \DB::table('lineas_investigacion')->insert([
                    'nombre' => $linea_investigacion->nombre,
                    'codigo' => $linea_investigacion->codigo,
                    'tipo' => \App\Models\LineaInvestigacion::$INVESTIGACION
                ]);
            }

            // Temática
            $lineas_tematicas = \DB::connection('remote')
                ->table('rt_redtematica')
                ->get()
                ->all();

            foreach ($lineas_tematicas as $linea_tematica) {
                \DB::table('lineas_investigacion')->insert([
                    'nombre' => $linea_tematica->nombre,
                    'codigo' => $linea_tematica->codigo,
                    'tipo' => \App\Models\LineaInvestigacion::$TEMATICA
                ]);
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }
}
