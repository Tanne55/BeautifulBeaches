@extends('layouts.auth')
@section('content')
    <div class="container">
        <h1>Danh sách Tour</h1>
        <a href="{{ route('ceo.tours.create') }}" class="btn btn-primary mb-3">Thêm tour mới</a>
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
                    <th>Tên tour</th>
                    <th>Bãi biển</th>
                    <th>Giá</th>
                    <th>Sức chứa</th>
                    <th>Thời lượng</th>
                    <th>Trạng thái</th>
                    <th>CEO</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tours as $tour)
                    <tr>
                        <td>{{ $tour->id }}</td>
                        <td>{{ $tour->title }}</td>
                        <td>{{ $tour->beach ? $tour->beach->title : '' }}</td>
                        <td>{{ number_format($tour->price, 0, ',', '.') }} đ</td>
                        <td>{{ $tour->capacity }}</td>
                        <td>{{ $tour->duration_days }} ngày</td>
                        
                        <td>
                            <span class="badge bg-{{ $tour->status === 'active' ? 'success' : 'secondary' }}">
                                {{ $tour->status === 'active' ? 'Hoạt động' : 'Ẩn' }}
                            </span>
                        </td>
                        <td>{{ $tour->ceo->name ?? $tour->ceo_id ?? '' }}</td>
                        <td>
                            <a href="{{ route('ceo.tours.edit', $tour->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection