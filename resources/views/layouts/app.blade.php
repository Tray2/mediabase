<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen antialiased leading-none text-gray-800">
    <div id="app">
        <nav class="bg-blue-900 shadow pt-6 pb-2">
            <div class="container mx-auto px-6 md:px-0">
                <div class="flex items-center justify-center">
                    <div class="mr-6">
                        <a href="{{ url('/') }}" class="text-3xl font-semibold text-gray-400 no-underline">
                            {{ config('app.name', 'Laravel') }}
                        </a>
                        <a class="no-underline hover:underline text-gray-400 text-2xl p-3
                                  <?php if(request()->path() == 'books') echo 'italic'; ?>"
                            href="{{ route('books.index') }}">Books
                        </a>
                        <a class="no-underline hover:underline text-gray-400 text-2xl p-3
                                  <?php if(request()->path() == 'records') echo 'italic'; ?>"
                           href="{{ route('records.index') }}">Records
                        </a>
                    </div>
                    <div class="flex-1 text-right">
                        @guest
                            <a class="no-underline hover:underline text-gray-300 text-lg p-3" href="{{ route('login') }}">{{ __('Login') }}</a>
                            @if (Route::has('register'))
                                <a class="no-underline hover:underline text-gray-300 text-lg p-3" href="{{ route('register') }}">{{ __('Register') }}</a>
                            @endif
                        @else
                            <a href="{{ route('home') }}" class="no-underline hover:underline text-gray-300 text-lg p-3">{{ Auth::user()->name }}</a>

                            <a href="{{ route('logout') }}"
                               class="no-underline hover:underline text-gray-300 text-lg p-3"
                               onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                {{ csrf_field() }}
                            </form>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>
        @yield('subnav')
        @include('common.validation_errors')
        @include('common.success')
        <div class="container mx-auto">
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
