<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\GrupoInvestigacion;
use App\Models\TareaGrupoInvestigacion;
use App\Models\EntregaTareaEstudiante;
use App\Libraries\{ Form, Helper };

class AulaVirtualController extends Controller  // TODO: Validar el asesor sea el asesor del grupo
{
    private function _usuario_puede_ver_tarea($grupo) {
        $user = \Auth::guard()->user();
        if (
            $user->usuario->pertenece_grupo($grupo) ||
            $user->is_administrador() ||
            $user->is_asesor()
        ) {
            return true;
        }
        return false;
    }

    public function ver_tarea($id) {
        $tarea = TareaGrupoInvestigacion::findOrFail($id);
        $user = \Auth::guard()->user();
        $grupo = $tarea->grupo;
        $entrega = null;

        if ($this->_usuario_puede_ver_tarea($grupo)) {
            $form = new Form(EntregaTareaEstudiante::class, ['descripcion'], [
                'descripcion' => [
                    'type' => 'textarea',
                    'label' => 'Entrega'
                ]
            ]);
            if ($user->is_estudiante()) {
                $entrega = EntregaTareaEstudiante::where('usuario_id', $user->usuario->id)
                    ->where('tarea_id', $tarea->id)->first();
                if ($entrega) {
                    $form = new Form($entrega, ['descripcion'], [
                        'descripcion' => [
                            'type' => 'textarea',
                            'label' => 'Entrega'
                        ]
                    ]);
                }
            }
            return view('aula.ver_tarea', compact(['grupo', 'tarea', 'entrega', 'form']));
        }

        abort(404, 'Página no encontrada');
    }

    public function listar_tareas_grupo($id) {
        $grupo = GrupoInvestigacion::findOrFail($id);
        $user = \Auth::guard()->user();

        if ($this->_usuario_puede_ver_tarea($grupo)) {
            return view('aula.tareas_grupo', compact(['grupo']));
        }
        abort(404, 'Página no encontrada');
    }

    public function crear_tarea($id) {
        $grupo = GrupoInvestigacion::findOrFail($id);
        $user = \Auth::guard()->user();

        if (($user->usuario->pertenece_grupo($grupo) && $user->is_maestro()) || $user->is_administrador() || $user->is_asesor()) {
            $form = new Form(TareaGrupoInvestigacion::class, [
                'titulo', 'fecha_inicio', 'fecha_fin', 'descripcion'
            ]);
            return view('aula.crear_tarea_grupo', compact(['grupo', 'form']));
        }
        abort(404, 'Página no encontrada');
    }

    public function guardar_tarea(Request $request, $id) {
        $grupo = GrupoInvestigacion::findOrFail($id);
        $usuario = \Auth::guard()->user()->usuario;

        if (($usuario->pertenece_grupo($grupo) && $usuario->is_maestro()) || $usuario->is_administrador() || $usuario->is_asesor()) {
            $validated_data = $request->validate([
                'descripcion' => '',
                'titulo' => 'required',
                'fecha_fin' => 'required|date|after:fecha_inicio',
                'fecha_inicio' => 'required|date|before:fecha_fin',
                'files.*' => 'file|image|mimes:doc,pdf,docx,zip,png,jpeg,jpg'
            ]);

            $tarea = TareaGrupoInvestigacion::create([
                'maestro_id' => $usuario->id,
                'titulo' => $validated_data['titulo'],
                'grupo_investigacion_id' => $grupo->id,
                'fecha_fin' => $validated_data['fecha_fin'],
                'descripcion' => $validated_data['descripcion'],
                'fecha_inicio' => $validated_data['fecha_inicio'],
            ]);

            $files = $validated_data['files'];
            foreach ($files as $file) {
                $filename = 'tareas/' . $tarea->id . '/' . $file->getClientOriginalName() . '.' . $file->getClientOriginalExtension();
                if ($file) {
                    Storage::disk('local')->put($filename, File::get($file));
                    \App\Models\DocumentoTareaGrupoInvestigacion::create([
                        'archivo' => $filename,
                        'tarea_id' => $tarea->id
                    ]);
                }
            }

            return redirect()
                ->route('aula.tareas-grupo', $grupo->id)
                ->with('success', 'Se ha programado la tarea con éxito');
        }
        abort(404, 'Página no encontrada');
    }

    public function ver_entregas_tarea($id) {
        $tarea = TareaGrupoInvestigacion::findOrFail($id);
        $usuario = \Auth::guard()->user()->usuario;

        if (
            ($usuario->pertenece_grupo($tarea->grupo) && $usuario->is_asesor()) ||
            ($usuario->pertenece_grupo($tarea->grupo) && $usuario->is_maestro() && $usuario->id == $tarea->maestro->id) ||
            $usuario->is_administrador()
        ) {
            $grupo = $tarea->grupo;
            return view('aula.entregas_tarea', compact(['grupo', 'tarea']));
        }
        abort(404, 'Página no encontrada');
    }

    public function ver_entrega_tarea($id) {
        $entrega = EntregaTareaEstudiante::findOrFail($id);
        $usuario = \Auth::guard()->user()->usuario;
        $tarea = $entrega->tarea;
        $grupo = $tarea->grupo;

        if (
            ($usuario->pertenece_grupo($grupo) && $usuario->is_asesor()) ||
            ($usuario->pertenece_grupo($grupo) && $usuario->is_maestro() && $usuario->id == $tarea->maestro->id) ||
            $usuario->is_administrador()
        ) {
            return view('aula.entrega_tarea', compact(['grupo', 'tarea', 'entrega']));
        }
    }

    public function agregar_entrega(Request $request, $id) {
        $tarea = TareaGrupoInvestigacion::findOrFail($id);
        $usuario = \Auth::guard()->user()->usuario;

        if ($usuario->is_estudiante() && $usuario->pertenece_grupo($tarea->grupo)) {
            $validated_data = $request->validate([
                'descripcion' => 'required_if:file,',
                'file' => 'file|image|mimes:doc,pdf,docx,zip,png,jpeg,jpg'
            ]);

            $entrega = EntregaTareaEstudiante::where('usuario_id', $usuario->id)
                ->where('tarea_id', $tarea->id)->first();

            if (!$entrega) {
                Helper::atomic_transaction(function () use (&$entrega, $validated_data, $tarea, $usuario) {
                    $entrega = EntregaTareaEstudiante::create([
                        'tarea_id' => $tarea->id,
                        'usuario_id' => $usuario->id,
                        'descripcion' => $validated_data['descripcion'],
                    ]);

                    $file = isset($validated_data['file']) ? $validated_data['file'] : null;

                    if ($file) {
                        $filename = 'tareas/' . $tarea->id . '/entregas/' . $usuario->id . '/documento' . '.' . $file->getClientOriginalExtension();
                        Storage::disk('local')->put($filename, File::get($file));
                        $entrega::update([ 'archivo' => $filename ]);
                    }
                });
            } else {
                $entrega->update([ 'descripcion' => $validated_data['descripcion'] ]);
                $file = isset($validated_data['file']) ? $validated_data['file'] : null;

                if ($file) {
                    $filename = 'tareas/' . $tarea->id . '/entregas/' . $usuario->id . '/documento' . '.' . $file->getClientOriginalExtension();
                    Storage::disk('local')->put($filename, File::get($file));
                    $entrega::update([ 'archivo' => $filename ]);
                }
            }
            return redirect()
                ->route('aula.ver-tarea', $tarea->id)
                ->with('success', 'Se ha agregado la entrega a la tarea');
        }
        abort(404, 'Página no encontrada');
    }
}