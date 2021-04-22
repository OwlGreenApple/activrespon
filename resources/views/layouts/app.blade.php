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
    <script src="{{ asset('/assets/datetimepicker/jquery.datetimepicker.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="{{ asset('/assets/css/nunito.css') }}" rel="stylesheet">
    
    <!-- Styles -->

    <!-- Bootstrap Style -->
    <link href="{{ asset('/assets/css/app.css') }}" rel="stylesheet">

    <!--<link href="{{ asset('/assets/css/waku.css') }}" rel="stylesheet"> -->
    
    <!-- Icomoon -->
    <link href="{{ asset('/assets/icomoon/icomoon.css') }}" rel="stylesheet" />
    
    <!-- Font Awesome 4.7 
    <link href="{{ asset('/assets/Font-Awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet"> -->

    <!-- Font Awesome 5 -->
    <link href="{{ asset('/assets/font-awesome-5/all.css') }}" rel="stylesheet">



    <!-- Emoji -->
    <link href="{{ asset('/assets/emoji/css/emojionearea.min.css') }}" rel="stylesheet"> 
    <script type="text/javascript" src="{{ asset('/assets/emoji/js/prettify.js') }}"></script>
    @if (env('APP_ENV')=='local')
    <script type="text/javascript" src="{{ asset('/assets/emoji/js/emojionearea.js') }}"></script>
    @else
    <script type="text/javascript" src="{{ asset('/assets/emoji/js/emojionearea-production.js') }}"></script>
    @endif

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

    <!-- Datetimepicker -->
    <link href="{{ asset('/assets/datetimepicker/jquery.datetimepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <script type="text/javascript" src="{{ asset('/assets/datetimepicker/js/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/assets/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script> 

    <!-- MDtimepicker -->
    <link href="{{ asset('/assets/MDTimePicker/mdtimepicker.min.css') }}" rel="stylesheet">
    <script type="text/javascript" src="{{ asset('/assets/MDTimePicker/mdtimepicker.min.js') }}"></script>

    <!-- Clipboard -->
    <script type="text/javascript" src="{{ asset('/assets/clipboard.js-master/clipboard.min.js') }}"></script> 

    <!-- Fancybox -->
    <link href="{{ asset('/assets/fancybox/jquery.fancybox.min.css') }}" rel="stylesheet" />
    <script type="text/javascript" src="{{ asset('/assets/fancybox/jquery.fancybox.min.js') }}"></script> 

    <!-- Intl Dialing Code -->
    <link href="{{ asset('/assets/intl-tel-input/css/intlTelInput.min.css') }}" rel="stylesheet" />
    <script type="text/javascript" src="{{ asset('/assets/intl-tel-input/js/intlTelInput.js') }}"></script> 

    <!-- Jquery Exists Element
    <script type="text/javascript" src="{{ asset('/assets/exists/exists.js') }}"></script>
     -->

    <!-- Main Styles -->
    <link href="{{ asset('/assets/css/main.css') }}" rel="stylesheet" />
    <link href="{{ asset('/assets/css/dashboard.css') }}" rel="stylesheet" />
    <link href="{{ asset('/assets/css/lists.css') }}" rel="stylesheet" />
    <link href="{{ asset('/assets/css/campaign.css') }}" rel="stylesheet" />
    <link href="{{ asset('/assets/css/settings.css') }}" rel="stylesheet" />
    <link href="{{ asset('/assets/css/appointment.css') }}" rel="stylesheet" />

    <!-- Jquery Tabs -->
    <link href="{{ asset('/assets/css/jquery-tabs.css') }}" rel="stylesheet" />
    <!-- Jquery Custom Select -->
    <link href="{{ asset('/assets/css/custom-select.css') }}" rel="stylesheet" />

		<!-- Tooltips -->
		<script type="text/javascript" src="{{asset('/assets/tooltipster/dist/js/tooltipster.bundle.min.js')}}"></script>
		<link rel="stylesheet" type="text/css" href="{{asset('/assets/tooltipster/dist/css/tooltipster.bundle.min.css')}}" />

    <!-- Select2 -->
    <link href="{{ asset('/assets/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('/assets/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet" />
    <script type="text/javascript" src="{{ asset('/assets/select2/js/select2.min.js') }}"></script>

    <!-- Canvas JS -->
    <script type="text/javascript" src="{{ asset('canvasjs/canvasjs.min.js') }}"></script>

    <!-- custom scroll -->
    <link href="{{ asset('assets/malihu-custom-scrollbar/jquery.mCustomScrollbar.min.css') }}" rel="stylesheet" />
    

</head>
<body>
		<?php 
			$is_nav_show = true;
			$arr_segment = ["pricing","checkout","summary"];
			if (in_array(request()->segment(1), $arr_segment)) {
				$is_nav_show = false;
			}
		?>

    <!-- Loading lama
    <div id="div-loading">
      <div class="loadmain"></div>
      <div class="background-load"></div>
    </div>
    -->
    <!--Loading Bar-->
    <div class="div-loading">
      <div id="loader" style="display: none;"></div>  
    </div> 

    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-telegram header-tel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('home') }}">
                    <img class="logo" src="{{asset('assets/img/logo.png')}}" />
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav">
                        <!-- Authentication Links -->
                        @guest

                        @else
                          @if($is_nav_show)
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link {{ (request()->is('home') || request()->is('list-form') || request()->is('list-create') || request()->is('create-campaign') || request()->is('create-apt')) ? 'active' : '' }} dropdown-toggle" href="{{ route('home') }}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Create<span class="caret"></span>
                                </a>
                                
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a href="{{url('list-form')}}" class="nav-link {{ (request()->is('list-form') || request()->is('create-campaign')|| request()->is('create-apt')) ? 'active' : '' }}">Lists</a>
                                    
                                    <a href="{{url('create-campaign')}}" class="nav-link {{ (request()->is('create-campaign')) ? 'active' : '' }}">Campaigns</a> 
                                    @if(getMembership(Auth()->user()->membership) > 1) 
                                      <a href="{{url('create-event')}}" class="nav-link {{ (request()->is('create-event')) ? 'active' : '' }}">Event</a>  

                                      <a href="{{url('create-apt')}}" class="nav-link {{ (request()->is('create-apt')) ? 'active' : '' }}">Appointment</a>
                                    @endif
                                </div>
                            </li> 

                            <li class="nav-item">   
                                <a href="{{url('lists')}}" class="nav-link {{ (request()->is('lists') || request()->is('lists-create') || Request::segment(1) == 'list-edit') ? 'active' : '' }}">Lists</a>
                            </li> 

                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('campaign') || request()->is('add-reminder')) ? 'active' : '' }}" href="{{url('campaign')}}">Campaigns</a>
                            </li> 
                            
                            @if(getMembership(Auth()->user()->membership) > 1) 
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('event')) ? 'active' : '' }}" href="{{ url('event') }}">Event</a>
                            </li> 

                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('appointment')) ? 'active' : '' }}" href="{{ route('appointment') }}">Appointment</a>
                            </li>
                            @endif
														<!--
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('google-form')) ? 'active' : '' }}" href="{{ url('google-form') }}">Google Form</a>
                            </li>
														-->
														@if(Auth()->user()->is_admin)
															<li class="nav-item">
                                <a class="nav-link {{ (request()->is('list-user')) ? 'active' : '' }}" href="{{ url('list-user') }}">Admin Page</a>
															</li>
														@endif
                            @if(Auth()->user()->is_chat > 0)
                             <li class="nav-item">
                                <a class="nav-link {{ (request()->is('chats')) ? 'active' : '' }}" href="{{ url('chats') }}">Chats</a>
                            </li>
                            @endif
                        @endif 
                    </ul>
                    <ul class="navbar-nav mr-auto"><!-- separator --></ul>
                    @if($is_nav_show)
                     <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
												@if(!Auth()->user()->is_admin)
													<li class="nav-item mr-3">
														 Hi, {{Auth()->user()->name}}
													</li>
												@endif 
                        <li class="nav-item cog-pos dropdown">
                           <a id="cogDropdown" class="icon-cog" data-toggle="dropdown"></a>
                           <div class="dropdown-menu dropdown-menu-right text-center" aria-labelledby="cogDropdown">
                                    <a href="{{url('settings')}}" class="nav-link {{ (request()->is('settings')) ? 'active' : '' }}">Settings</a>

                                    <a href="{{url('pricing')}}" class="nav-link {{ (request()->is('pricing')) ? 'active' : '' }}">Buy More</a> 

                                    <a href="{{url('reseller-invoice')}}" class="nav-link {{ (request()->is('reseller-invoice')) ? 'active' : '' }}">Reseller Invoice</a>
                                    
                                    <!--<a href="{{url('pricing')}}" class="nav-link {{ (request()->is('pricing')) ? 'active' : '' }}">Upgrade</a> 
                                    -->
																		<a href="{{url('order')}}" class="nav-link {{ (request()->is('order')) ? 'active' : '' }}">Order & Confirm</a>
                                    
																		<a href="https://docs.google.com/document/d/1Z29tFyZuWr0nw0uQ0gETlzvRS-T2Nn4ccuNMU5C_5sI/edit" class="nav-link" target="_blank">Tutorial</a>
																		
                                    <a class="nav-link" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Log Out') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                            </div>
                        </li>
                    </ul>
                    @endif
                    @endguest

                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- footer -->
    <div class="col-md-12">
      <div class="container footer">
        Copyright &copy; 2020 <b>Activrespon</b> All Rights Reserved.<br/>
        <!-- @if(Auth()->check())
           <a href="{{ url('signout') }}">Logout</a>                        
        @endif -->
      </div>
    </div>

    <script type="text/javascript">
        $(window).on('load', function() { 
          $("#div-loading").hide();
        }); 

			 $(document).ready(function() {
			 	$('.tooltipstered').tooltipster({
					contentAsHTML: true,
					trigger: 'ontouchstart' in window || navigator.maxTouchPoints ? 'click' : 'hover',
				});
			});

    </script>

</body>
</html>
