@extends('layouts.auth')
@section('content')
    <div class="container">
        <h1>Danh sách bãi biển</h1>
        <a href="{{ route('admin.beaches.create') }}" class="btn btn-primary mb-3">Thêm bãi biển mới</a>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tiêu đề</th>
                    <th>Khu vực</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($beaches as $beach)
                    <tr>
                        <td>{{ $beach->id }}</td>
                        <td>{{ $beach->title }}</td>
                        <td>{{ $beach->region }}</td>
                        <td>
                            <a href="{{ route('admin.beaches.edit', $beach->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                            <form action="{{ route('admin.beaches.destroy', $beach->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection