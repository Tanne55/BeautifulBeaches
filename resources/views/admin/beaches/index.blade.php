@extends('layouts.auth')
@section('content')
    <div style="background: linear-gradient(135deg, #ffffff 0%, #c1d5f5 100%);">
        <div class="container py-4 container-custom ">
            <!-- Header Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h1 class="mb-1">
                                <i class="bi bi-water text-primary me-2"></i>Quản lý Bãi Biển
                            </h1>
                            <p class="text-muted mb-0">Quản lý thông tin các bãi biển trong hệ thống</p>
                        </div>
                        <a href="{{ route('admin.beaches.create') }}" class="btn btn-lg shadow"
                            style="background: linear-gradient(135deg, #a5bef2 0%, #21adee 100%);">
                            <i class="bi bi-plus-circle me-2"></i>Thêm Bãi Biển
                        </a>
                    </div>

                    <!-- Success Alert -->
                    @if(session('success'))
                        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <strong>Thành công!</strong> {{ session('success') }}
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class=" border-0 shadow-sm rounded border-top" style="overflow: hidden;">
                <div class="card-header border-0">
                    <h5 class="mx-3 my-3"><i class="bi bi-funnel me-2"></i>Tìm kiếm & Lọc</h5>
                    <div class="mx-3" style="height:1px; background:#bcb5b5; border-radius:9999px; margin:8px 0;">
                    </div>
                </div>

                <div class="card-body p-4">
                    <form method="GET" action="{{ route('admin.beaches.index') }}" class="row g-3">
                        <!-- Search Input -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-search text-primary me-1"></i>Tìm kiếm
                            </label>
                            <input type="text" class="form-control border-0 shadow-sm" name="search"
                                value="{{ request('search') }}" placeholder="Tìm theo tên, mô tả..."
                                style="background: #f8f9fa;">
                        </div>

                        <!-- Region Filter -->
                        <div class="col-md-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-geo-alt text-warning me-1"></i>Vùng
                            </label>
                            <select class="form-select border-0 shadow-sm" name="region" style="background: #f8f9fa;">
                                <option value="all">Tất cả vùng</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region->id }}" {{ request('region') == $region->id ? 'selected' : '' }}>
                                        {{ $region->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div class="col-md-2">
                            <label class="form-label fw-bold">
                                <i class="bi bi-sort-down text-info me-1"></i>Sắp xếp
                            </label>
                            <select class="form-select border-0 shadow-sm" name="sort_by" style="background: #f8f9fa;">
                                <option value="created_at" {{ request('sort_by', 'created_at') == 'created_at' ? 'selected' : '' }}>Ngày tạo</option>
                                <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Tên A-Z</option>
                                <option value="region" {{ request('sort_by') == 'region' ? 'selected' : '' }}>Vùng</option>
                            </select>
                        </div>

                        <!-- Sort Order -->
                        <div class="col-md-2">
                            <label class="form-label fw-bold">
                                <i class="bi bi-arrow-down-up text-success me-1"></i>Thứ tự
                            </label>
                            <select class="form-select border-0 shadow-sm" name="sort_order" style="background: #f8f9fa;">
                                <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>Giảm dần
                                </option>
                                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Tăng dần</option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-md-1">
                            <label class="form-label fw-bold text-transparent">Action</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100" title="Tìm kiếm">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Clear Filters -->
                    @if(request()->hasAny(['search', 'region', 'sort_by', 'sort_order']))
                        <div class="mt-3 pt-3 ">
                            <a href="{{ route('admin.beaches.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-x-circle me-1"></i>Xóa bộ lọc
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            <!-- Results Section -->
            <div class="row my-3">
                <div class="col-md-6">
                    <p class="text-muted mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Hiển thị {{ $beaches->firstItem() ?? 0 }}-{{ $beaches->lastItem() ?? 0 }}
                        trong tổng số {{ $beaches->total() }} bãi biển
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    @if(request()->hasAny(['search', 'region']))
                        <span class="badge bg-info">
                            <i class="bi bi-filter me-1"></i>Đã lọc
                        </span>
                    @endif
                </div>
            </div>

            <!-- Data Table -->
            <div class=" border-top shadow-sm p-4">
                <div class="card-header bg-gradient text-white"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="text-dark ">
                        <i class="bi bi-table me-2"></i>Danh sách bãi biển
                    </h5>
                </div>

                @if($beaches->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0" width="60">
                                        <i class="bi bi-image text-muted"></i>
                                    </th>
                                    <th class="border-0">
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'title', 'sort_order' => request('sort_by') == 'title' && request('sort_order', 'desc') == 'desc' ? 'asc' : 'desc']) }}"
                                            class="text-decoration-none fw-bold">
                                            Tên bãi biển
                                            @if(request('sort_by') == 'title')
                                                <i
                                                    class="bi bi-arrow-{{ request('sort_order', 'desc') == 'desc' ? 'down' : 'up' }}"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="border-0">
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'region', 'sort_order' => request('sort_by') == 'region' && request('sort_order', 'desc') == 'desc' ? 'asc' : 'desc']) }}"
                                            class="text-decoration-none fw-bold">
                                            Vùng
                                            @if(request('sort_by') == 'region')
                                                <i
                                                    class="bi bi-arrow-{{ request('sort_order', 'desc') == 'desc' ? 'down' : 'up' }}"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="border-0">Thông tin</th>
                                    <th class="border-0">
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => request('sort_by', 'created_at') == 'created_at' && request('sort_order', 'desc') == 'desc' ? 'asc' : 'desc']) }}"
                                            class="text-decoration-none fw-bold">
                                            Ngày tạo
                                            @if(request('sort_by', 'created_at') == 'created_at')
                                                <i
                                                    class="bi bi-arrow-{{ request('sort_order', 'desc') == 'desc' ? 'down' : 'up' }}"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="border-0 text-center" width="120">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($beaches as $beach)
                                    <tr class="align-middle">
                                        <!-- Image -->
                                        <td>
                                            @php
                                                // Thứ tự ưu tiên: field image > primary image > first image > placeholder
                                                $imageUrl = null;

                                                if ($beach->image) {
                                                    // Ưu tiên 1: field image trong bảng beaches
                                                    $imageUrl = $beach->image;
                                                    if (!str_starts_with($imageUrl, 'http') && !str_starts_with($imageUrl, '/assets')) {
                                                        $imageUrl = asset('storage/' . (str_starts_with($imageUrl, 'beaches/') ? $imageUrl : 'beaches/' . $imageUrl));
                                                    }
                                                } elseif ($beach->images && $beach->images->where('is_primary', true)->first()) {
                                                    // Ưu tiên 2: ảnh primary trong beach_images
                                                    $primaryImg = $beach->images->where('is_primary', true)->first();
                                                    $imageUrl = $primaryImg->image_url;
                                                    if (!str_starts_with($imageUrl, 'http') && !str_starts_with($imageUrl, '/assets')) {
                                                        $imageUrl = asset('storage/' . (str_starts_with($imageUrl, 'beaches/') ? $imageUrl : 'beaches/' . $imageUrl));
                                                    }
                                                } elseif ($beach->images && $beach->images->first()) {
                                                    // Ưu tiên 3: ảnh đầu tiên trong beach_images
                                                    $firstImg = $beach->images->first();
                                                    $imageUrl = $firstImg->image_url;
                                                    if (!str_starts_with($imageUrl, 'http') && !str_starts_with($imageUrl, '/assets')) {
                                                        $imageUrl = asset('storage/' . (str_starts_with($imageUrl, 'beaches/') ? $imageUrl : 'beaches/' . $imageUrl));
                                                    }
                                                } else {
                                                    // Fallback: placeholder
                                                    $imageUrl = 'https://via.placeholder.com/60x40/e3f2fd/1976d2?text=Beach';
                                                }
                                            @endphp
                                            <img src="{{ $imageUrl }}" alt="{{ $beach->title }}" class="rounded shadow-sm"
                                                style="width: 60px; height: 40px; object-fit: cover;">
                                        </td>

                                        <!-- Title -->
                                        <td>
                                            <div>
                                                <h6 class="mb-1 fw-bold">{{ $beach->title }}</h6>
                                                <small class="text-muted">
                                                    {{ Str::limit($beach->short_description, 50) }}
                                                </small>
                                            </div>
                                        </td>

                                        <!-- Region -->
                                        <td>
                                            <span class="badge bg-primary rounded-pill">
                                                <i class="bi bi-geo-alt me-1"></i>
                                                {{ $beach->region->name }}
                                            </span>
                                        </td>

                                        <!-- Info -->
                                        <td>
                                            <div class="small">
                                                <div class="mb-1">
                                                    <i class="bi bi-calendar text-info me-1"></i>
                                                    {{ $beach->created_at->diffForHumans() }}
                                                </div>
                                                <div>
                                                    <i class="bi bi-eye text-success me-1"></i>
                                                    <a href="{{ route('beaches.show', $beach->id) }}"
                                                        class="text-decoration-none small" target="_blank">
                                                        Xem trang
                                                    </a>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Created Date -->
                                        <td>
                                            <div class="small text-muted">
                                                <div>{{ $beach->created_at->format('d/m/Y') }}</div>
                                                <div>{{ $beach->created_at->format('H:i') }}</div>
                                            </div>
                                        </td>

                                        <!-- Actions -->
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.beaches.edit', $beach->id) }}"
                                                    class="btn btn-outline-warning btn-sm" title="Chỉnh sửa">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                    onclick="confirmDelete({{ $beach->id }}, '{{ $beach->title }}')" title="Xóa">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                        </div>
                        <h4 class="text-muted">Không tìm thấy bãi biển nào</h4>
                        <p class="text-muted mb-4">
                            @if(request()->hasAny(['search', 'region']))
                                Thử thay đổi điều kiện tìm kiếm hoặc
                                <a href="{{ route('admin.beaches.index') }}" class="text-decoration-none">xóa bộ lọc</a>
                            @else
                                Bắt đầu bằng cách thêm bãi biển đầu tiên
                            @endif
                        </p>
                        @if(!request()->hasAny(['search', 'region']))
                            <a href="{{ route('admin.beaches.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Thêm Bãi Biển Đầu Tiên
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if($beaches->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $beaches->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white border-0">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle me-2"></i>Xác nhận xóa
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center">
                        <i class="bi bi-trash3 display-1 text-danger mb-3"></i>
                        <h4>Bạn có chắc chắn muốn xóa?</h4>
                        <p class="text-muted mb-0">
                            Bãi biển <strong id="beachName"></strong> sẽ được xóa vĩnh viễn.
                            <br>Thao tác này không thể hoàn tác.
                        </p>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Hủy
                    </button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="bi bi-trash me-2"></i>Xóa vĩnh viễn
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- JavaScript -->
    <script>
        // Delete confirmation
        function confirmDelete(beachId, beachName) {
            document.getElementById('beachName').textContent = beachName;
            document.getElementById('deleteForm').action = '{{ route("admin.beaches.destroy", ":id") }}'.replace(':id', beachId);

            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        // Auto-submit form when filters change
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form[method="GET"]');
            const autoSubmitElements = form.querySelectorAll('select[name="region"], select[name="sort_by"], select[name="sort_order"]');

            autoSubmitElements.forEach(element => {
                element.addEventListener('change', function () {
                    // Add loading state
                    this.classList.add('loading');
                    form.submit();
                });
            });

            // Search with debounce
            const searchInput = form.querySelector('input[name="search"]');
            let searchTimeout;

            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    form.submit();
                }, 500); // Wait 500ms after user stops typing
            });

            // Highlight active filters
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('search') && urlParams.get('search')) {
                searchInput.classList.add('border-primary', 'bg-primary', 'bg-opacity-10');
            }

            autoSubmitElements.forEach(element => {
                if (element.value && element.value !== 'all' && element.value !== 'created_at' && element.value !== 'desc') {
                    element.classList.add('border-primary', 'bg-primary', 'bg-opacity-10');
                }
            });

            // Smooth scroll to top after form submission
            if (urlParams.toString()) {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });

        // Toast notification for successful actions
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', function () {
                const toast = document.createElement('div');
                toast.className = 'position-fixed top-0 end-0 p-3';
                toast.style.zIndex = '9999';
                toast.innerHTML = `
                                                                                                                                                                                                                                                                                                                            <div class="toast show" role="alert">
                                                                                                                                                                                                                                                                                                                                <div class="toast-body bg-success text-white rounded">
                                                                                                                                                                                                                                                                                                                                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                        `;
                document.body.appendChild(toast);
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            });
        @endif
    </script>
@endsection