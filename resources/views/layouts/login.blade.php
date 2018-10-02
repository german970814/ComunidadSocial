<form action="{{ route('login') }}" method="POST" role="form">
    <div class="form-group formField">
        <input type="text" class="form-control" placeholder="User name">
    </div>
    <div class="form-group formField">
        <input type="password" class="form-control" placeholder="Password">
    </div>
    <div class="form-group formField">
        <input type="submit" class="btn btn-primary btn-block bg-color-3 border-color-3" value="Log in">
    </div>
    <div class="form-group formField">
        <p class="help-block"><a href="#">Forgot password?</a></p>
    </div>
</form>