@extends('layouts/base')

@section('content')
    <section class="mainContent">
        <div class="container">
            <div class="row">
                <div class="col-md-9 col-sm-8 col-xs-12 block pull-right">
                    @yield('section')
                </div>
                <div class="col-md-3 col-sm-4 col-xs-12 pull-left">
                    @include('usuarios.sidebar')
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom_script')
<script>
    $(document).ready(function () {
        $('a.solicitud-amistad').on('click', function () {
            $.ajax({
                method: 'GET',
                url: "{{ route('usuario.solicitar-amistad', $usuario->id) }}",
                success: (data) => {
                    if (data.code === 200) {
                        $.notify({message: data.message}, {type: 'success'})
                    }
                }
            })
        })
    })
</script>
@endsection