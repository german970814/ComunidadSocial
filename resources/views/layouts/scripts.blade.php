<script>
    ;(function() {
        {{-- let eventSource = new EventSource('{{ route("notificaciones", $usuario->id) }}'); --}}

        eventSource.addEventListener('message', event => {
            let data = JSON.parse(event.data.replace('\\n\\n\\n<!DOCTYPE html><!--', ''));
            console.log(data);
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
    });

    window._app_config = {
        routes: {
            "mostrarUsuario": "{{ route('usuario.show', 99) }}",
            "aceptarSolicitudAmistad": "{{ route('usuario.aceptar-solicitud-amistad', 99) }}",
            "solicitarAmistad": "{{ route('usuario.solicitar-amistad', 99) }}",
            "leerNotificacion": "{{ route('notificacion.leer', 99) }}",
            "comentarPost": "{{ route('post.comment', 99) }}",
            "likePost": "{{ route('post.like', 99) }}",
            "detallePost": "{{ route('post.show', 99) }}",
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
<script src="{{ asset('assets/js/app.js') }}"></script>
<script src="{{ asset('/js/custom.js') }}"></script>

@yield('custom_script')
