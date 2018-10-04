<?php

namespace App\Http\Middleware;

use Closure;

class Notificacion
{
    private $notificaciones_pendientes = null;
    private $notificaciones = null;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = \Auth::user();
        if (\Auth::check()) {
            $notificaciones = function () use ($user) {  // make lazy
                if ($this->notificaciones === null) {
                    $this->notificaciones = \App\Models\Notificacion::where('usuario_id', $user->usuario->id)
                        ->orderBy('created_at', 'desc')
                        ->get();
                }
                return $this->notificaciones;
            };

            $notificaciones_pendientes = function () use ($user) {
                if ($this->notificaciones_pendientes === null) {
                    $this->notificaciones_pendientes = \App\Models\Notificacion::where('usuario_id', $user->usuario->id)
                        ->where('leida', false)->count();
                }
                return $this->notificaciones_pendientes;
            };
            view()->share('notificaciones', Closure::bind($notificaciones, $this));
            view()->share('notificaciones_pendientes', Closure::bind($notificaciones_pendientes, $this));
        }
        return $next($request);
    }
}
