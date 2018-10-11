<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function usuario() {
        return $this->hasOne('App\Models\Usuario');
    }

    public function is_estudiante() {
        return $this->usuario->is_estudiante();
    }

    public function is_maestro() {
        return $this->usuario->is_maestro();
    }

    public function is_administrador() {
        return $this->usuario->is_administrador();
    }

    public function is_asesor() {
        return $this->usuario->is_asesor();
    }

    public function is_institucion() {
        return $this->usuario->is_institucion();
    }
}
