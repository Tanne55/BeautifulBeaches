@extends('layouts.guest')

@section('content')
    <div class="row justify-content-center container m-5 ms-auto">
        <div class="col-6 d-flex justify-content-center">
            <div class="card shadow-sm w-75">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4">Quên mật khẩu</h2>
                    <p class="text-center text-muted mb-4">Nhập email của bạn để đặt lại mật khẩu</p>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('password.email') }}" method="POST" class="w-100">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" name="email" type="email"
                                class="form-control @error('email') is-invalid @enderror" required autocomplete="email"
                                value="{{ old('email') }}" placeholder="Nhập email của bạn">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                Xác nhận Email
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="text-decoration-none">
                                Quay lại đăng nhập
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
