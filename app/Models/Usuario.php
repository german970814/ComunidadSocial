<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Libraries\{ ModelForm };
use App\User;

class Usuario extends ModelForm  // TODO: Implements CacheMethods traits
{
    /**
     * Nombre de la tabla
     * 
     * @var string
     */
    protected $table = 'usuarios';

    /**
     * Campos que se pueden llenar con el metodo create, update
     * 
     * @var array
     */
    protected $fillable = [
        'nombres', 'apellidos', 'sexo', 'user_id', 'tipo_documento',
        'numero_documento', 'tipo_usuario', 'grupo_etnico', 'fecha_nacimiento',
        'profile_photo'
    ];

    /**
     * Opciones de sexo
     * 
     * @var array
     */
    static $sexo_opciones = [
        'M' => 'Masculino',
        'F' => 'Femenino'
    ];

    /**
     * Opciones de tipo de documento
     * 
     * @var array
     */
    static $tipo_documento_opciones = [
        'CC' => 'Cedula de ciudadanía',
        'TI' => 'Tarjeta de identidad',
        'RG' => 'Registro civil',
        'NES' => 'Número establecido por la secretaría',
        'NIP' => 'Número de identificación personal',
        'NUIP' => 'Número único de identificación personal'
    ];

    /**
     * Opciones de grupo etnico
     * 
     * @var array
     */
    static $grupo_etnico_opciones = [
        'IN' => 'Indigenas',
        'AF' => 'Afrocolombianos',
        'RO' => 'ROM',
        'NI' => 'Ninguno'
    ];

    /**
     * Schema para \App\Liraries\Form
     * 
     * @var array
     */
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
            'label' => 'Género'
        ],
        'tipo_documento' => [
            'label' => 'Tipo de documento'
        ],
        'numero_documento' => [
            'type' => 'number',
        ]
    ];

    /**
     * Tipo usuario constantes
     */
    static $ASESOR = 'S';
    static $MAESTRO = 'M';
    static $ESTUDIANTE = 'E';
    static $INSTITUCION = 'I';
    static $ADMINISTRADOR = 'A';

    /**
     * Laravel User
     */
    public function user() {
        return $this->belongsTo('App\User');
    }

    /**
     * Solicitudes de amistad de usuario
     */
    public function solicitudes() {
        return $this->belongsToMany(
            '\App\Models\SolicitudAmistad',
            'solicitudes_usuario',
            'usuario_id',
            'solicitud_id'
        );
    }

    public function solicitudes_amistad_pendientes() {
        return $this->solicitudes()->where('aceptada', false)->get()->all();
    }

    /**
     * Notificaciones del usuario
     */
    public function notificaciones() {
        return $this->hasMany('App\Models\Notification');
    }

    /**
     * Amigos del usuario
     */
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

    public function amigos_ids() {
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

        return $amigos_enviaron_solicitud->merge($amigos_self_solicitud)->all();
    }

    /**
     * Método para saber si un usuario es amigo
     */
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

    /**
     * Posts que le han publicado al usuario
     */
    public function posts() {
        return $this->hasMany('\App\Models\Post', 'usuario_destino_id')
            ->where('posts.estado', \App\Models\Post::$ACTIVO);
    }

    /**
     * Feed
     * 
     * @return
     */
    public function feed() {
        return $this->posts()->orderBy('created_at', 'desc');
    }

    /**
     * Si es usuario institucion
     * 
     * @return
     */
    public function institucion() {
        if ($this->tipo_usuario === self::$INSTITUCION) {
            return $this->hasOne('\App\Models\Institucion');
        }
        return null;
    }

    /**
     * Método para saber si al usuario le gusta un post específico
     * 
     * @return boolean
     */
    public function likes_post(\App\Models\Post $post) {
        return \DB::table('comentarios_posts')
            ->where('usuario_id', $this->id)
            ->where('post_id', $post->id)
            ->where('like', true)
            ->exists();
    }

    /**
     * Relación inversa para guardar solicitudes del usuario
     * 
     * @return void
     */
    public function agregar_solicitud(\App\Models\SolicitudAmistad $solicitud) {
        $this->solicitudes()->attach($solicitud->id);
    }

    /**
     * Método para saber si un usuario no ha respondido a la solicitud de amistad
     * 
     * @return boolean
     */
    public function solicitud_amistad_enviada(Usuario $usuario) {
        // Solicitudes enviadas por el usuario
        return \DB::table('solicitudes_usuario')
            ->join('solicitudes', 'solicitudes_usuario.solicitud_id', 'solicitudes.id')
            ->where('solicitudes.usuario_id', $this->id)
            ->where('solicitudes.aceptada', false)
            ->where('solicitudes_usuario.usuario_id', $usuario->id)
            ->exists();
    }

    /**
     * Método para saber si el usuario no ha respondido a una solicitud de amistad
     * 
     * @return boolean
     */
    public function no_confirma_solicitud(Usuario $usuario) {
        return $this->solicitudes()
            ->where('solicitudes.usuario_id', $usuario->id)
            ->where('aceptada', false)
            ->exists();
    }

    /**
     * Método para obtener la url del perfil del usuario
     * 
     * @return string
     */
    public function get_profile_url() {
        return route('usuario.show', $this->id);
    }

    /**
     * Método para obtener la url de la imagen de perfil del usuario
     * 
     * @return string
     */
    public function get_profile_photo_url() {
        if (Storage::disk('local')->has($this->profile_photo)) {
            return route('usuario.profile-photo', $this->id);
        }
        return asset('assets/img/user.png');
    }

    /**
     * Método para obtener el nombre del usuario
     * 
     * @return string
     */
    public function get_full_name() {
        if ($this->is_institucion()) {
            return $this->nombres;
        }
        return $this->nombres . ' ' . $this->apellidos;
    }

    /**
     * Método estático para crear usuarios de laravel a partir
     * de los datos del usuario
     * 
     * @param Array $data
     * 
     * @return \App\User
     */
    public static function create_user($data) {
        $name = $data['nombres'] . ' ' . $data['apellidos'];
        return User::create([
            'name' => $name,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Institución a la que pertenece
     * 
     * @return \App\Models\Institución
     * @return null
     */
    public function institucion_pertenece() {
        if ($this->is_maestro() || $this->is_estudiante()) {
            $solicitud = SolicitudInstitucion::where('usuario_id', $this->id)
                ->where('aceptada', true)
                ->first();
            if ($solicitud) {
                return $solicitud->institucion;
            }
        }
        return null;
    }

    /**
     * Método para saber si un usuario espera respuesta de unirse a una institución
     * 
     * @return bool
     */
    public function espera_respuesta_institucion($institucion) {  // TODO: Cambiar a consultar el modelo SolicitudInsitucion
        if ($this->is_maestro() || $this->is_estudiante()) {
            return Institucion::join('solicitudes_institucion', 'instituciones.id', 'solicitudes_institucion.institucion_id')
                ->where('solicitudes_institucion.usuario_id', $this->id)
                ->where('solicitudes_institucion.institucion_id', $institucion->id)
                ->where('aceptada', false)
                ->exists();
        }
        return false;
    }

    /**
     * Método para saber si un usuario espera respuesta de unirse a un grupo de investigación
     */
    public function espera_respuesta_grupo($grupo) {
        if ($this->is_maestro() || $this->is_estudiante()) {
            return SolicitudGrupoInvestigacion::where('grupo_investigacion_id', $grupo->id)
                ->where('usuario_id', $this->id)
                ->where('aceptada', false)
                ->exists();
        }
        return false;
    }

    /**
     * Obtiene la representación en texto del tipo de usuario
     */
    public function get_tipo_usuario_display() {
        if ($this->is_institucion()) {
            return 'Institución';
        }
        switch ($this->tipo_usuario){
            case self::$MAESTRO:
                return 'Maestro';
                break;
            case self::$ESTUDIANTE:
                return 'Estudiante';
                break;
            case self::$ASESOR:
                return 'Asesor';
                break;
            case self::$ADMINISTRADOR:
                return 'Administrador';
                break;
        }
    }

    /**
     * Método para obtener los grupos de investigacion a los que
     * pertenece el usuario
     */
    public function get_grupos_investigacion_pertenece() {
        $grupos_ids = \DB::table('solicitudes_grupo_investigacion')
            ->where('usuario_id', $this->id)
            ->where('aceptada', true)
            ->select('grupo_investigacion_id as id')
            ->get()->map(function ($grupo) {
                return $grupo->id;
            });
        return \App\Models\GrupoInvestigacion::find($grupos_ids->all())
            ->where('tipo', \App\Models\GrupoInvestigacion::$INVESTIGACION);
    }

    /**
     * Método para obtener las redes temáticas a los que
     * pertenece el usuario
     */
    public function get_redes_tematicas_pertenece() {
        $grupos_ids = \DB::table('solicitudes_grupo_investigacion')
            ->where('usuario_id', $this->id)
            ->where('aceptada', true)
            ->select('grupo_investigacion_id as id')
            ->get()->map(function ($grupo) {
                return $grupo->id;
            });
        return \App\Models\GrupoInvestigacion::find($grupos_ids->all())
            ->where('tipo', \App\Models\GrupoInvestigacion::$TEMATICA);
    }

    /**
     * Método para saber si un usuario pertenece a un grupo
     * de investigación
     */
    public function pertenece_grupo($grupo) {
        return \DB::table('solicitudes_grupo_investigacion')
            ->where('usuario_id', $this->id)
            ->where('grupo_investigacion_id', $grupo->id)
            ->where('aceptada', true)
            ->exists();
    }

    /**
     * Método para saber si un usuario puede unirse a un grupo
     * de investigación
     */
    public function puede_unirse_grupo($grupo) {
        if (!$this->institucion_pertenece() || $this->pertenece_grupo($grupo)) {
            return false;
        }
        return $grupo->institucion->id == $this->institucion_pertenece()->id;
    }

    public function is_estudiante() {
        return $this->tipo_usuario === self::$ESTUDIANTE;
    }

    public function is_maestro() {
        return $this->tipo_usuario === self::$MAESTRO;
    }

    public function is_administrador() {
        return $this->tipo_usuario === self::$ADMINISTRADOR;
    }

    public function is_asesor() {
        return $this->tipo_usuario === self::$ASESOR;
    }

    public function is_institucion() {
        return $this->tipo_usuario === self::$INSTITUCION && $this->institucion;
    }
}
