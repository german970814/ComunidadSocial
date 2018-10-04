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
