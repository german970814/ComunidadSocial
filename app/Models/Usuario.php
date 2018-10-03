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

    public function agregar_solicitud(\App\Models\SolicitudAmistad $solicitud) {
        $this->solicitudes()->attach($solicitud->id);
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
