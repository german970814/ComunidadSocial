<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\LineaInvestigacion;
use \App\Libraries\Form;

class LineaInvestigacionController extends Controller
{
    private $messages = [
        'required' => 'Este campo es requerido'
    ];

    public function index(Request $request) {
        $tipo = $request->tipo;
        if (!$tipo || ($tipo !== LineaInvestigacion::$TEMATICA && $tipo !== LineaInvestigacion::$INVESTIGACION)) {
            abort(404, 'The resource you are looking for could not be found');
        }

        $lineas = LineaInvestigacion::where('tipo', $tipo)->get();
        return view('grupos.linea_investigacion_list', ['lineas' => $lineas, 'tipo' => $tipo]);
    }

    public function create(Request $request) {
        $tipo = $request->tipo;
        if (!$tipo || ($tipo !== LineaInvestigacion::$TEMATICA && $tipo !== LineaInvestigacion::$INVESTIGACION)) {
            abort(404, 'The resource you are looking for could not be found');
        }
        $form = new Form(LineaInvestigacion::class, ['nombre', 'codigo', 'descripcion']);
        return view('grupos.linea_investigacion_create', compact(['form', 'tipo']));
    }

    public function store(Request $request) {
        $validated_data = $request->validate([
            'nombre' => 'required',
            'codigo' => 'required',
            'tipo' => 'required',
            'descripcion' => ''
        ], $this->messages);

        LineaInvestigacion::create($validated_data);

        return redirect(route('linea-investigacion.index') . '?tipo=' . $validated_data['tipo'])->with(
            'success', 'Has creado la linea de investigación'
        );
    }
}