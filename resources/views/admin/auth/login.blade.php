<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="{{$base_url}}backend_asset/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{$base_url}}backend_asset/css/style.css" rel="stylesheet">

    {{-- <title>Luxcozi | admin panel1254</title> --}}
    <title>Ayachak Ashrama</title>
  </head>
  <body>
    <main class="login">
      {{-- <div class="login__left">
        <img src="{{asset('backend_asset/images/men.png') }}">
      </div> --}}
      <div class="login__right">
        <div class="login__block">
          <div class="logo__block">
            <img src="{{asset('backend_asset/images/logo.png') }}">
          </div>

          @if (Session::get('success'))<div class="alert alert-success">{{ Session::get('success') }}</div>@endif
          @if (Session::get('failure'))<div class="alert alert-danger">{{ Session::get('failure') }}</div>@endif

          <form method="POST" action="{{ route('admin.login.check') }}">
          @csrf
            <div class="form-floating mb-3">
              <input type="email" class="form-control" name="email" value="{{ old('email') }}" id="floatingInput" placeholder="name@example.com">
              <label for="floatingInput">Email address</label>
            </div>
            @error('email') <p class="small text-danger">{{ $message }}</p> @enderror

            <div class="form-floating mb-3">
              <input type="password" class="form-control" name="password" id="floatingPassword" placeholder="Password">
              <label for="floatingPassword">Password</label>
            </div>
            @error('password') <p class="small text-danger">{{ $message }}</p> @enderror

            <div class="row mb-3">
              <div class="col-6">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                  <label class="form-check-label" for="flexCheckDefault">
                    Remember Me
                  </label>
                </div>
              </div>
              {{-- <div class="col-6 text-end">
                <a href="{{ route('password.request') }}">Forgot Password?</a>
              </div> --}}
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-lg btn-primary">Login</button>
            </div>
          </form>

          <div class="row mt-3">
              <div class="col-12 text-center">
                <a href="{{ url('/') }}">Back to homepage</a>
              </div>
            </div>
        </div>
      </div>
    </main>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="{{$base_url}}backend_asset/js/bootstrap.bundle.min.js"></script>
<script>
// $(document).ready(function() {
//     // Update href attributes of all <a> tags
//     $('a').each(function() {
//         var href = $(this).attr('href');
//         if (href && href.startsWith('http:')) {
//             $(this).attr('href', href.replace('http:', 'https:'));
//         }
//     });
//     $('link').each(function() {
//         var href = $(this).attr('href');
//         if (href && href.startsWith('http:')) {
//             $(this).attr('href', href.replace('http:', 'https:'));
//         }
//     });

//     // Update action attributes of all <form> tags
//     $('form').each(function() {
//         var action = $(this).attr('action');
//         if (action && action.startsWith('http:')) {
//             $(this).attr('action', action.replace('http:', 'https:'));
//         }
//     });

//     // Update src attributes of all elements with src attributes
//     $('[src]').each(function() {
//         var src = $(this).attr('src');
//         if (src && src.startsWith('http:')) {
//             $(this).attr('src', src.replace('http:', 'https:'));
//         }
//     });
// });
</script>
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>
