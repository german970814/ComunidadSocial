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

    static $form_schema = [
        'email' => [
            'type' => 'email',
            'validators' => 'email'
        ],
        'password' => [
            'type' => 'password',
            'label' => 'Contraseña'
        ],
        'sexo' => [
            'type' => 'select',
            'options' => [
                'M' => 'Masculino',
                'F' => 'Femenino'
            ]
        ],
        'tipo_documento' => [
            'label' => 'Tipo de documento',
            'type' => 'select',
            'options' => [
                'CC' => 'Cedula de ciudadanía',
                'TI' => 'Tarjeta de identidad',
                'RG' => 'Registro civil',
                'NES' => 'Número establecido por la secretaría',
                'NIP' => 'Número de identificación personal',
                'NUIP' => 'Número único de identificación personal'
            ]
        ],
        'numero_documento' => [
            'type' => 'number',
        ],
        'grupo_etnico' => [
            'type' => 'select',
            'options' => [
                'IN' => 'Indigenas',
                'AF' => 'Afrocolombianos',
                'RO' => 'ROM',
                'NI' => 'Ninguno'
            ]
        ]
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function solicitudes() {
        return $this->belongsToMany(
            '\App\Models\SolicitudAmistad',
            'solicitudes_usuario',
            'usuario_id',
            'solicitud_id'
        );
    }

    public function notificaciones() {
        return $this->hasMany('App\Models\Notification');
    }

    public function amigos() {
        $amigos_enviaron_solicitud_query = \DB::table('usuarios')
            ->join('solicitudes_usuario', 'solicitudes_usuario.usuario_id', 'usuarios.id')
            ->join('solicitudes', 'solicitudes.id', 'solicitudes_usuario.solicitud_id')
            ->where('solicitudes.aceptada', true)
            ->where('usuarios.id', $this->id)
            ->select('solicitudes.usuario_id as id')
            ->get();

        $amigos_self_solicitud_query = \DB::table('usuarios')
            ->join('solicitudes_usuario', 'solicitudes_usuario.usuario_id', 'usuarios.id')
            ->join('solicitudes', 'solicitudes.id', 'solicitudes_usuario.solicitud_id')
            ->where('solicitudes.aceptada', true)
            ->where('solicitudes.usuario_id', $this->id)
            ->select('solicitudes_usuario.usuario_id as id')
            ->get();

        $amigos_enviaron_solicitud = collect($amigos_enviaron_solicitud_query->all())->map(function ($result) {
            return $result->id;
        });
        $amigos_self_solicitud = collect($amigos_self_solicitud_query->all())->map(function ($result) {
            return $result->id;
        });

        return Usuario::find($amigos_enviaron_solicitud->merge($amigos_self_solicitud)->all());
    }

    public function is_amigo(Usuario $usuario) {
        $query_1 = \DB::table('usuarios')
            ->join('solicitudes_usuario', 'solicitudes_usuario.usuario_id', 'usuarios.id')
            ->join('solicitudes', 'solicitudes.id', 'solicitudes_usuario.solicitud_id')
            ->where('solicitudes.aceptada', true)
            ->where('usuarios.id', $this->id)
            ->where('solicitudes.usuario_id', $usuario->id)
            ->exists();
        $query_2 = \DB::table('usuarios')
            ->join('solicitudes_usuario', 'solicitudes_usuario.usuario_id', 'usuarios.id')
            ->join('solicitudes', 'solicitudes.id', 'solicitudes_usuario.solicitud_id')
            ->where('solicitudes.aceptada', true)
            ->where('solicitudes.usuario_id', $this->id)
            ->where('solicitudes_usuario.usuario_id', $usuario->id)
            ->exists();
        return $query_1 || $query_2;
    }

    public function agregar_solicitud(\App\Models\SolicitudAmistad $solicitud) {
        $this->solicitudes()->attach($solicitud->id);
    }

    public function get_profile_url() {
        return route('usuario.show', $this->id);
    }

    public function get_full_name() {
        return $this->nombres . ' ' . $this->apellidos;
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
