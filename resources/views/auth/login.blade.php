<!doctype html>
<html lang="en">
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @include('layouts.components.head')

    <body class="" style="background: #f4f2f1">
        
        <div class="account-pages my-5 pt-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card overflow-hidden">
                            <div class="" style="background: #022130">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="p-4" style="color: #f4f2f1">
                                            <h5>LIGTAS<strong style="color: #ffbc2e">GIS</strong></h5>
                                            <p>Emergency Reporting App</p>
                                        </div>
                                    </div>
                                    <div class="col-5 align-self-end">
                                        <img src="assets/images/profile-img.png" alt="" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0"> 
                                <div class="auth-logo">
                                    <a href="#" class="auth-logo-light">
                                        <div class="avatar-md profile-user-wid mb-4">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <img src="assets/images/ligtas_icon.svg" alt="" class="rounded-circle" height="70">
                                            </span>
                                        </div>
                                    </a>

                                    <a href="#" class="auth-logo-dark">
                                        <div class="avatar-md profile-user-wid mb-4">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <img src="assets/images/ligtas_icon.svg" alt="" class="rounded-circle" height="70">
                                            </span>
                                        </div>
                                    </a>
                                </div>
                                <div class="p-2">

                                    <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email Address" style="    width: 94%;left: 3%;">

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                
                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <div class="input-group auth-pass-inputgroup">
                                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror

                                                <button class="btn btn-light " type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                            
                                            </div>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                        
                                        <div class="mt-3 d-grid">
                                            <button class="btn waves-effect waves-light" type="submit" style="background: #30e483; color: #f4f2f1">Log In</button>
                                        </div>
            

                                        <div class="mt-4 text-center">
                                            <!-- <a href="auth-recoverpw.html" class="text-muted"><i class="mdi mdi-lock me-1"></i> Forgot your password?</a> -->
                                            @if (Route::has('password.request'))
                                                <a style="color: #ffbc2e" class="btn btn-link" href="{{ route('password.request') }}">
                                                    {{ __('Forgot Your Password?') }}
                                                </a>
                                            @endif
                                        </div>
                                    </form>
                                </div>
            
                            </div>
                        </div>
                        <div class="mt-5 text-center">
                            <div>
                                <p>Don't have an account ? <a href="/register" class="fw-medium" style="color: #ffbc2e"> Signup now </a> </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        @include('layouts.components.script')

    </body>
</html>

