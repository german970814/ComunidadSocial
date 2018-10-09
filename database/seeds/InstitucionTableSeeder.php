<?php

use Illuminate\Database\Seeder;

class InstitucionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::beginTransaction();
            $instituciones = DB::connection('remote')
                ->table('ins_sede')
                ->get()->all();

            foreach ($instituciones as $institucion) {
                $institucion_base = DB::connection('remote')
                    ->table('ins_institucion')
                    ->where('codigo', $institucion->codinstitucion)
                    ->get()->first();
                $municipio = DB::table('municipios')
                    ->where('codigo', $institucion->codmunicipio || $institucion_base->codmunicipio)
                    ->get()->first();

                
                $user = \App\Models\Usuario::create_user([
                    'apellidos' => '',
                    'nombres' => $institucion->nombre,
                    'email' => $institucion->dane,
                    'username' => $institucion->dane,
                    'password' => $institucion->dane
                ]);

                $usuario = \App\Models\Usuario::create([
                    'sexo' => 'M',
                    'user_id' => $user->id,
                    'tipo_documento' => 'NES',
                    'nombres' => $institucion->nombre,
                    'apellidos' => $institucion_base->nombre,
                    'numero_documento' => $institucion->dane,
                    'tipo_usuario' => \App\Models\Usuario::$INSTITUCION
                ]);
    
                DB::table('instituciones')->insert([
                    'telefono' => '0',
                    'dane' => $institucion->dane,
                    'municipio_id' => $municipio->id,
                    'nombre' => $institucion->nombre,
                    'codigo' => $institucion->codigo,
                    'email' => $institucion_base->email || '',
                    'director' => $institucion_base->idrector,
                    'usuario_id' => $usuario->id
                ]);
            }
            DB::commit();
        } catch (\PDOException $e) {
            DB::rollback();
            throw $e;
        }
    }
}
