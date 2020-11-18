<!DOCTYPE HTML>
<html lang="en">
    <head>
        <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}"/>
        <title>{{ Cache::get('app_name') }}</title>
        <meta charset="utf-8">
        <meta http-equiv="pragma" content="no-cache"/>
        <meta http-equiv="cache-control" content="max-age=604800" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
        <link href="{{ theme('css/ui.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ theme('css/custom.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ theme('css/responsive.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ theme('css/stepper.css')}}"  rel="stylesheet" type="text/css"/>
        <link href="{{ theme('css/calender.css')}}"  rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> 
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"> 
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/css/intlTelInput.min.css"> 
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script><!--carousel-->
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/intlTelInput.min.js"></script>
        <script>
        var home = "{{ get('home_url') }}";
        </script>
        <script src="{{ asset('js/script.js') }}"></script>
        <script src="{{ asset('js/custom.js') }}"></script>
        <script src="{{ asset('themes/'.get('theme').'/js/script.js')}}"></script>
       	<style>
            .qtyPicker::-webkit-inner-spin-button, 
            .qtyPicker::-webkit-outer-spin-button { 
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                margin: 0; 
            }
            .cartEdit{
                display: none;
            }
        </style>	
    </head>
	<body style="background-color:#f6f7f9">
            <header>
                <nav class="header-info text-left navbar-light bg-white p-3 shadow-sm">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="logo">
                                    <a class="navbar-brand ml-2" href="{{ route('home') }}"><img src="{{ asset('images/headerlogo.png') }}"  alt="Logo">
                                    </a>
                                </div>
                            </div>

                            <div class="col-md-6 text-left text-lg-center mt-3">
                                <form action="{{ route('shop') }}" class="search-header">
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="{{ isset($_GET['s']) ? trim($_GET['s']) : ''}}" name="s" placeholder="Search">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit">
                                                <em class="fa fa-search"></em>
                                            </button>
                                        </div>
                                    </div>
                                </form> 
                            </div>

                            <div class="col-md-3 text-left text-xl-right mt-3">
                                <div class="lcbuttons">
                                    @if(isLoggedIn())
                                        <div class="nav-item cartside">
                                            <a class="btn btn-link" href="{{ route('my-account') }}"><em class="fa fa-user fa-lg mr-2"></em>My Account</a>
                                        </div>
                                    @else
                                        <div class="nav-item loginhover">
                                            <a href="{{ route('login') }}"><button class="btn btn-primary"><img src="{{ theme('images/login.png') }}" alt="Login">Login</button></a>
                                        </div>
                                    @endif
                                    <div class="nav-item cartside">
                                        <a class="btn btn-link" href="{{ route('cart') }}"><img src="{{ theme('images/cart.png') }}" alt="Cart">
                                            @if(isset($data['cart']['cart']) && is_array($data['cart']['cart']) && count($data['cart']['cart']))
                                                <span class="badge badge-danger">{{ count($data['cart']['cart']) }}</span>
                                            @endif
                                            Cart
                                        </a> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(isset($view) && $view != 'home')
                        @include('themes.ekart.common.sub-header')
                    @endif
                </nav>
            </header>

            <div>
                @include("themes.".get('theme').".parts.breadcrumb")
                @include("themes.".get('theme').".common.msg")
            </div>
         