@php
    $isLoggedIn = Auth::check();
    $role = $isLoggedIn ? Auth::user()->role : null;
@endphp
@extends('layouts.auth')
@section('content')
<div class="container my-5 text-center">
    <h1 class="display-1 fw-bold text-warning">403</h1>
    <h2 class="mb-4">Không có quyền truy cập</h2>
    <p class="lead mb-4">Bạn không có quyền truy cập vào trang này.</p>
    @if($isLoggedIn)
        @if($role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Về trang quản trị Admin</a>
        @elseif($role === 'ceo')
            <a href="{{ route('ceo.dashboard') }}" class="btn btn-primary">Về trang quản trị CEO</a>
        @else
            <a href="{{ route('user.dashboard') }}" class="btn btn-primary">Về trang người dùng</a>
        @endif
    @else
        <a href="{{ route('home') }}" class="btn btn-primary">Về trang chủ</a>
        <a href="{{ route('login') }}" class="btn btn-outline-secondary ms-2">Đăng nhập</a>
    @endif
</div>
@endsection
