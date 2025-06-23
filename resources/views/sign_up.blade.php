@extends('layouts.guest')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 d-flex justify-content-center">
            <div class="card shadow-sm w-75">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4">Create your account</h2>
                    <p class="text-center text-muted mb-4">Join Beautiful Beaches today</p>

                    <form action="{{ route('register') }}" method="POST" class="w-100">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input id="name" name="name" type="text"
                                class="form-control @error('name') is-invalid @enderror" required value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input id="email" name="email" type="email"
                                class="form-control @error('email') is-invalid @enderror" required autocomplete="email"
                                value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" name="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" required
                                autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                class="form-control" required autocomplete="new-password">
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select id="role" name="role" class="form-select" required>
                                <option value="" disabled selected>-- Chọn vai trò --</option>
                                <option value="user">User</option>
                                <option value="ceo">CEO</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>


                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                Sign up
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="text-decoration-none">
                                Already have an account? Log in
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection