<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use App\User;

class Usuario extends Model
{
    protected $fillable = [
        'nombres', 'apellidos', 'sexo', 'user_id', 'tipo_documento',
        'numero_documento', 'tipo_usuario', 'grupo_etnico'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }

    // public function amigos() {
    //     return $this->belongsToMany('Usuario', 'amigos', 'usuario_id', 'amigo_id');
    // }

    public function solicitudes() {
        return $this->belongsToMany(
            '\App\Models\SolicitudAmistad',
            'solicitudes_usuario',
            'usuario_id',
            'solicitud_id'
        );
    }

    public function agregar_solicitud(\App\Models\SolicitudAmistad $solicitud) {
        $this->solicitudes()->attach($solicitud->id);
    }

    public function get_full_name() {
        return $this->nombres . ' ' . $this->apellidos;
    }

    public function add_amigo(\App\Models\Usuario $usuario) {
        $this->amigos()->attach($usuario->id);
    }

    public static function create_user($data) {
        $name = $data['nombres'] . ' ' . $data['apellidos'];
        return User::create([
            'name' => $name,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
