@extends('layouts.auth')
@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-2">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('admin.users.store') }}" method="POST">
                            @csrf
                            <!-- Tên người dùng -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Tên người dùng</label>
                                <input type="text" class="form-control form-control-lg fw-bold" id="name" name="name"
                                    placeholder="Nhập tên người dùng" required value="{{ old('name') }}">
                            </div>
                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Nhập email" required value="{{ old('email') }}">
                            </div>
                            <!-- Mật khẩu -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Nhập mật khẩu (tối thiểu 8 ký tự)" required>
                            </div>
                            <!-- Xác nhận mật khẩu -->
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                                    placeholder="Nhập lại mật khẩu" required>
                            </div>
                            <!-- Vai trò -->
                            <div class="mb-4">
                                <label for="role" class="form-label">Vai trò</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="">Chọn vai trò</option>
                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                    <option value="ceo" {{ old('role') == 'ceo' ? 'selected' : '' }}>CEO</option>
                                </select>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success px-4">Lưu</button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary px-4">Quay lại</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection