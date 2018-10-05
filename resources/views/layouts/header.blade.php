<header id="pageTop" class="header-wrapper">
    <div class="container-fluid color-bar top-fixed clearfix">
        <div class="row">
            <div class="col-sm-1 col-xs-2 bg-color-1">fix bar</div>
            <div class="col-sm-1 col-xs-2 bg-color-2">fix bar</div>
            <div class="col-sm-1 col-xs-2 bg-color-3">fix bar</div>
            <div class="col-sm-1 col-xs-2 bg-color-4">fix bar</div>
            <div class="col-sm-1 col-xs-2 bg-color-5">fix bar</div>
            <div class="col-sm-1 col-xs-2 bg-color-6">fix bar</div>
            <div class="col-sm-1 bg-color-1 hidden-xs">fix bar</div>
            <div class="col-sm-1 bg-color-2 hidden-xs">fix bar</div>
            <div class="col-sm-1 bg-color-3 hidden-xs">fix bar</div>
            <div class="col-sm-1 bg-color-4 hidden-xs">fix bar</div>
            <div class="col-sm-1 bg-color-5 hidden-xs">fix bar</div>
            <div class="col-sm-1 bg-color-6 hidden-xs">fix bar</div>
        </div>
    </div>

    <div class="top-info-bar bg-color-7 hidden-xs">
        <div class="container">
            <div class="row">
                {{-- <div class="col-sm-7">
                    <ul class="list-inline topList">
                    <li><i class="fa fa-envelope bg-color-1" aria-hidden="true"></i> <a href="mailto:info@yourdomain.com">info@yourdomain.com</a></li>
                    <li><i class="fa fa-phone bg-color-2" aria-hidden="true"></i> +1 234 567 8900</li>
                    <li><i class="fa fa-clock-o bg-color-6" aria-hidden="true"></i> Open: 9am - 6pm</li>
                    </ul>
                </div>
                <div class="col-sm-5"> --}}
                <div class="col-sm-offset-8 col-sm-4">
                    <ul class="list-inline functionList">
                        @auth
                            <li><i class="fa fa-user bg-color-1" aria-hidden="true"></i> <a href="{{ route('usuario.profile') }}">{{ Auth::guard()->user()->usuario->get_full_name() }}</a></li>
                            <li><i class="fa fa-unlock-alt bg-color-5" aria-hidden="true"></i> <a href="/logout">Salir</a></li>
                            <li class="cart-dropdown">
                                <a href="#" class="bg-color-6 shop-cart" tabindex="2">
                                    <i class="fa fa-bell" aria-hidden="true"></i>
                                    @if ($notificaciones_pendientes() >= 1)
                                        <span class="badge bg-color-1">{{ $notificaciones_pendientes() }}</span>
                                    @endif
                                </a>
                                <ul tabindex="1" class="dropdown-menu dropdown-menu-right">
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
                            <li><i class="fa fa-unlock-alt bg-color-5" aria-hidden="true"></i> <a href='#loginModal' data-toggle="modal" >Entrar</a> or <a href="{{ route('usuario.create') }}">Crear una cuenta</a></li>
                        @endauth
                    </ul>
                </div>
                {{-- </div> --}}
            </div>
        </div>
    </div>

    <nav id="menuBar" class="navbar navbar-default lightHeader" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/"><img src="{{ asset('assets/img/logo-school.png') }}" alt="Kidz School"></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown singleDrop color-1   active ">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-home bg-color-1" aria-hidden="true"></i> <span class="active">Inicio</span>
                        </a>
                    </li>
                    <li class="dropdown singleDrop color-3 ">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-list-ul bg-color-3" aria-hidden="true"></i> <span>Periódico</span>
                        </a>
                    </li>
                    <li class=" dropdown megaDropMenu color-2 ">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="300" data-close-others="true" aria-expanded="false">
                            <i class="fa fa-file-text-o bg-color-2" aria-hidden="true"></i> <span>Preguntas Frecuentes</span>
                        </a>
                    </li>
                    <li class="dropdown singleDrop color-4 ">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-pencil-square-o bg-color-4" aria-hidden="true"></i> <span>Contenido Digital</span>
                        </a>
                    </li>
                    <li class="dropdown singleDrop color-5  ">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-calendar bg-color-5" aria-hidden="true"></i> <span>Tutoriales</span>
                        </a>
                    </li>
                    <li class="dropdown singleDrop color-6 ">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-gg bg-color-6" aria-hidden="true"></i> <span>Soporte</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>