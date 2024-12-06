<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Pharmaceutical Research Product Management</title>

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Prevent FOUC -->
    <style>
        [v-cloak] {
            display: none;
        }
    </style>
</head>
<body class="antialiased">
    <div id="app" v-cloak></div>

    <script>
        window.config = {
            baseUrl: '{{ url('/') }}',
            apiUrl: '{{ url('/api') }}',
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
</body>
</html>
