<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts y estilos -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="@yield('body_class', 'hold-transition') d-flex flex-column min-vh-100">

    <!-- Contenedor principal centrado -->
    <div class="flex-grow-1 d-flex justify-content-center align-items-center">
        <div class="w-100" style="max-width: 400px;">
            @yield('content')
        </div>
    </div>

    <!-- Footer fijo abajo -->
    <footer class=" d-flex justify-content-between bg-light text-center py-3 mt-auto w-100 p-2">
        <strong>&copy; {{ date('Y') }} </strong>
        <span>IES6001</span>
    </footer>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    @yield('js')
</body>

</html>