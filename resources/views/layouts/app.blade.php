<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ App\Company::findOrFail(1)->name }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
    <script src="/js/jquery-ui.min.js"></script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="/">
                        {{ App\Company::findOrFail(1)->name }}
                    </a>
                </div>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="/login">Login</a></li>
                        <li><a href="/register">Register</a></li>
                    @else
                        <li>
                            <a href="/profile" class="" style="padding: 9px;">
                                <img src="{{ Auth::user()->avatar }}" style="max-width:32px; max-height:32px; border-radius:50%">
                            </a>
                        </li>
                        <li class="dropdown @if ($view_name == 'profile') active @endif ">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                @if (Auth::check() && Auth::user()->hasRole('registered'))
                                    <li @if ($view_name == 'profile') class="active" @endif >
                                        <a href="/profile">My Profile</a>
                                    </li>
                                    <li class="divider"></li>
                                @endif
                                <li>
                                    <a href="/logout"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="/logout" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        <li @if ($view_name == 'home') class="active" @endif ><a href="/home">Home</a></li>

                        @if (Auth::check() && Auth::user()->hasRole('editor'))
                            <li @if ($view_name == 'tiles') class="active" @endif ><a href="/tiles">Tiles</a></li>
                            <li @if ($view_name == 'filters') class="active" @endif ><a href="/filters">Filters</a></li>

                            @if (config('app.use_product_category'))
                                <li @if ($view_name == 'categories') class="active" @endif ><a href="/categories">Categories</a></li>
                            @endif

                            @if (config('app.engine_2d_enabled'))
                                <li @if ($view_name == '2d.rooms') class="active" @endif ><a href="/rooms2d">
                                    @if (config('app.engine_3d_enabled')) 2D @endif
                                    Rooms
                                </a></li>
                            @endif

                            @if (config('app.engine_3d_enabled'))
                                <li @if ($view_name == '3d.rooms') class="active" @endif ><a href="/rooms">
                                    @if (config('app.engine_2d_enabled')) 3D @endif
                                    Rooms
                                </a></li>
                            @endif

                            @if (config('app.engine_panorama_enabled'))
                                <li @if ($view_name == 'panorama.rooms') class="active" @endif ><a href="/panoramas">
                                        Panorama
                                    </a></li>
                            @endif
                        @endif

                        @if (Auth::check() && Auth::user()->hasRole('administrator'))
                            <li @if ($view_name == 'users') class="active" @endif ><a href="/users">Users</a></li>

                            <li class="dropdown @if ($view_name == 'surfacetypes' || $view_name == 'roomtypes' || $view_name == 'appsettings') active @endif ">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    Settings <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li @if ($view_name == 'surfacetypes') class="active" @endif ><a href="/surfacetypes">Surface Types</a></li>
                                    <li @if ($view_name == 'roomtypes') class="active" @endif ><a href="/roomtypes">Room Types</a></li>
                                    <li class="divider"></li>
                                    <li @if ($view_name == 'appsettings') class="active" @endif ><a href="/appsettings">App Settings</a></li>

                                    @if (config('app.needs_storage_link'))
                                        <li class="divider"></li>
                                        <li><a href="/storage-link">Storage Link</a></li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
        @yield('scripts')
    </div>

    <!-- Scripts -->
    
</body>
</html>
