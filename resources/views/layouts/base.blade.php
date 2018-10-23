<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Comunidad virtual - @yield('title')</title>

  <!-- PLUGINS CSS STYLE -->
  @include('layouts/styles')

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body class="body-wrapper">
  {{-- @include('layouts/preloader') --}}

  <div class="main-wrapper">
    @include('layouts/header')
    {{-- @include('layouts/errors') --}}

    @yield('content')

    <div class="hidden-sm hidden-md hidden-lg hidden-xl" id="sidebar">
      @yield('sidebar')
    </div>
	
    @include('layouts/footer')
    @if (Auth::check() && (isset($show_chat) ? $show_chat : true))
      @include('usuarios.amigos_activos')
    @endif
  </div>
 
  @section('modals')
    @auth
      <div></div>
    @else
      <div class="modal fade customModal" id="loginModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="panel panel-default formPanel">
              <div class="panel-heading bg-color-1 border-color-1">
                <h3 class="panel-title">Login</h3>
              </div>
              <div class="panel-body">
                @include('layouts/login')
              </div>
            </div>
          </div>
        </div>
      </div>
    @endauth
  @show

  @include('layouts/scripts')
</body>
</html>

