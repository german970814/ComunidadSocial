<div class="media-profile teamContent teamAdjust">
    <div class="teamImage">
        <img class="img-circle img-responsive" style="height: 100%; width: 100%" src="{{ $usuario->get_profile_photo_url() }}" alt="profile" />
        @if (Auth::guard()->user()->usuario->id === $usuario->id)
        <div class="maskingContent">
            <ul class="list-inline">
                <li>
                    <form class="form-change-profile-photo" action="{{ route('usuario.change-profile-photo') }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <a href="#" data-original-title="Cambiar foto de perfil" data-placement="bottom" data-toggle="tooltip">
                            <i class="fa fa-upload"></i>
                            <input type="file" name="photo" accept="image/*" />
                        </a>
                    </form>
                </li>
            </ul>
        </div>
        @else
        {{-- <li><a href="#"><i class="fa fa-facebook"></i></a></li>
        <li><a href="#"><i class="fa fa-facebook"></i></a></li> --}}
        @endif
    </div>
</div>
