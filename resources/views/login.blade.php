@extends('layouts.guest')

@section('content')
    <div class="row justify-content-center container m-5 ms-auto">
        <div class="col-6 d-flex justify-content-center">
            <div class="card shadow-sm w-75">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4">Đăng nhập vào tài khoản của bạn</h2>
                    <p class="text-center text-muted mb-4">Chào mừng bạn quay lại với Beautiful Beaches</p>

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <form action="{{ route('login') }}" method="POST" class="w-100">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" name="email" type="email"
                                class="form-control @error('email') is-invalid @enderror" required autocomplete="email"
                                value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mật Khẩu</label>
                            <input id="password" name="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" required
                                autocomplete="current-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                                <label class="form-check-label" for="remember_me">
                                    Ghi nhớ đăng nhập
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                Đăng nhập
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('register') }}" class="text-decoration-none">
                                Chưa có tài khoản? Đăng kí!
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection