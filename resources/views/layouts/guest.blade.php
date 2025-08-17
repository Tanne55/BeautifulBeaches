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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @vite('resources/css/guest.css')
    @stack('styles')
    @yield('head')
</head>

<body>
    {{-- Social Media --}}
    @include('layouts.social_media')


    {{-- Header --}}
    @include('layouts.header')



    {{-- Nội dung động của từng trang --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')


    {{-- Load JS ở cuối body --}}
    @vite('resources/js/guest.js')
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</html>