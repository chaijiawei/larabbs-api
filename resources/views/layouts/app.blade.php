<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @stack('style')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item @if(request()->routeIs('topics.index')) active @endif">
                            <a class="nav-link" href="{{ route('topics.index') }}">话题</a>
                        </li>
                        @include('shared._category_nav', ['categories' => \App\Models\Category::all()])
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a href="{{ route('topics.create') }}" class="nav-link">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a href="{{ route('notification.show') }}" class="nav-link
                                @if(Auth::user()->notify_count > 0)
                                    text-danger
                                @endif
                                ">
                                    <i class="fa fa-inbox"></i>
                                    {{ Auth::user()->notify_count }}
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <img class="rounded-circle" width="32" src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}">
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('users.show', Auth::user()) }}">个人中心</a>
                                    <a class="dropdown-item" href="{{ route('users.edit', Auth::user()) }}">编辑</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                @include('flash::message')
            </div>

            @yield('content')
        </main>

        <footer class="container border-top">
            <div class="row mt-2">
                <div class="col-md-6">
                    由 chai.jiawei 编写
                </div>

                <div class="col-md-6 text-right">
                    我是最pang(棒)的
                </div>
            </div>
        </footer>
    </div>
</body>

@if (config('app.debug'))
    @include('sudosu::user-selector')
@endif

<!-- Scripts -->
<script src="{{ mix('js/app.js') }}"></script>
@stack('script')
</html>
