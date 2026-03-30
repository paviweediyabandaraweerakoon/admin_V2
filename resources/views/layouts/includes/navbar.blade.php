<header class="navbar navbar-header navbar-header-fixed">
    <a href="#" id="mainMenuOpen" class="burger-menu"><i data-feather="menu"></i></a>
    <div class="navbar-brand">
        <a href="{{ url('/home') }}" class="df-logo"><img src="{{ asset('img/myl-logo-icon.png') }}" alt=""></a>
    </div><!-- navbar-brand -->
    <div id="navbarMenu" class="navbar-menu-wrapper">
        <div class="navbar-menu-header">
            <a href="{{ url('/home') }}" class="df-logo">{{ asset(env('APP_LOGO')) }}</a>
            <a id="mainMenuClose" href="#"><i data-feather="x"></i></a>
        </div><!-- navbar-menu-header -->

        <ul class="nav navbar-menu">
            {!! $menux !!}
        </ul>


    </div><!-- navbar-menu-wrapper -->
    <div class="navbar-right">

        {{-- If not logged in --}}
        @if (Auth::guest())
            <a href="https://www.facebook.com/Mylinex/" class="btn btn-social"><i class="fab fa-facebook"></i></a>
            <a href="https://twitter.com/MylinexIntl" class="btn btn-social"><i class="fab fa-twitter"></i></a>

            {{-- If logged in --}}
        @else
{{--            <div class="dropdown dropdown-message">--}}
{{--                <a href="#" class="dropdown-link new-indicator" data-toggle="dropdown">--}}
{{--                    <i data-feather="message-square"></i>--}}
{{--                    <span>0</span>--}}
{{--                </a>--}}
{{--                <div class="dropdown-menu dropdown-menu-right">--}}
{{--                    <div class="dropdown-header">New Messages</div>--}}
{{--                </div><!-- dropdown-menu -->--}}
{{--            </div><!-- dropdown -->--}}
{{--            <div class="dropdown dropdown-notification">--}}
{{--                <a href="#" class="dropdown-link new-indicator" data-toggle="dropdown">--}}
{{--                    <i data-feather="bell"></i>--}}
{{--                    <span>0</span>--}}
{{--                </a>--}}
{{--                <div class="dropdown-menu dropdown-menu-right">--}}
{{--                    <div class="dropdown-header">Notifications</div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        --}}
            <div class="dropdown dropdown-profile">
                <a href="#" class="dropdown-link" data-toggle="dropdown" data-display="static">
                    <div class="avatar avatar-sm"><img src="{{ asset('img/img1.png') }}" class="rounded-circle" alt="">
                    </div>
                </a><!-- dropdown-link -->
                <div class="dropdown-menu dropdown-menu-right tx-13">
                    <div class="avatar avatar-lg mg-b-15"><img src="{{ asset('img/img1.png') }}" class="rounded-circle"
                                                               alt=""></div>
                    <h6 class="tx-semibold mg-b-5">{{ Auth::user()->name }}</h6>
                    <p class="mg-b-25 tx-12 tx-color-03">{{ Auth::user()->getRoleNames()[0] }}</p>
                    <a class="dropdown-item" href="{{ route('users.profile') }}">
                        <i data-feather="user"></i>Profile</a>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('frm-logout').submit();"><i
                            data-feather="log-out"></i>Sign Out</a>
                </div><!-- dropdown-menu -->
            </div><!-- dropdown -->
            <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        @endif
    </div><!-- navbar-right -->
</header><!-- navbar -->
