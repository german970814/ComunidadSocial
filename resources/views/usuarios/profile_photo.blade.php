{{-- <form action="/process" enctype="multipart/form-data" method="POST">
    @csrf
    <p>
        <label for="photo">
            <input type="file" name="photo" id="photo">
        </label>
    </p>
    <button>Upload</button>
</form> --}}
<div class="media-profile teamContent teamAdjust">
    <div class="teamImage">
        <img class="img-circle img-responsive" style="height: 100%; width: 100%" src="{{ $usuario->get_profile_photo_url() }}" alt="profile" />
        <div class="maskingContent">
            <ul class="list-inline">
                @if (Auth::guard()->user()->usuario->id === $usuario->id)
                <li>
                    <form class="form-change-profile-photo" action="{{ route('usuario.change-profile-photo') }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <a href="#">
                            <i class="fa fa-upload"></i>
                            <input type="file" name="photo" accept="image/*" />
                        </a>
                    </form>
                </li>
                @else
                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                @endif
            </ul>
        </div>
    </div>
</div>
