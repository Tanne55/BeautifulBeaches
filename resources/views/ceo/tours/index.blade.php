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
        
        <!-- Search and Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class=" border-0 shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="{{ route('ceo.tours.index') }}" id="filterForm">
                            <div class="row g-3">
                                <!-- Search -->
                                <div class="col-md-4">
                                    <label for="search" class="form-label">Tìm kiếm</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                        <input type="text" class="form-control" id="search" name="search" 
                                               placeholder="Tên tour, bãi biển..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                
                                <!-- Status Filter -->
                                <div class="col-md-2">
                                    <label for="status" class="form-label">Trạng thái</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">Tất cả</option>
                                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Ẩn</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Hết hạn</option>
                                    </select>
                                </div>
                                
                                <!-- Beach Filter -->
                                <div class="col-md-3">
                                    <label for="beach_id" class="form-label">Bãi biển</label>
                                    <select class="form-select" id="beach_id" name="beach_id">
                                        <option value="">Tất cả bãi biển</option>
                                        @if(isset($beaches))
                                            @foreach($beaches as $beach)
                                                <option value="{{ $beach->id }}" {{ request('beach_id') == $beach->id ? 'selected' : '' }}>
                                                    {{ $beach->title }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                
                                <!-- Sort -->
                                <div class="col-md-2">
                                    <label for="sort" class="form-label">Sắp xếp</label>
                                    <select class="form-select" id="sort" name="sort">
                                        <option value="id_desc" {{ request('sort') == 'id_desc' || !request('sort') ? 'selected' : '' }}>Mới nhất</option>
                                        <option value="id_asc" {{ request('sort') == 'id_asc' ? 'selected' : '' }}>Cũ nhất</option>
                                        <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Tên A-Z</option>
                                        <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Tên Z-A</option>
                                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá thấp</option>
                                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá cao</option>
                                    </select>
                                </div>
                                
                                <!-- Buttons -->
                                <div class="col-md-1">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-1">
                                        <button type="submit" class="btn btn-primary btn-sm" title="Lọc">
                                            <i class="bi bi-funnel"></i>
                                        </button>
                                        <a href="{{ route('ceo.tours.index') }}" class="btn btn-outline-secondary btn-sm" title="Xóa bộ lọc">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class=" border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive overflow-hidden">
                    <table class="table table-bordered align-middle text-center table-hover mb-0">
                        <thead class="table-primary align-middle">
                            <tr>
                                <th class="text-center" style="width: 50px;">ID</th>
                                <th class="text-start">Tên tour</th>
                                <th class="text-center">Bãi biển</th>
                                <th class="text-center">Ngày đi</th>
                                <th class="text-center">Giá</th>
                                <th class="text-center">Sức chứa</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">CEO</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tours as $tour)
                                <tr>
                                    <td>{{ $tour->id }}</td>
                                    <td class="text-start">
                                        <div class="fw-semibold">{{ $tour->title }}</div>
                                        @if($tour->detail && $tour->detail->highlights)
                                            <small class="text-muted">{{ Str::limit(implode(', ', array_slice($tour->detail->highlights, 0, 2)), 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($tour->beach)
                                            <span class="badge bg-light text-dark border">{{ $tour->beach->title }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $departureDates = [];
                                            if($tour->detail && $tour->detail->departure_dates) {
                                                foreach($tour->detail->departure_dates as $date) {
                                                    $departureDates[] = \Carbon\Carbon::parse($date)->format('d/m/Y');
                                                }
                                                // Loại bỏ trùng lặp và sắp xếp
                                                $departureDates = array_unique($departureDates);
                                                sort($departureDates);
                                            }
                                        @endphp
                                        
                                        @if(count($departureDates) > 0)
                                            <div class="d-flex flex-wrap gap-1 justify-content-center">
                                                @foreach(array_slice($departureDates, 0, 3) as $date)
                                                    <span class="badge bg-info text-dark">{{ $date }}</span>
                                                @endforeach
                                                @if(count($departureDates) > 3)
                                                    <span class="badge bg-secondary">+{{ count($departureDates) - 3 }}</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted"><em>Chưa có lịch</em></span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($tour->prices && $tour->prices->count() > 0)
                                            @php 
                                                $priceDetails = $tour->current_price_details;
                                            @endphp
                                            <div class="text-center">
                                                <span class="fw-bold text-danger">
                                                    {{ number_format($priceDetails['final_price'], 0, ',', '.') }} tr vnđ
                                                </span>
                                                @if($priceDetails['is_discounted'])
                                                    <br>
                                                    <small class="text-decoration-line-through text-muted">
                                                        {{ number_format($priceDetails['original_price'], 0, ',', '.') }} tr vnđ
                                                    </small>
                                                    <br>
                                                    <small class="badge bg-danger">-{{ number_format($priceDetails['discount'], 1) }}%</small>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">Chưa có giá</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $tour->capacity }} người</span>
                                    </td>
                                    <td>
                                        @if($tour->status === 'confirmed')
                                            <span class="badge bg-success">Hoạt động</span>
                                        @elseif($tour->status === 'pending')
                                            <span class="badge bg-warning text-dark">Ẩn</span>
                                        @elseif($tour->status === 'cancelled')
                                            <span class="badge bg-danger">Hết hạn</span>
                                        @else
                                            <span class="badge bg-secondary">Không xác định</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-primary">{{ $tour->ceo->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('ceo.tours.edit', $tour->id) }}" class="btn btn-warning btn-sm" title="Chỉnh sửa">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="#" class="btn btn-info btn-sm" title="Xem chi tiết">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            <p class="mb-0">Không tìm thấy tour nào</p>
                                            @if(request()->hasAny(['search', 'status', 'beach_id']))
                                                <small>Hãy thử thay đổi bộ lọc hoặc <a href="{{ route('ceo.tours.index') }}" class="text-decoration-none">xóa bộ lọc</a></small>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
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
    
    <script>
        // Auto submit form when select changes
        document.querySelectorAll('#status, #beach_id, #sort').forEach(function(element) {
            element.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });
        
        // Search with debounce
        let searchTimeout;
        document.getElementById('search').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                document.getElementById('filterForm').submit();
            }, 500);
        });
    </script>
@endsection