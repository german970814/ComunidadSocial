<form action="{{ route('login') }}" method="POST" role="form">
    @csrf
    <div class="form-group formField">
        <input name="email" type="text" class="form-control" placeholder="Email">
    </div>
    <div class="form-group formField">
        <input name="password" type="password" class="form-control" placeholder="Contraseña">
    </div>
    <div class="form-group formField">
        <input type="submit" class="btn btn-primary btn-block bg-color-3 border-color-3" value="Log in">
    </div>
    <div class="form-group formField">
        <p class="help-block"><a href="#">Olvidé la contraseña?</a></p>
    </div>
</form>