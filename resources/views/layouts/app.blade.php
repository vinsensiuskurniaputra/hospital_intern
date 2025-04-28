<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Magang Rumah Sakit')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/app.css')
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tom-select/2.2.2/css/tom-select.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tom-select/2.2.2/js/tom-select.complete.min.js"></script>
    @stack('styles')
</head>

<body class="bg-gray-100 overflow-hidden">
    @yield('base-content')
    @stack('scripts')
</body>


</html>
