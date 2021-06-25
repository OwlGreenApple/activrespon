<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Admin Activwa') }}</title>

   <!-- Scripts -->
    <script src="{{ asset('/assets/js/jquery-3.2.1.min.js') }}"></script>
    <!--<script src="{{ asset('/assets/js/jquery-1.12.4.js') }}"></script>-->
    <script src="{{ asset('/assets/js/app.js') }}"></script>

    <!-- Fonts -->
    <!-- <link rel="dns-prefetch" href="//fonts.gstatic.com">-->
    <link href="{{ asset('/assets/css/nunito.css') }}" rel="stylesheet">

    <!-- Datetimepicker -->
    <link href="{{ asset('/assets/datetimepicker/jquery.datetimepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <script type="text/javascript" src="{{ asset('/assets/datetimepicker/js/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/assets/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script> 

    <!-- Styles -->
    <link href="{{ asset('/assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/css/waku.css') }}" rel="stylesheet"> 
    <link href="{{ asset('/assets/datetimepicker/jquery.datetimepicker.css') }}" rel="stylesheet">

     <!-- Emoji -->
    <link href="{{ asset('/assets/emoji/css/emojionearea.min.css') }}" rel="stylesheet"> 
    <script type="text/javascript" src="{{ asset('/assets/emoji/js/prettify.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/assets/emoji/js/emojionearea.js') }}"></script>

    <!-- Data Table -->
    <link href="{{ asset('/assets/DataTables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/DataTables/Responsive/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <script defer type="text/javascript" src="{{ asset('/assets/DataTables/datatables.min.js') }}"></script>
    <script defer type="text/javascript" src="{{ asset('/assets/DataTables/Responsive/js/dataTables.responsive.min.js') }}"></script>

    <!-- CKEditor -->
    <link href="{{ asset('/assets/ckeditor/contents.css') }}" rel="stylesheet" />
    <script type="text/javascript" src="{{ asset('/assets/ckeditor/ckeditor.js') }}"></script>

    <!-- CKFinder -->
    <script type="text/javascript" src="{{ asset('/assets/ckfinder/ckfinder.js') }}"></script>

    <!-- Datetimepicker-->
    <link href="{{ asset('/assets/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('/assets/datetimepicker/js/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/assets/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script> 
 
    <!-- MDtimepicker -->
    <link href="{{ asset('/assets/MDTimePicker/mdtimepicker.min.css') }}" rel="stylesheet">
    <script type="text/javascript" src="{{ asset('/assets/MDTimePicker/mdtimepicker.min.js') }}"></script>

    <!-- Clipboard -->
    <script type="text/javascript" src="{{ asset('/assets/clipboard.js-master/clipboard.min.js') }}"></script> 

    <!-- Canvas JS -->
    <script type="text/javascript" src="{{ asset('/canvasjs/canvasjs.min.js') }}"></script>
</head>
<body>

  <main class="py-4">
      @yield('content')
  </main>

</body>
</html>
