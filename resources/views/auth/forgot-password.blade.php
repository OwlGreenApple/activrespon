@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="act-tel-login bg-dashboard wrapper">
               <form class="mb-4" method="POST" action="{{ url('send-forgot-password') }}">
                  @csrf
                   <div class="form-group text-center">
                      <big>Hello</big>
                    </div>

                   <div class="form-group">
                      <label>Your Email*</label>
                       <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }} {{ Cookie::get('email') }}" required autocomplete="email" placeholder="Input Your Email">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="text-right">
                      <button type="submit" class="btn btn-custom btn-lg">Send</button>
                    </div>
               </form>

            <!-- end wrapper -->
            </div>

            <hr class="mt-5" />

        </div>
    </div>
</div>
@endsection
