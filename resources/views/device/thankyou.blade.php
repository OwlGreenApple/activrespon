@extends('layouts.app')

@section('content')

<div class="jumbotron text-center">
  <h1 class="display-3">Thank You!</h1>
  <p class="lead"><strong>Please check your email</strong> for further instructions on how to complete your account setup.</p>
  <hr>
  <p>
    Total : <b>Rp 100.000,00</b>
  </p>
  <p class="lead">
    <a class="btn btn-primary btn-sm" href="{{route('temporary')}}" role="button">Confirm Payment</a>
  </p>
</div>
@endsection
