@extends('layouts.auth')
@section('content')
    <div class="container">
        <h1>Danh sách người dùng</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">Thêm người dùng mới</a>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span
                                class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'ceo' ? 'warning' : 'info') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            @if(isset($user->is_banned) && $user->is_banned)
                                <span class="badge bg-danger">Đã ban</span>
                            @else
                                <span class="badge bg-success">Hoạt động</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($user->role == "admin")
                                <span class="badge bg-danger">Không hành động nào được thực thi</span>
                            @else
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                                
                                <!-- Ban/Unban button -->
                                <form action="{{ route('admin.users.ban', $user->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn {{ isset($user->is_banned) && $user->is_banned ? 'btn-success' : 'btn-danger' }} btn-sm"
                                        onclick="return confirm('{{ isset($user->is_banned) && $user->is_banned ? 'Bạn có chắc muốn mở ban người dùng này?' : 'Bạn có chắc muốn ban người dùng này?' }}')">
                                        {{ isset($user->is_banned) && $user->is_banned ? 'Mở ban' : 'Ban' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc muốn xóa người dùng này?')">Xóa</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection