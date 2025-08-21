@extends('layouts.guest')

@section('content')
    <div class="row justify-content-center container m-5 ms-auto">
        <div class="col-6 d-flex justify-content-center">
            <div class="card shadow-sm w-75">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4">Đặt lại mật khẩu</h2>
                    <p class="text-center text-muted mb-4">Nhập mật khẩu mới cho email: <strong>{{ $email }}</strong></p>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('password.update') }}" method="POST" class="w-100">
                        @csrf

                        <input type="hidden" name="email" value="{{ $email }}">

                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu mới</label>
                            <input id="password" name="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" required
                                autocomplete="new-password" placeholder="Nhập mật khẩu mới (tối thiểu 8 ký tự)">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                class="form-control" required autocomplete="new-password" 
                                placeholder="Nhập lại mật khẩu mới">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                Đổi mật khẩu
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
