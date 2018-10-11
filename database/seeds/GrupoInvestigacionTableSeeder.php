<?php

use Illuminate\Database\Seeder;

class GrupoInvestigacionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()  // for rollback run: \App\Models\GrupoInvestigacion::where('id', '>', '1')->delete()
    {
        try {
            \DB::beginTransaction();

            // Investigación
            $proyectos = \DB::connection('remote')
                ->table('pro_proyectosede')
                ->get()
                ->all();

            foreach ($proyectos as $proyecto) {
                $institucion = \App\Models\Institucion::where('codigo', $proyecto->codsede)
                    ->first();
                $linea_investigacion = \App\Models\LineaInvestigacion::where('codigo', $proyecto->codarea)
                    ->first();

                if ($institucion && $linea_investigacion) {
                    \DB::table('grupos_investigacion')->insert([
                        'codigo' => $proyecto->codigo,
                        'nombre' => $proyecto->nombregrupo,
                        'institucion_id' => $institucion->id,
                        'linea_investigacion_id' => $linea_investigacion->id,
                        'tipo' => \App\Models\GrupoInvestigacion::$INVESTIGACION
                    ]);
                }
            }

            // Temática
            $redes = \DB::connection('remote')
                ->table('rt_redtematicasede')
                ->get()
                ->all();

            foreach ($redes as $red) {
                $institucion = \App\Models\Institucion::where('codigo', $red->codsede)
                    ->first();
                $linea_investigacion = \App\Models\LineaInvestigacion::where('codigo', $red->codredtematica)
                    ->first();

                if ($linea_investigacion) {
                    \DB::table('grupos_investigacion')->insert([
                        'codigo' => $red->codigo,
                        'institucion_id' => $institucion->id,
                        'nombre' => $linea_investigacion->get_nombre(),
                        'linea_investigacion_id' => $linea_investigacion->id,
                        'tipo' => \App\Models\GrupoInvestigacion::$TEMATICA,
                    ]);
                }
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }
}
