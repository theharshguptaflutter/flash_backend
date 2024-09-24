<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | @yield('pageTitle')</title>
    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}">
    <!-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- csrf token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{  PUBLIC_PATH . 'assets/admin/bootstrap/css/bootstrap.min.css' }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{  PUBLIC_PATH . 'assets/admin/font-awesome/css/font-awesome.min.css' }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{  PUBLIC_PATH . 'assets/admin/ionicons/css/ionicons.min.css' }}">
    <link rel="stylesheet" href="{{  PUBLIC_PATH . 'assets/admin/bootstrap/css/bootstrap.css' }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{  PUBLIC_PATH . 'assets/admin/dist/css/AdminLTE.min.css' }}">
    <link rel="stylesheet" href="{{  PUBLIC_PATH . 'assets/admin/dist/css/skins/skin-blue.min.css' }}">
    <link rel="stylesheet" href="{{  PUBLIC_PATH . 'assets/admin/dist/css/style.css' }}">

    @yield('customStyles')
    <!-- ================= custom css ================= -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/responsive.css') }}">

</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <header class="main-header">

            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <button class="sidebar-toggle">
                    <div></div>
                </button>
                <div class="site_logo_frMobile">
                    <a href="javascript:;"><img src="{{ asset('assets/images/instavio-logo.png') }}" alt=""></a>
                </div>

                <div class="navbar-custom-menu">
                    <div class="dropdown user_more_dropdown">
                        <button class="dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">
                            <?php
                             $path = "";
                             if(Auth::user()->profile_picture != NULL){
                                 $path = Auth::user()->profile_picture; 
                             }  ?>
                            <div class="img-container">
                                <img src="@if(isset(Auth::user()->profile_picture)) {{ PUBLIC_PATH.'images/'. $path }} @else {{ asset('assets/noimage.jpg') }} @endif" class="user-image" alt="User Image">
                            </div>
                            <h5>{{Auth::user()->first_name}}</h5>
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="{{admin_url('admin-profile')}}">Profile</a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="{{admin_url('logout') }}">Logout</a></li>

                        </ul>
                    </div>

                </div>
            </nav>
        </header>

        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            @include('admin.layouts.sidebar')
        </aside>
        @yield('content')


        <footer class="main-footer">
            <strong>Copyright &copy; {{ date("Y") }}
                <a>Flash</a>.
            </strong> All rights reserved.
        </footer>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <!-- ckeditor -->
    <script src="{{ asset('assets/admin/ckeditor/ckeditor.js')  }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button);

    </script>
    <script src="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.sidebar-toggle').click(function() {
                $('.sidebar-toggle').toggleClass('active');
                $('.main-header').toggleClass('header_fixed');
                $('body').toggleClass('body_fixed');
                $('.main-sidebar').toggleClass('show');
            });
            
            $('.has-sub-menus-openbtn').click(function(){
                var gettarget =  $('.has-sub-menus');
                gettarget.removeClass('active');
                $(this).parents(gettarget).toggleClass('active');
            });
            $('.has-sub-menus-closebtn').click(function(){
                var gettarget =  $('.has-sub-menus');
                $(this).parents(gettarget).removeClass('active');
            });
            
        });

    </script>
    @yield('customScript')
</body>

</html>
