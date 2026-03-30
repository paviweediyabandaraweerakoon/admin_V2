<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Meta -->
    <meta name="description" content="This system developed and maintained by {{env('APP_COMPANY')}}">
    <meta name="author" content="{{env('APP_DEVELOPER')}}">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset(env('APP_FAVICON')) }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | @yield('title')</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="{{ asset('plugins/nunito/nunito.css') }}" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/jquery-toast-plugin/jquery.toast.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Other styles -->
@stack('styles')
<!-- DashForge CSS -->
    <link rel="stylesheet" href="{{asset('plugins/dashforge/css/dashforge.css')}}">
    @stack('dashforge-css')
    <link rel="stylesheet" href="{{asset('plugins/dashforge/css/skin.dark.css')}}">
</head>
<body>
<div id="app">

    <div id="content-wrap">
        @include('layouts.includes.navbar')
        <div class="content content-fixed content-auth-alt">
            <div id="ajx_load" class="d-none">
                <div class="loading"></div>
            </div>
            @yield('content', 'Default Content')
        </div><!-- content -->
    </div>
    @include('layouts.includes.footer')

    <script src="{{asset('plugins/feather-icons/feather.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-toast-plugin/jquery.toast.min.js')}}"></script>
    <script src="{{asset('plugins/dashforge/js/dashforge.js')}}"></script>
    <script src="{{asset('js/notifications.js')}}"></script>

    <!-- append theme customizer -->
    <script>
        $(function () {
            'use script'

            feather.replace();

            @if(Session::has('message'))
            let type = "{{ Session::get('alert-type', 'info') }}";
            let msg = "{{ Session::get('message') }}";
            switch (type) {
                case 'info':
                    Notifications.showSuccessMsg(msg);
                    break;
                case 'warning':
                    Notifications.showSuccessMsg(msg);
                    break;
                case 'success':
                    Notifications.showSuccessMsg(msg);
                    break;
                case 'error':
                    Notifications.showErrorMsg(msg);
                    break;
            }
            @endif
                window.darkMode = function () {
                $('.btn-white').addClass('btn-dark').removeClass('btn-white');
            }
            window.lightMode = function () {
                $('.btn-dark').addClass('btn-white').removeClass('btn-dark');
            }
            var hasMode = 'dark';
            if (hasMode === 'dark') {
                darkMode();
            } else {
                lightMode();
            }
        });
        $(document).ajaxSend(function () {
            $('#ajx_load').removeClass('d-none');
        });
        $(document).ajaxComplete(function () {
            $('#ajx_load').addClass('d-none');
        });

        @if (Auth::user())

        var idleTime = 0;

        function timerIncrement() {
            idleTime = idleTime + 1;
            let session_time = {{config("session.lifetime")}};
            if (idleTime >= session_time) {
                $.ajax({
                    url: '/logout',
                    type: 'POST'
                });
                location.href = "/";
                Notifications.showErrorMsg("Session expired. You'll be take to the login page");
            }
        }

        //Increment the idle time counter every minute.
        let idleInterval = setInterval(timerIncrement, 60 * 1000); // 1 minute

        //Zero the idle timer on mouse movement.
        $(this).mousemove(function (e) {
            idleTime = 0;
        });
        $(this).keypress(function (e) {
            idleTime = 0;
        });


        @endif
    </script>

    @stack('scripts')
</div>
</body>
</html>
