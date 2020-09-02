@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="act-tel-login bg-dashboard wrapper">
               <form class="add-contact" method="POST" action="{{ route('login') }}">
                  @csrf
                   <div class="form-group text-center">
                      <big>Hello</big>
                    </div>

                   <div class="form-group">
                      <label>Email*</label>
                       <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }} {{ Cookie::get('email') }}" required autocomplete="email" placeholder="Input Your Email">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                      <label>Password *</label>
                       <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" value="{{ Cookie::get('password') }}" placeholder="Input Your Password">

                       @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                       @enderror
                    </div>

                    <div class="form-group">
                      <label class="custom-checkbox">
                          <input type="checkbox" name="remember" id="remember"/>
                          <span class="checkmark-check"></span>
                      </label>
                      <label class="checkbox-left" for="remember">Remember Me</label>
                    </div>

                    @if (Route::has('password.request'))
                      <div class="form-group">
                        <a href="{{route('password.request')}}">Forgot Password</a>
                      </div>
                    @endif  

                    <div class="text-left">
                      <button type="submit" class="btn btn-custom btn-lg">LOG IN</button>
                    </div>
               </form>

               <hr class="mt-5" />

              <div class="mt-4 mb-3"><sb>Need an Activrespon account? <a href="{{route('pricing')}}">Register Here</a></sb></div>
            <!-- end wrapper -->
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    rememberMe();
  });

  function rememberMe(){
    $("input[name=remember]").click(function(){
      var val = $(this).val();

      if(val == 1){
        $(this).val('on');
      }
      else {
        $(this).val(1);
      }

    });
  }
</script>
@endsection
