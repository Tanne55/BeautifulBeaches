<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{ config('app.name', 'Beautiful Beaches') }}</title> --}}
    <title>Beautiful Beaches</title>
    <!-- Scripts and Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/guest.css', 'resources/js/guest.js'])

</head>

<body>
    {{-- Social Media --}}
    @include('layouts.social_media')


    {{-- Header --}}
    @include('layouts.header')



    {{-- Nội dung động của từng trang --}}
    <main class="">
        <div class="">
            @yield('content')
        </div>
    </main>

    {{-- Footer --}}
    @include('layouts.footer')


</body>

</html>