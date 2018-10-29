<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\GrupoInvestigacion;
use App\Models\TareaGrupoInvestigacion;
use App\Models\ExamenGrupoInvestigacion;
use App\Models\EntregaTareaEstudiante;
use App\Libraries\{ Form, Helper, Permissions };

class AulaVirtualController extends Controller  // TODO: Validar el asesor sea el asesor del grupo
{
    private $messages = [
        'required' => 'Este campo es requerido'
    ];

    public function ver_tarea($id) {
        $tarea = TareaGrupoInvestigacion::findOrFail($id);
        $user = \Auth::guard()->user();
        $grupo = $tarea->grupo;
        $entrega = null;

        if (Permissions::has_perm('ver_tarea', ['tarea' => $tarea])) {
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

        if (Permissions::has_perm('ver_tareas', ['grupo' => $grupo])) {
            return view('aula.tareas_grupo', compact(['grupo']));
        }
        abort(404, 'Página no encontrada');
    }

    public function crear_tarea($id) {
        $grupo = GrupoInvestigacion::findOrFail($id);
        $user = \Auth::guard()->user();

        if (Permissions::has_perm('crear_tarea', ['grupo' => $grupo])) {
            $form = new Form(TareaGrupoInvestigacion::class, [
                'titulo', 'fecha_inicio', 'fecha_fin', 'descripcion'
            ]);
            return view('aula.crear_tarea_grupo', compact(['grupo', 'form']));
        }
        abort(404, 'Página no encontrada');
    }

    public function editar_tarea($id) {
        $tarea = TareaGrupoInvestigacion::findOrFail($id);
        $usuario = \Auth::guard()->user()->usuario;
        $grupo = $tarea->grupo;

        if (Permissions::has_perm('editar_tarea', ['tarea' => $tarea])) {
            $form = new Form($tarea, [
                'titulo', 'fecha_inicio', 'fecha_fin', 'descripcion'
            ]);
            return view('aula.crear_tarea_grupo', compact(['grupo', 'form', 'tarea']));
        }
        abort(404, 'Página no encontrada');
    }

    public function actualizar_tarea(Request $request, $id) {
        $tarea = TareaGrupoInvestigacion::findOrFail($id);
        $usuario = \Auth::guard()->user()->usuario;
        $grupo = $tarea->grupo;

        if (Permissions::has_perm('editar_tarea', ['tarea' => $tarea])) {
            $validated_data = $request->validate([
                'descripcion' => '',
                'titulo' => 'required',
                'fecha_fin' => 'required|date|after:fecha_inicio',
                'fecha_inicio' => 'required|date|before:fecha_fin',
                'files.*' => 'file|mimes:doc,pdf,docx,zip,png,jpeg,jpg,ppt,pptx'
            ], $this->messages);

            $tarea->update([
                'titulo' => $validated_data['titulo'],
                'fecha_fin' => $validated_data['fecha_fin'],
                'descripcion' => $validated_data['descripcion'],
                'fecha_inicio' => $validated_data['fecha_inicio'],
            ]);

            $files = isset($validated_data['files']) ? $validated_data['files'] : [];
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
                ->route('aula.editar-tarea', $tarea->id)
                ->with('success', 'Se ha editado la tarea');
        }
        abort(404, 'Página no encontrada');
    }

    public function guardar_tarea(Request $request, $id) {
        $grupo = GrupoInvestigacion::findOrFail($id);
        $usuario = \Auth::guard()->user()->usuario;

        if (Permissions::has_perm('crear_tarea', ['grupo' => $grupo])) {
            $validated_data = $request->validate([
                'descripcion' => '',
                'titulo' => 'required',
                'fecha_fin' => 'required|date|after:fecha_inicio',
                'fecha_inicio' => 'required|date|before:fecha_fin',
                'files.*' => 'file|mimes:doc,pdf,docx,zip,png,jpeg,jpg,ppt,pptx'
            ], $this->messages);

            $tarea = TareaGrupoInvestigacion::create([
                'maestro_id' => $usuario->id,
                'titulo' => $validated_data['titulo'],
                'grupo_investigacion_id' => $grupo->id,
                'fecha_fin' => $validated_data['fecha_fin'],
                'descripcion' => $validated_data['descripcion'],
                'fecha_inicio' => $validated_data['fecha_inicio'],
            ]);

            $files = isset($validated_data['files']) ? $validated_data['files'] : [];
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

        if (Permissions::has_perm('editar_tarea', ['tarea' => $tarea])) {
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

        if (Permissions::has_perm('editar_tarea', ['tarea' => $tarea])) {
            return view('aula.entrega_tarea', compact(['grupo', 'tarea', 'entrega']));
        }
    }

    public function get_documento($id) {
        $documento = \App\Models\DocumentoTareaGrupoInvestigacion::findOrFail($id);

        if (Storage::disk('local')->has($documento->archivo)) {
            $file = Storage::disk('local')->get($documento->archivo);
            $content_type = Helper::get_content_type($documento->archivo);

            return response($file, 200)
                ->header('Content-Type', $content_type)
                ->header('Content-Disposition', 'attachment; filename="' . $documento->get_nombre() . '"');
        }
        abort(404, 'Página no encontrada');
    }

    public function eliminar_documento($id) {
        $documento = \App\Models\DocumentoTareaGrupoInvestigacion::findOrFail($id);
        $tarea = $documento->tarea;
        $grupo = $tarea->grupo;
        $usuario = \Auth::guard()->user()->usuario;

        if (Permissions::has_perm('editar_tarea', ['tarea' => $tarea])) {
            $documento->delete();
            return back()->with('info', 'Ha eliminado el documento');
        }
        return back()->with('warning', 'No tiene permisos de eliminar este documento');
    }

    public function get_documento_entrega($id) {
        $entrega = EntregaTareaEstudiante::findOrFail($id);

        if (Storage::disk('local')->has($entrega->archivo)) {
            $file = Storage::disk('local')->get($entrega->archivo);
            $content_type = Helper::get_content_type($entrega->archivo);

            return response($file, 200)
                ->header('Content-Type', $content_type)
                ->header('Content-Disposition', 'attachment; filename="entrega_' . $entrega->usuario->get_full_name() . '"');
        }
        abort(404, 'Página no encontrada');
    }

    public function agregar_entrega(Request $request, $id) {
        $tarea = TareaGrupoInvestigacion::findOrFail($id);
        $usuario = \Auth::guard()->user()->usuario;

        if (Permissions::has_perm('ver_tarea', ['tarea' => $tarea])) {
            $validated_data = $request->validate([
                'descripcion' => 'required_if:file,',
                'file' => 'file|mimes:doc,pdf,docx,zip,png,jpeg,jpg,ppt,pptx'
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
                        $entrega->update([ 'archivo' => $filename ]);
                    }
                });
            } else {
                $entrega->update([ 'descripcion' => $validated_data['descripcion'] ]);
                $file = isset($validated_data['file']) ? $validated_data['file'] : null;

                if ($file) {
                    $filename = 'tareas/' . $tarea->id . '/entregas/' . $usuario->id . '/documento' . '.' . $file->getClientOriginalExtension();
                    Storage::disk('local')->put($filename, File::get($file));
                    $entrega->update([ 'archivo' => $filename ]);
                }
            }
            return redirect()
                ->route('aula.ver-tarea', $tarea->id)
                ->with('success', 'Se ha agregado la entrega a la tarea');
        }
        abort(404, 'Página no encontrada');
    }

    public function crear_examen($id) {
        $grupo = GrupoInvestigacion::findOrFail($id);
        if (Permissions::has_perm('crear_examen', ['grupo' => $grupo])) {
            $form = new Form(ExamenGrupoInvestigacion::class, [
                'titulo', 'fecha_inicio', 'fecha_fin',
                'descripcion', 'duracion', 'preguntas'
            ]);
            return view('aula.crear_examen_grupo', compact(['grupo', 'form']));
        }
        abort(404, 'Página no encontrada');
    }

    public function listar_examenes_grupo($id) {
        $grupo = GrupoInvestigacion::findOrFail($id);

        if (Permissions::has_perm('ver_examenes', ['grupo' => $grupo])) {
            return view('aula.examenes_grupo', compact(['grupo']));
        }
        abort(404, 'Página no encontrada');
    }

    public function editar_examen($id) {
        $examen = ExamenGrupoInvestigacion::findOrFail($id);
        $grupo = $examen->grupo;

        if (Permissions::has_perm('editar_examen', ['examen' => $examen])) {
            $form = new Form($examen, [
                'titulo', 'fecha_inicio', 'fecha_fin',
                'descripcion', 'duracion', 'preguntas'
            ]);
            return view('aula.crear_examen_grupo', compact(['grupo', 'form', 'examen']));
        }
        abort(404, 'Página no encontrada');
    }

    public function actualizar_examen(Request $request, $id) {
        $examen = ExamenGrupoInvestigacion::findOrFail($id);

        if (Permissions::has_perm('editar_examen', ['examen' => $examen])) {
            $grupo = $examen->grupo;
            $validation_data = $request->validate([
                'descripcion' => '',
                'titulo' => 'required',
                'duracion' => 'required',
                'preguntas' => 'required',
                'fecha_fin' => 'required|date|after:fecha_inicio',
                'fecha_inicio' => 'required|date|before:fecha_fin',
            ], $this->messages);

            $examen->update([
                'titulo' => $validation_data['titulo'],
                'duracion' => $validation_data['duracion'],
                'preguntas' => $validation_data['preguntas'],
                'fecha_fin' => $validation_data['fecha_fin'],
                'descripcion' => $validation_data['descripcion'],
                'fecha_inicio' => $validation_data['fecha_inicio'],
            ]);
            return redirect()
                ->route('aula.editar-examen', $examen->id)
                ->with('success', 'Examen editado exitosamente');
        }
        abort(404, 'Página no encontrada');
    }

    public function guardar_examen(Request $request, $id) {
        $grupo = GrupoInvestigacion::findOrFail($id);
        $usuario = \Auth::guard()->user()->usuario;

        if (Permissions::has_perm('crear_examen', ['grupo' => $grupo])) {
            $validation_data = $request->validate([
                'descripcion' => '',
                'titulo' => 'required',
                'duracion' => 'required',
                'preguntas' => 'required',
                'fecha_fin' => 'required|date|after:fecha_inicio',
                'fecha_inicio' => 'required|date|before:fecha_fin',
            ], $this->messages);

            $examen = ExamenGrupoInvestigacion::create([
                'maestro_id' => $usuario->id,
                'titulo' => $validation_data['titulo'],
                'grupo_investigacion_id' => $grupo->id,
                'duracion' => $validation_data['duracion'],
                'preguntas' => $validation_data['preguntas'],
                'fecha_fin' => $validation_data['fecha_fin'],
                'descripcion' => $validation_data['descripcion'],
                'fecha_inicio' => $validation_data['fecha_inicio'],
            ]);

            return redirect()
                ->route('aula.ver-examen', $examen->id)
                ->with('success', 'Ha creado el examen con exito');
        }
        abort(404, 'Página no encontrada');
    }

    public function ver_examen($id) {
        $examen = ExamenGrupoInvestigacion::findOrFail($id);
        $grupo = $examen->grupo;

        if (Permissions::has_perm('ver_examen', ['examen' => $examen])) {
            return view('aula.ver_examen', compact(['grupo', 'examen']));
        }
        abort(404, 'Página no encontrada');
    }

    /**
     * Vista en la que el estudiante hace la prueba del examen
     */
    public function examen_estudiante($id) {
        $examen = ExamenGrupoInvestigacion::findOrFail($id);
        $usuario = \Auth::guard()->user()->usuario;

        if (Permissions::has_perm('ver_examen', ['examen' => $examen])) {
            if ($examen->is_activo()) {
                $entrega = \App\Models\EntregaExamenEstudiante::firstOrCreate([
                    'usuario_id' => $usuario->id,
                    'examen_id' => $examen->id
                ], ['respuestas' => '[]']);

                if ($entrega->is_editable()) {
                    return view('aula.examen_estudiante', compact(['entrega', 'examen']));
                }
            }
            return redirect()
                ->route('aula.ver-examen', $examen->id)
                ->with('warning', 'El examen ya se ha terminado');
        }
        abort(404, 'Página no encontrada');
    }

    public function cerrar_examen($id) {
        $entrega = \App\Models\EntregaExamenEstudiante::findOrFail($id);
        $usuario = \Auth::guard()->user()->usuario;

        if (Permissions::has_perm('ver_examen', ['examen' => $entrega->examen])) {
            if ($entrega->is_editable()) {
                $entrega->update(['cerrada' => true]);
                return redirect()->route('aula.ver-examen', $entrega->examen->id)
                    ->with('success', 'Examen entregado con exito');
            }
        }
        return redirect()->route('aula.ver-examen', $entrega->examen->id)
            ->with('warning', 'El examen ya se cerró');
    }

    /**
     * 
     * Para las respuestas múltiples, solo se está teniendo en cuenta
     * que tan solo una opción sea correcta para marcar la pregunta
     * como respondida acertivamente
     */
    public function entregas_examen($id) {
        $examen = ExamenGrupoInvestigacion::findOrFail($id);
        $grupo = $examen->grupo;

        if (Permissions::has_perm('editar_examen', ['examen' => $examen])) {
            $preguntas = json_decode($examen->preguntas);
            return view('aula.entregas_examen', compact(['examen', 'entregas', 'grupo', 'preguntas']));
        }
        abort(404, 'Página no encontrada');
    }

    public function entrega_examen($id) {
        $entrega = \App\Models\EntregaExamenEstudiante::findOrFail($id);
        
        if (Permissions::has_perm('editar_examen', ['examen' => $entrega->examen])) {
            $examen = $entrega->examen;
            $grupo = $examen->grupo;
            $preguntas = json_decode($examen->preguntas);
            return view('aula.entrega_examen', compact('examen', 'grupo', 'entrega', 'preguntas'));
        }
        abort(404, 'Página no encontrada');
    }

    /**
     * @return JsonResponse
     */
    public function guardar_respuesta_estudiante(Request $request, $id) {
        $entrega = \App\Models\EntregaExamenEstudiante::findOrFail($id);
        $usuario = \Auth::guard()->user()->usuario;

        if ($entrega->is_editable() && $entrega->usuario->id == $usuario->id) {
            $validated_data = $request->validate([
                'respuestas' => 'required'
            ]);
            $entrega->update([
                'respuestas' => $validated_data['respuestas']
            ]);
            return response()->json([
                'code' => 200,
                'message' => 'Respuesta actualizada con éxito'
            ]);
        }
        abort(404, 'Página no encontrada');
    }
}