<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{ config('app.name', 'Beautiful Beaches') }}</title> --}}
    <title>Beautiful Beaches</title>
    <!-- Scripts and Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite('resources/css/auth.css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>



<body class="d-flex flex-column min-vh-100">

    {{-- sidebar --}}
    @include('layouts.sidebar')


    {{-- Nội dung động của từng trang --}}
    <main class="flex-grow-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')


    {{-- Load JS ở cuối body --}}
    @vite('resources/js/auth.js')
    @stack('scripts')
    @yield('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>