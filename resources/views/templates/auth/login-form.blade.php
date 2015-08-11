<form class="form-horizontal" role="form" method="POST" action="/auth/login">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="form-group">
        <label class="control-label">E-Mail Address</label>
        <input type="email" class="form-control" name="email" value="{{ old('email') }}">
    </div>

    <div class="form-group">
        <label class="control-label">Password</label>
        <input type="password" class="form-control" name="password">
    </div>

    <div id="remember-me-container" class="form-group">
        <input type="checkbox" name="remember" id="remember-me">
        <label for="remember-me">Remember Me</label>
    </div>

    <div class="form-group">
        <div>
            <button type="submit" class="btn btn-primary" style="margin-right: 15px;">
                Login
            </button>

            <a href="/password/email">Forgot Your Password?</a>
        </div>
    </div>
</form>