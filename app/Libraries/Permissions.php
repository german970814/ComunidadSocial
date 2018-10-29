<?php

namespace App\Libraries;


class Permissions
{
    use CacheMethods;

    public static function get_user() {
        return \Auth::guard()->user()->usuario;
    }

    public static function has_perm($perm) {
        $args = [];
        $user = null;
        $arg_list = func_get_args();

        for ($i = 1; $i <= count($arg_list) - 1; $i++) {
            $arg = $arg_list[$i];
            if ($arg) {
                $is_user = is_object($arg) &&
                    $arg instanceof \App\Models\Usuario;
                $user = $is_user ? $arg : $user;
                $args = $is_user ? [] : $arg;
            }
        }

        if (!$user) {
            $user = self::get_user();
        }

        return self::{$perm}($user, $args);
    }

    public static function has_perms($perms, $user=null, $args=[]) {
        if (!$user) {
            $user = self::get_user();
        }

        return array_reduce($perms, function($carry, $item) use ($user, $args) {
            if (is_bool($carry)) {
                return $carry && self::{$item}($user, $args);
            }
            return self::{$item}($user, $args);
        });
    }

    public static function estudiante($user) {
        return $user->is_estudiante();
    }

    public static function maestro($user) {
        return $user->is_maestro();
    }

    public static function administrador($user) {
        return $user->is_administrador();
    }

    public static function asesor($user, $args) {
        $grupo = isset($args['grupo']) ? $args['grupo'] : null;
        return $user->is_asesor($grupo);
    }

    public static function institucion($user) {
        return $user->is_institucion();
    }

    /**
     * Puede ver mensajes si el usuario es administrador, maestro o
     * estudiante
     */
    public static function ver_mensajes($user) {
        $same_user = $user->id == \Auth::guard()->user()->usuario->id;
        return $same_user && (
            $user->is_estudiante() ||
            $user->is_administrador() ||
            $user->is_maestro()
        );
    }

    /**
     * Se puede ver las solicitudes de un grupo si es administrador
     * o si es un asesor de grupo
     */
    public static function ver_solicitudes_grupo($user, $args) {
        $grupo = $args['grupo'];
        return $user->is_administrador() || $user->is_asesor($grupo);
    }

    /**
     * Se puede crear un foro si es administrador, asesor, o
     * si perteneces al grupo
     */
    public static function crear_foro($user, $args) {
        $grupo = $args['grupo'];
        $pertenece = $user->pertenece_grupo($grupo);
        return $user->is_administrador() || $user->is_asesor($grupo) || $pertenece;
    }

    /**
     * Se puede editar un foro si eres administrador, asesor,
     * o si creaste el foro
     */
    public static function editar_foro($user, $args) {
        $foro = $args['foro'];
        $grupo = $foro->grupo;
        return $user->is_administrador() ||
            $user->is_asesor($grupo) ||
            (
                $user->pertenece_grupo($grupo) &&
                $user->id == $foro->usuario->id
            );
    }

    /**
     * Se puede ver un foro si eres administrador,
     * asesor, o perteneces a un grupo
     */
    public static function ver_foro($user, $args) {
        $foro = $args['foro'];

        return $user->is_administrador() ||
            $user->is_asesor($foro->grupo) ||
            $user->pertenece_grupo($foro->grupo);
    }

    public static function ver_foros($user, $args) {
        $grupo = $args['grupo'];

        return $user->is_administrador() ||
            $user->is_asesor($grupo) ||
            $user->pertenece_grupo($grupo);
    }

    /**
     * Se puede participar en un foro si eres administrador,
     * asesor, o perteneces a un grupo
     */
    public static function participar_foro($user, $args) {
        $foro = $args['foro'];

        return $user->is_administrador() ||
            $user->is_asesor($foro->grupo) ||
            $user->pertenece_grupo($foro->grupo);
    }

    /**
     * Se puede crear grupo si es administrador o institucion
     */
    public static function crear_grupo($user, $args) {
        $institucion = $args['institucion'];
        return $user->is_administrador() || $user->is_institucion($institucion) || $user->is_asesor();
    }

    /**
     * Se pueden editar grupos si es administrador, asesor o institucion
     */
    public static function editar_grupo($user, $args) {
        $grupo = $args['grupo'];
        $institucion = $grupo->institucion;
        return $user->is_administrador() ||
            $user->is_institucion($institucion) ||
            $user->is_asesor($grupo);
    }

    /**
     * Se pueden crear tareas si es administrador, asesor o maestro
     */
    public static function crear_tarea($user, $args) {
        $grupo = $args['grupo'];
        return $user->is_administrador() ||
            $user->is_asesor($grupo) ||
            ($user->pertenece_grupo($grupo) && $user->is_maestro());
    }

    /**
     * Se pueden editar tareas si es administrador, asesor o maestro
     */
    public static function editar_tarea($user, $args) {
        $tarea = $args['tarea'];
        $grupo = $tarea->grupo;

        return $user->is_administrador() ||
            $user->is_asesor($grupo) ||
            (
                $user->is_maestro() &&
                $tarea->maestro->id == $user->id &&
                $user->pertenece_grupo($grupo)
            );
    }

    /**
     * Se pueden ver tareas si es administrador, asesor, o si
     * perteneces al grupo
     */
    public static function ver_tareas($user, $args) {
        $grupo = $args['grupo'];
        return $user->is_administrador() ||
            $user->is_asesor($grupo) ||
            $user->pertenece_grupo($grupo);
    }

    /**
     * Se pueden ver una tarea si es administrador, asesor, o si
     * perteneces al grupo
     */
    public static function ver_tarea($user, $args) {
        $grupo = $args['tarea']->grupo;
        return $user->is_administrador() ||
            $user->is_asesor($grupo) ||
            $user->pertenece_grupo($grupo);
    }

    /**
     * Se pueden crear exámenes si es administrador, asesor o maestro
     */
    public static function crear_examen($user, $args) {
        $grupo = $args['grupo'];
        return $user->is_administrador() ||
            $user->is_asesor($grupo) ||
            ($user->pertenece_grupo($grupo) && $user->is_maestro());
    }

    /**
     * Se pueden crear exámenes si es administrador, asesor o maestro
     */
    public static function editar_examen($user, $args) {
        $examen = $args['examen'];
        $grupo = $examen->grupo;
        return $user->is_administrador() ||
            $user->is_asesor($grupo) ||
            (
                $user->is_maestro() &&
                $user->id == $examen->maestro->id &&
                $user->pertenece_grupo($grupo)
            );
    }

    /**
     * Se pueden ver examenes si eres administrador, asesor
     * o si perteneces al grupo
     */
    public static function ver_examenes($user, $args) {
        $grupo = $args['grupo'];
        return $user->is_administrador() ||
            $user->is_asesor($grupo) ||
            $user->pertenece_grupo($grupo);
    }

    /**
     * Se puede ver una tarea si eres administrador, asesor o 
     * perteneces al grupo
     */
    public static function ver_examen($user, $args) {
        $grupo = $args['examen']->grupo;
        return $user->is_administrador() ||
            $user->is_asesor($grupo) ||
            $user->pertenece_grupo($grupo);
    }

    /**
     * Se puede hacer una prueba si eres estudiante y perteneces
     * al grupo
     */
    public static function hacer_prueba($user, $args) {
        $examen = $args['examen'];
        $pertenece = $user->pertenece_grupo($examen->grupo);
        return $user->is_estudiante() && $pertenece;
    }

    public static function enviar_solicitud_institucion($user) {
        return ($user->is_estudiante() || $user->is_maestro()) && !$user->institucion_pertenece();
    }
}