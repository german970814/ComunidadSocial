<script>
    @if (($loggedUser = Auth::guard()->user()) && Auth::check())
    ;(function() {
        if (!!window.EventSource) {
            let eventSource = new EventSource('{{ route("notificaciones", $loggedUser->usuario->id) }}');
    
            eventSource.addEventListener('message', event => {
                let data = JSON.parse(event.data.replace('\\n\\n\\n<!DOCTYPE html><!--', ''));
                if (data.notificaciones.new) {
                    let { notificacion } = data.notificaciones;
                    let value = parseInt($('#notificaciones-pendientes').html());
                    $('#notificaciones-pendientes').removeClass('hidden').html(++value);
                    $('#notificacion-dropdown').children().first().after(`
                        <li data-id="${ notificacion.id }" class="notificacion-item notificacion-pendiente">
                            ${ notificacion.render }
                            <span class="cancel"><i class="fa fa-close" aria-hidden="true"></i></span>
                        </li>
                    `);
                }
            }, false);
    
            eventSource.addEventListener('error', event => {
                if (event.readyState == EventSource.CLOSED) {
                    console.log('Event was closed');
                    console.log(EventSource);
                }
            }, false);
    
            eventSource.onerror = function (e) {
                if (event.readyState == EventSource.CLOSED) {
                    console.log('Event was closed');
                    console.log(EventSource);
                }
            }
        }
    });
    @endif

    window._app_config = {
        routes: {
            "mostrarUsuario": "{{ route('usuario.show', 99) }}",
            "aceptarSolicitudAmistad": "{{ route('usuario.aceptar-solicitud-amistad', 99) }}",
            "solicitarAmistad": "{{ route('usuario.solicitar-amistad', 99) }}",
            "leerNotificacion": "{{ route('notificacion.leer', 99) }}",
            "comentarPost": "{{ route('post.comment', 99) }}",
            "likePost": "{{ route('post.like', 99) }}",
            "detallePost": "{{ route('post.show', 99) }}",
            "reportarComentario": "{{ route('comentario.reportar', 99) }}",
            "departamentoMunicipios": "{{ route('departamento.municipios', 99) }}",
            "solicitarIngresoInstitucion": "{{ route('institucion.solicitud-ingreso-institucion', 99) }}",
            "getConversacion": "{{ route('mensajes.get-conversacion', 99) }}",
            "guardarMensaje": "{{ route('mensajes.guardar-mensaje', 99) }}",
            "verConversacion": "{{ route('mensajes.ver-conversacion', 99) }}",
        },
        messages: {
            @if($session_message_success = Session::get('success'))
            'success': '{{ $session_message_success }}',
            @endif
            @if($session_message_error = Session::get('error'))
            'danger': '{{ $session_message_error }}',
            @endif
            @if($session_message_info = Session::get('info'))
            'info': '{{ $session_message_info }}',
            @endif
            @if($session_message_info = Session::get('warning'))
            'warning': '{{ $session_message_info }}',
            @endif
        },
        server: {
            @auth
                "loggedUserId": {{ Auth::guard()->user()->usuario->id }},
                "loggedUserFullName": "{{ Auth::guard()->user()->usuario->get_full_name() }}",
                @if (isset($usuario) && $usuario->id)
                    "usuarioId": "{{ $usuario->id }}",
                    "usuarioFullName": "{{ $usuario->get_full_name() }}",
                @endif
            @endauth
        }
    }
</script>
<script src="{{ asset('assets/plugins/jquery/jquery-min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/plugins/rs-plugin/js/jquery.themepunch.tools.min.js') }}"></script>
<script src="{{ asset('assets/plugins/rs-plugin/js/jquery.themepunch.revolution.min.js') }}"></script>
<script src="{{ asset('assets/plugins/selectbox/jquery.selectbox-0.1.3.min.js') }}"></script>
<script src="{{ asset('assets/plugins/owl-carousel/owl.carousel.js') }}"></script>
<script src="{{ asset('assets/plugins/waypoint/waypoints.min.js') }}"></script>
<script src="{{ asset('assets/plugins/counter-up/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('assets/plugins/isotope/isotope.min.js') }}"></script>
<script src="{{ asset('assets/plugins/fancybox/jquery.fancybox.min.js') }}"></script>
<script src="{{ asset('assets/plugins/isotope/isotope-triger.js') }}"></script>
<script src="{{ asset('assets/plugins/countdown/jquery.syotimer.js') }}"></script>
<script src="{{ asset('assets/plugins/velocity/velocity.min.js') }}"></script>
<script src="{{ asset('assets/plugins/smoothscroll/SmoothScroll.js') }}"></script>
<script src="{{ asset('assets/plugins/wow/wow.min.js') }}"></script>
<script src="{{ asset('assets/plugins/notify/bootstrap.notify.js') }}"></script>
<script src="{{ asset('assets/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datetimepicker/datetimepicker.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>
<script src="{{ asset('/js/custom.js') }}"></script>
<script src="{{ asset('assets/plugins/vue/vue.js') }}"></script>

@if (Auth::check() && (isset($show_chat) ? $show_chat : true))
<script src="{{ asset('/js/io.js') }}"></script>
<script src="{{ asset('/js/client.js') }}"></script>
@endif

@if (!Auth::check())
    @if(!(Request::is('login')) && !(Request::is('usuario/create')))
        @if ($errors->has('email'))
        <script>
            $(document).ready(function () {
                $('#modalLogin').click();
            })
        </script>
        @endif
    @endif
@endif

@yield('custom_script')
