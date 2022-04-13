<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    
     <!-- Scripts -->
     <script src="{{ asset('js/bootstrap.js') }}" defer></script>
     <script src="{{ asset('bower_components/adminlte/plugins/jquery/jquery.min.js') }}"></script>

    <!-- CKEditor -->
    <script src="{{ asset('bower_components/ckeditor/ckeditor.js') }}"></script>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('bower_components/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    
    <!-- IonIcons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('bower_components/adminlte/dist/css/adminlte.min.css') }}">

    <!-- Nofi toast -->
    <link href="{{ asset('bower_components/toastr/toastr.min.css') }}" rel="stylesheet">
    
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <div id="app">
        @include('layouts.admin.navigation')
        @include('layouts.admin.sidebar')

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <!-- Main Footer -->
    <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Anything you want
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<!-- <script src="{{ asset('bower_components/adminlte/plugins/jquery/jquery.min.js') }}"></script> -->
<!-- Bootstrap -->
<script src="{{ asset('bower_components/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE -->
<script src="{{ asset('bower_components/adminlte/dist/js/adminlte.min.js') }}"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="{{ asset('bower_components/adminlte/plugins/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('bower_components/toastr/toastr.min.js') }}"></script>
    <script>
        $(function(){
            @if(Session::has('success'))
                toastr.success("{{ Session::get('success') }}");
            @endif

            @if(Session::has('info'))
                toastr.info("{{ Session::get('info') }}");
            @endif

            @if(Session::has('warning'))
                toastr.warning("{{ Session::get('warning') }}");
            @endif

            @if(Session::has('error'))
                toastr.error("{{ Session::get('error') }}");
            @endif
        });
    </script>
</body>
</html>
