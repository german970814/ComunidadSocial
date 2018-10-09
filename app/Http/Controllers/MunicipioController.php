<?php

namespace App\Http\Controllers;

use App\Models\Departamento;

class MunicipioController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function municipio_by_departamento($id) {
        $departamento = Departamento::findOrFail($id);
        $municipios = $departamento->municipios;

        return response()->json([
            'code' => 200,
            'message' => 'Operation success',
            'data' => $municipios->map(function ($municipio) {
                return ['id' => $municipio->id, 'nombre' => $municipio->nombre];
            })
        ]);
    }
}