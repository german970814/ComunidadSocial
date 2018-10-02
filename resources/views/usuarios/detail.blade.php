@extends('usuarios/base_profile')

@section('section')
    <div>
        <form action="">
            @csrf
            <div class="form-group">
                <textarea class="form-control border-color-4" placeholder="Escribe algo"></textarea>
            </div>
            <button class="btn btn-primary" type="submit">Publicar</button>
        </form>
        <div class="space-25"></div>
        <div class="timeline">
            <div class="well">
                <div class="media">
                    <div class="media-left">
                        <a href="#">
                            <img src="{{ asset('assets/img/user.png') }}" alt="profile" width="32" height="32" class="media-object" />
                        </a>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">Nombre</h4>
                    </div>
                </div>
                <div class="row"></div>
                <div class="space-25"></div>
                <div class="row row-comment">
                    <textarea class="form-control border-color-5" placeholder="Escribe un comentario" rows="10" cols="50"></textarea>
                </div>
            </div>
            <div class="space-25"></div>
            <div class="well">
                <div class="media">
                    <div class="media-left">
                        <a href="#">
                            <img src="{{ asset('assets/img/user.png') }}" alt="profile" width="32" height="32" class="media-object" />
                        </a>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">Nombre</h4>
                    </div>
                </div>
                <div class="row"></div>
                <div class="space-25"></div>
                <div class="row row-comment">
                    <textarea class="form-control border-color-5" placeholder="Escribe un comentario" rows="10" cols="50"></textarea>
                </div>
            </div>
            <div class="space-25"></div>
            <div class="well">
                <div class="media">
                    <div class="media-left">
                        <a href="#">
                            <img src="{{ asset('assets/img/user.png') }}" alt="profile" width="32" height="32" class="media-object" />
                        </a>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">Nombre</h4>
                    </div>
                </div>
                <div class="row"></div>
                <div class="space-25"></div>
                <div class="row row-comment">
                    <textarea class="form-control border-color-5" placeholder="Escribe un comentario" rows="10" cols="50"></textarea>
                </div>
            </div>
            <div class="space-25"></div>
            <div class="well">
                <div class="media">
                    <div class="media-left">
                        <a href="#">
                            <img src="{{ asset('assets/img/user.png') }}" alt="profile" width="32" height="32" class="media-object" />
                        </a>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">Nombre</h4>
                    </div>
                </div>
                <div class="row"></div>
                <div class="space-25"></div>
                <div class="row row-comment">
                    <textarea class="form-control border-color-5" placeholder="Escribe un comentario" rows="10" cols="50"></textarea>
                </div>
            </div>
            <div class="space-25"></div>
            <div class="well">
                <div class="media">
                    <div class="media-left">
                        <a href="#">
                            <img src="{{ asset('assets/img/user.png') }}" alt="profile" width="32" height="32" class="media-object" />
                        </a>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">Nombre</h4>
                    </div>
                </div>
                <div class="row"></div>
                <div class="space-25"></div>
                <div class="row row-comment">
                    <textarea class="form-control border-color-5" placeholder="Escribe un comentario" rows="10" cols="50"></textarea>
                </div>
            </div>
            <div class="space-25"></div>
            <div class="well">
                <div class="media">
                    <div class="media-left">
                        <a href="#">
                            <img src="{{ asset('assets/img/user.png') }}" alt="profile" width="32" height="32" class="media-object" />
                        </a>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">Nombre</h4>
                    </div>
                </div>
                <div class="row"></div>
                <div class="space-25"></div>
                <div class="row row-comment">
                    <textarea class="form-control border-color-5" placeholder="Escribe un comentario" rows="10" cols="50"></textarea>
                </div>
            </div>
            <div class="space-25"></div>
            <div class="well">
                <div class="media">
                    <div class="media-left">
                        <a href="#">
                            <img src="{{ asset('assets/img/user.png') }}" alt="profile" width="32" height="32" class="media-object" />
                        </a>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">Nombre</h4>
                    </div>
                </div>
                <div class="row"></div>
                <div class="space-25"></div>
                <div class="row row-comment">
                    <textarea class="form-control border-color-5" placeholder="Escribe un comentario" rows="10" cols="50"></textarea>
                </div>
            </div>
            <div class="space-25"></div>
            <div class="well">
                <div class="media">
                    <div class="media-left">
                        <a href="#">
                            <img src="{{ asset('assets/img/user.png') }}" alt="profile" width="32" height="32" class="media-object" />
                        </a>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">Nombre</h4>
                    </div>
                </div>
                <div class="row"></div>
                <div class="space-25"></div>
                <div class="row row-comment">
                    <textarea class="form-control border-color-5" placeholder="Escribe un comentario" rows="10" cols="50"></textarea>
                </div>
            </div>
            <div class="space-25"></div>
        </div>
    </div>
@endsection