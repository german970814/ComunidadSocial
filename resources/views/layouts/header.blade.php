<header id="pageTop" class="header-wrapper">
    <div class="container-fluid color-bar top-fixed clearfix">
        <div class="row">
            <div class="col-sm-1 col-xs-2 bg-color-3">fix bar</div>
            <div class="col-sm-1 col-xs-2 bg-color-3">fix bar</div>
            <div class="col-sm-1 col-xs-2 bg-color-3">fix bar</div>
            <div class="col-sm-1 col-xs-2 bg-color-3">fix bar</div>
            <div class="col-sm-1 col-xs-2 bg-color-3">fix bar</div>
            <div class="col-sm-1 col-xs-2 bg-color-3">fix bar</div>
            <div class="col-sm-1 bg-color-3 hidden-xs">fix bar</div>
            <div class="col-sm-1 bg-color-3 hidden-xs">fix bar</div>
            <div class="col-sm-1 bg-color-3 hidden-xs">fix bar</div>
            <div class="col-sm-1 bg-color-3 hidden-xs">fix bar</div>
            <div class="col-sm-1 bg-color-3 hidden-xs">fix bar</div>
            <div class="col-sm-1 bg-color-3 hidden-xs">fix bar</div>
        </div>
    </div>

    <div class="top-info-bar bg-color-primary">
        <div class="container">
            <div class="row">
                {{-- <div class="col-sm-7">
                    <ul class="list-inline topList">
                    <li><i class="fa fa-envelope bg-color-1" aria-hidden="true"></i> <a href="mailto:info@yourdomain.com">info@yourdomain.com</a></li>
                    <li><i class="fa fa-phone bg-color-2" aria-hidden="true"></i> +1 234 567 8900</li>
                    <li><i class="fa fa-clock-o bg-color-6" aria-hidden="true"></i> Open: 9am - 6pm</li>
                    </ul>
                </div> --}}
                <div class="col-md-3 hidden-xs header-item inline">
                    @auth
                    <form action="{{ route('usuario.buscar_usuarios') }}" method="GET">
                        <div class="form-group formField">
                            <input type="text" name="q" class="buscar" placeholder="Buscar" />
                        </div>
                    </form>
                    @endauth
                </div>
                @auth
                    <div class="col-xs-1 hidden-sm hidden-md hidden-lg hidden-xl">
                        <button type="button" id="sidebarCollapse" class="shop-cart bg-color-4">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>
                @endauth
                <div class="col-md-9 col-xs-11">
                    <ul class="list-inline functionList">
                        @auth
                            <li class="header-item block">
                                <a href="{{ route('usuario.profile') }}">
                                    <i class="fa fa-home bg-color-accent-2" aria-hidden="true"></i>
                                    <span>
                                        INICIO
                                    </span>
                                </a>
                            </li>
                            <li class="header-item block">
                                <a href="{{ route('usuario.amigos', \Auth::guard()->user()->usuario->id) }}">
                                    <i class="fa fa-group bg-color-accent-2" aria-hidden="true"></i>
                                    <span>
                                        AMIGOS
                                    </span>
                                </a>
                            </li>
                            <li class="header-item block">
                                <a href="{{ route('usuario.mensajes') }}">
                                    <i class="fa fa-envelope bg-color-accent-2" aria-hidden="true"></i>
                                    <span>
                                        MENSAJES
                                    </span>
                                </a>
                            </li>
                            <li class="header-item inline">
                                <i class="fa fa-user bg-color-accent-2" aria-hidden="true"></i>
                                <a href="{{ route('usuario.profile') }}">
                                    {{ Auth::guard()->user()->usuario->get_full_name() }}
                                </a>
                            </li>
                            <li class="header-item inline"><i class="fa fa-unlock-alt bg-color-5" aria-hidden="true"></i> <a href="/logout">Salir</a></li>
                            <li class="header-item inline cart-dropdown">
                                <a href="#" class="bg-color-3 shop-cart" tabindex="2">
                                    <i class="fa fa-bell" aria-hidden="true"></i>
                                    <span id="notificaciones-pendientes" class="badge bg-color-accent-2 {{ $notificaciones_pendientes() >= 1 ? '' : 'hidden' }}">{{ $notificaciones_pendientes() }}</span>
                                </a>
                                <ul tabindex="1" id="notificacion-dropdown" class="dropdown-menu dropdown-menu-right">
                                    <li class="color-3"><i class="fa fa-bell color-3" aria-hidden="true"></i>Notificaciones</li>
                                    @if (!(count($notificaciones()) >= 1))
                                        <li>
                                            <div class="media">
                                                <div class="media-body color-1">
                                                    No hay notificaciones para mostrar
                                                </div>
                                            </div>
                                        </li>
                                    @else
                                        @foreach ($notificaciones() as $notificacion)
                                            <li data-id="{{ $notificacion->id }}" class="notificacion-item {{ $notificacion->leida ? '' : 'notificacion-pendiente' }}">
                                                {!! $notificacion->render() !!}
                                                <span class="cancel"><i class="fa fa-close" aria-hidden="true"></i></span>
                                            </li>
                                        @endforeach
                                    @endif
                                    <li></li>
                                </ul>
                            </li>
                        @else
                            <li class="header-item inline">
                                <i class="fa fa-unlock-alt bg-color-5" aria-hidden="true"></i><a id="modalLogin" href='#loginModal' data-toggle="modal" >Entrar</a> o <a href="{{ route('usuario.create') }}">Crear una cuenta</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </div>
        {{-- <div class="hidden-sm hidden-md hidden-lg hidden-xl" id="sidebar">
            @yield('sidebar')
        </div> --}}
    </div>

    {{-- @if (!Auth::check())
        <nav id="menuBar" class="hidden-md hidden-sm hidden-lg hidden-xl navbar navbar-default lightHeader" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/"><img src="{{ asset('assets/img/logo-school.png') }}" alt="Kidz School"></a>
                </div>


                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown color-1">
                            <a href="/">
                                <i class="fa fa-home bg-color-1" aria-hidden="true"></i> <span class="active">Inicio</span>
                            </a>
                        </li>
                        <li class="dropdown color-3 ">
                            <a href="{{ route('login') }}">
                                <i class="fa fa-list-ul bg-color-3" aria-hidden="true"></i> <span>Entrar</span>
                            </a>
                        </li>
                        <li class=" dropdown color-2 ">
                            <a href="{{ route('usuario.create') }}">
                                <i class="fa fa-file-text-o bg-color-2" aria-hidden="true"></i> <span>Crear una cuenta</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    @endif --}}
</header>