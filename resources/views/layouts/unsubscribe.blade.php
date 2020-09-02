<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Activrespon') }}</title>
		<!-- Icon -->
		<link rel='shortcut icon' type='image/png' href="{{ asset('assets/img/favicon.png') }}">

   <!-- Scripts -->
    <script src="{{ asset('/assets/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('/assets/js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="{{ asset('/assets/css/nunito.css') }}" rel="stylesheet">
    
    <!-- Styles -->

    <!-- Bootstrap Style -->
    <link href="{{ asset('/assets/css/app.css') }}" rel="stylesheet">

    <!-- Font Awesome 4.7 -->
    <link href="{{ asset('/assets/Font-Awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet">

    <!-- Font Awesome 5 -->
    <link href="{{ asset('/assets/font-awesome-5/all.css') }}" rel="stylesheet">

    <!-- Icomoon -->
    <link href="{{ asset('/assets/icomoon/icomoon.css') }}" rel="stylesheet" />

    <!-- Main Styles -->
    <link href="{{ asset('/assets/css/main.css') }}" rel="stylesheet" />   

</head>
<body>

    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-telegram bg-subscribe-top header-tel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('home') }}">
                    <img class="logo" src="{{asset('assets/img/logo.png')}}" />
                </a>
            </div>
        </nav>

      <main class="bg-subscribe-bottom bg-telegram mb-3">
         <div class="container">
           <div class="col-lg-12 row">
            <!-- left -->
             <div class="col-lg-9">
               <div class="subscriber-big-font">Sorry to see you go</div>
               <div class="subscriber-small-font px-2 mt-2">you have been unsubscribed from:</div>
               <div class="subscriber-list">{{ $label }}</div>
               <div class="subscriber-small-font">Contact us if you have any questions</div>
               <a class="btn btn-subscribe btn-lg">Contact Now</a>
             </div>
            <!-- right -->
             <div class="col-lg-3 subscribe-icon">
               <img class="subscribe-img" src="{{ asset('assets/img/icon_unsubscrib.png') }}"/>
             </div>
           </div>
          <!-- end row -->
         </div>
      </main>
    </div>

    <!-- footer -->
    <div class="col-md-12">
      <div class="container footer">
        Copyright &copy; 2020 <b>Activrespon</b> All Rights Reserved.
      </div>
    </div>

</body>
</html>
