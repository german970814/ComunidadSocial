<form action="{{ route('login') }}" method="POST" role="form">
    @csrf
    <div class="form-group formField {{ $errors->has('email') ? ' color-3' : ''}}">
        <input name="email" type="text" class="form-control" placeholder="Email">
        @if ($errors->has('email'))
            <span class="invalid-feedback" role="alert">
                <strong class="color-3">{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group formField {{ $errors->has('password') ? ' color-3' : ''}}">
        <input name="password" type="password" class="form-control" placeholder="Contraseña">
        @if ($errors->has('password'))
            <span class="invalid-feedback" role="alert">
                <strong class="color-3">{{ $errors->first('password') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group formField">
        <input type="submit" class="btn btn-primary btn-block bg-color-3 border-color-3" value="Log in">
    </div>
    <div class="form-group row">
        <div class="col-md-6 offset-md-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    {{ __('Recordarme') }}
                </label>
            </div>
        </div>
    </div>
    <div class="form-group formField">
        <p class="help-block"><a href="#">Olvidé la contraseña?</a></p>
    </div>
</form>