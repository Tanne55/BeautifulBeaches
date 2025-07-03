@extends('layouts.auth')
@section('content')
    <div class="container py-4 container-custom">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Danh sách Tour</h1>
            <a href="{{ route('ceo.tours.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm tour mới
            </a>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <div class="">
            <div class=" p-2">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center table-hover mb-0">
                        <thead class="table-primary align-middle">
                            <tr>
                                <th rowspan="2" class="text-center" style="width: 50px;">ID</th>
                                <th rowspan="2" class="text-start">Tên tour</th>
                                <th rowspan="2" class="text-center">Bãi biển</th>
                                <th colspan="2" class="text-center">Thời gian</th>
                                <th rowspan="2" class="text-center">Giá (triệu)</th>
                                <th rowspan="2" class="text-center">Sức chứa</th>
                                <th rowspan="2" class="text-center">Trạng thái</th>
                                <th rowspan="2" class="text-center">CEO</th>
                                <th rowspan="2" class="text-center">Hành động</th>
                            </tr>
                            <tr>
                                <th class="text-center">Ngày đi</th>
                                <th class="text-center">Ngày về</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tours as $tour)
                                <tr>
                                    <td>{{ $tour->id }}</td>
                                    <td class="text-start">{{ $tour->title }}</td>
                                    <td>{{ $tour->beach ? $tour->beach->title : '' }}</td>
                                    <td>{{ $tour->detail->departure_time ? \Carbon\Carbon::parse($tour->detail->departure_time)->format('d/m/Y H:i') : '' }}</td>
                                    <td>{{ $tour->detail->return_time ? \Carbon\Carbon::parse($tour->detail->return_time)->format('d/m/Y H:i') : '' }}</td>
                                    <td>{{ number_format($tour->price, 0, ',', '.') }}</td>
                                    <td>{{ $tour->capacity }}</td>
                                    <td>
                                        @if($tour->status === 'active')
                                            <span class="badge px-3 py-2 bg-success">Hoạt động</span>
                                        @elseif($tour->status === 'inactive')
                                            <span class="badge px-3 py-2 bg-secondary">Ẩn</span>
                                        @elseif($tour->status === 'outdated')
                                            <span class="badge px-3 py-2 bg-danger">Hết hạn</span>
                                        @else
                                            <span class="badge px-3 py-2 bg-dark">Không xác định</span>
                                        @endif
                                    </td>
                                    <td><span class="fw-bold text-primary">{{ $tour->ceo->name ?? $tour->ceo_id ?? '' }}</span></td>
                                    <td>
                                        <a href="{{ route('ceo.tours.edit', $tour->id) }}" class="btn btn-warning btn-sm" title="Sửa">
                                            <i class="bi bi-pencil-square"></i> Sửa
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4 py-2">
            <div class="text-muted">
                Hiển thị {{ $tours->firstItem() ?? 0 }} - {{ $tours->lastItem() ?? 0 }} trong tổng số {{ $tours->total() }} tour
            </div>
            <div>
                {{ $tours->links() }}
            </div>
        </div>
        
        
    </div>
@endsection