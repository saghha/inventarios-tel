<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="">

<div class="app flex-row align-items-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card-group mb-0">
            <div class="card p-2">
                <div class="card-block">
                    <h1>Login</h1>
                    <form action="{{route('login')}} " method="POST">
                        {{ csrf_field() }}
                    <p class="text-muted">Sign In to your account</p>
                    <div class="input-group mb-1">
                    <span class="input-group-addon"><i class="icon-user"></i></span>
                    <input type="email" class="form-control" placeholder="Email" name="email">
                    </div>
                    <div class="input-group mb-2">
                    <span class="input-group-addon"><i class="icon-lock"></i></span>
                    <input type="password" class="form-control" placeholder="Password" name="password">
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary px-2">Login</button>
                        </div>
                        <div class="col-6 text-right">
                            <button type="button" class="btn btn-link px-0">Forgot password?</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            <div class="card card-inverse card-primary py-3 hidden-md-down" style="width:44%">
              <div class="card-block text-center">
                <div>
                  <h2>Sign up</h2>
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                  <button type="button" class="btn btn-primary active mt-1">Register Now!</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>