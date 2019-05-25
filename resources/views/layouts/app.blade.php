<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('public/js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <!-- For Material Icons -->
    <link href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons' rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('public/css/app.css') }}" rel="stylesheet">

</head>
<body>
    <div id="app">
        
        <v-toolbar>
            <v-toolbar-side-icon></v-toolbar-side-icon>
            <v-toolbar-title> LChat</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items class="hidden-sm-and-down">
                @guest
                    <v-btn flat href="{{ route('login') }}">{{ __('Login') }}</v-btn>
                    @if (Route::has('register'))
                        <v-btn flat href="{{ route('register') }}">{{ __('Register') }}</v-btn>
                    @endif
                @else
                    <v-btn flat href="{{route('home')}}"> Group</v-btn>
                    <v-btn flat href="{{route('private')}}"> Private</v-btn>
                    <v-btn flat>{{ Auth::user()->name }}</v-btn>
                    <v-btn flat @click=" $refs.logoutForm.submit(); ">{{ __('Logout') }}</v-btn>
                @endguest
                <form ref="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </v-toolbar-items>
        </v-toolbar>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>




