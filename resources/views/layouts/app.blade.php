<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-gray-50 text-gray-900">
    @include('layouts.partials.header')

    <main class="flex-1 container mx-auto px-4 py-8">
        @include('layouts.partials.alerts')
        @yield('content')
    </main>

    @include('layouts.partials.footer')
</body>
</html>
