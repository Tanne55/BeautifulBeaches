@extends('layouts.auth')

@section('title', 'Quản lý Yêu cầu Hỗ trợ')

@section('content')
    <div class="container py-4 container-custom">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h1 class="mb-1">
                            <i class="bi bi-headset text-primary me-2"></i>Quản lý Yêu cầu Hỗ trợ
                        </h1>
                        <p class="text-muted mb-0">Theo dõi và xử lý các yêu cầu hỗ trợ từ khách hàng</p>
                    </div>
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
        <div class=" border-0 shadow-sm p-4 border-top">
            <div class="card-header bg-light border-0">
                <h5 class="mb-0"><i class="bi bi-funnel me-2"></i>Tìm kiếm & Lọc</h5>
            </div>
            <div class="card-body" style="padding-top:16px ">
                <form method="GET" action="{{ route('admin.support.index') }}" class="row g-3">
                    <!-- Search Input -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-search text-primary me-1"></i>Tìm kiếm
                        </label>
                        <input type="text" class="form-control border-0 shadow-sm" name="search"
                            value="{{ request('search') }}" placeholder="Tìm theo tên, email, tiêu đề..."
                            style="background: #f8f9fa;">
                    </div>

                    <!-- Status Filter -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-flag text-warning me-1"></i>Trạng thái
                        </label>
                        <select class="form-select border-0 shadow-sm" name="status" style="background: #f8f9fa;">
                            <option value="all">Tất cả trạng thái</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                🟡 Chờ xử lý
                            </option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>
                                🔵 Đang xử lý
                            </option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>
                                🟢 Đã giải quyết
                            </option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                🔴 Từ chối
                            </option>
                        </select>
                    </div>

                    <!-- Sort By -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold">
                            <i class="bi bi-sort-down text-info me-1"></i>Sắp xếp
                        </label>
                        <select class="form-select border-0 shadow-sm" name="sort_by" style="background: #f8f9fa;">
                            <option value="priority" {{ request('sort_by', 'priority') == 'priority' ? 'selected' : '' }}>Độ
                                ưu tiên</option>
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Ngày tạo
                            </option>
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                            <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email</option>
                        </select>
                    </div>

                    <!-- Sort Order -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold">
                            <i class="bi bi-arrow-down-up text-success me-1"></i>Thứ tự
                        </label>
                        <select class="form-select border-0 shadow-sm" name="sort_order" style="background: #f8f9fa;">
                            <option value="asc" {{ request('sort_order', 'asc') == 'asc' ? 'selected' : '' }}>Tăng dần
                            </option>
                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Giảm dần</option>
                        </select>
                    </div>

                    <!-- Action Button -->
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
                @if(request()->hasAny(['search', 'status', 'sort_by', 'sort_order']))
                    <div class="mt-3 pt-3 border-top">
                        <a href="{{ route('admin.support.index') }}" class="btn btn-outline-secondary btn-sm">
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
                    Hiển thị {{ $supportRequests->firstItem() ?? 0 }}-{{ $supportRequests->lastItem() ?? 0 }}
                    trong tổng số {{ $supportRequests->total() }} yêu cầu
                    <small class="text-muted ms-2">({{ $supportRequests->perPage() }} yêu cầu/trang)</small>
                </p>
            </div>
            <div class="col-md-6 text-end">
                @if($supportRequests->hasPages())
                    <span class="badge bg-secondary me-2">
                        <i class="bi bi-journal-text me-1"></i>Trang
                        {{ $supportRequests->currentPage() }}/{{ $supportRequests->lastPage() }}
                    </span>
                @endif
                @if(request()->hasAny(['search', 'status']))
                    <span class="badge bg-info">
                        <i class="bi bi-filter me-1"></i>Đã lọc
                    </span>
                @endif
            </div>
        </div>

        <!-- Data Table -->
        <div class=" border-0 shadow-sm">
            <div class="card-header bg-gradient text-white"
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="mb-0">
                    <i class="bi bi-table me-2"></i>Danh sách yêu cầu hỗ trợ
                </h5>
            </div>

            @if($supportRequests->count() > 0)
                <div class="">
                    <table class="table  mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => request('sort_by') == 'name' && request('sort_order', 'asc') == 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none fw-bold">
                                        <i class="bi bi-person me-1"></i>Người gửi
                                        @if(request('sort_by') == 'name')
                                            <i class="bi bi-arrow-{{ request('sort_order', 'asc') == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="border-0">
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'email', 'sort_order' => request('sort_by') == 'email' && request('sort_order', 'asc') == 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none fw-bold">
                                        <i class="bi bi-envelope me-1"></i>Email & SĐT
                                        @if(request('sort_by') == 'email')
                                            <i class="bi bi-arrow-{{ request('sort_order', 'asc') == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="border-0">
                                    <i class="bi bi-chat-square-text me-1"></i>Nội dung
                                </th>
                                <th class="border-0 text-center">
                                    <i class="bi bi-flag me-1"></i>Trạng thái
                                </th>
                                <th class="border-0">
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => request('sort_by') == 'created_at' && request('sort_order', 'desc') == 'desc' ? 'asc' : 'desc']) }}"
                                        class="text-decoration-none fw-bold">
                                        <i class="bi bi-calendar me-1"></i>Ngày tạo
                                        @if(request('sort_by') == 'created_at')
                                            <i
                                                class="bi bi-arrow-{{ request('sort_order', 'desc') == 'desc' ? 'down' : 'up' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="border-0 text-center" width="120">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($supportRequests as $request)
                                <tr class="">
                                    <!-- Name -->
                                    <td>
                                        <div>
                                            <div class="fw-bold text-dark">
                                                {{ $request->name ?? $request->user->name ?? 'Ẩn danh' }}
                                            </div>
                                            @if($request->user)
                                                <small class="text-muted">
                                                    <i class="bi bi-person-check me-1"></i>Thành viên
                                                </small>
                                            @else
                                                <small class="text-muted">
                                                    <i class="bi bi-person me-1"></i>Khách
                                                </small>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Email & Phone -->
                                    <td>
                                        <div>
                                            <div class="small">
                                                <i class="bi bi-envelope me-1 text-primary"></i>
                                                <a href="mailto:{{ $request->email }}" class="text-decoration-none">
                                                    {{ $request->email }}
                                                </a>
                                            </div>
                                            @if($request->phone)
                                                <div class="small mt-1">
                                                    <i class="bi bi-phone me-1 text-success"></i>
                                                    <a href="tel:{{ $request->phone }}" class="text-decoration-none">
                                                        {{ $request->phone }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Content -->
                                    <td>
                                        <div>
                                            <div class="fw-semibold text-dark mb-1">{{ Str::limit($request->title, 30) }}</div>
                                            <div class="small text-muted">{{ Str::limit($request->message, 50) }}</div>
                                        </div>
                                    </td>

                                    <!-- Status -->
                                    <td class="text-center">
                                        @php
                                            $statusConfig = [
                                                'pending' => ['class' => 'bg-warning text-dark', 'icon' => 'bi-hourglass-half', 'text' => 'Chờ xử lý'],
                                                'in_progress' => ['class' => 'bg-info text-white', 'icon' => 'bi-arrow-clockwise', 'text' => 'Đang xử lý'],
                                                'resolved' => ['class' => 'bg-success text-white', 'icon' => 'bi-check-circle', 'text' => 'Đã giải quyết'],
                                                'rejected' => ['class' => 'bg-danger text-white', 'icon' => 'bi-x-circle', 'text' => 'Từ chối']
                                            ];
                                            $config = $statusConfig[$request->status] ?? $statusConfig['pending'];
                                        @endphp
                                        <span class="badge {{ $config['class'] }} rounded-pill px-3 py-2">
                                            <i class="{{ $config['icon'] }} me-1"></i>
                                            {{ $config['text'] }}
                                        </span>
                                    </td>

                                    <!-- Created Date -->
                                    <td>
                                        <div class="small">
                                            <div class="text-dark">{{ $request->created_at->format('d/m/Y') }}</div>
                                            <div class="text-muted">{{ $request->created_at->format('H:i') }}</div>
                                        </div>
                                    </td>

                                    <!-- Actions -->
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <div class="dropdown">
                                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-gear me-1"></i>Xử lý
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="#"
                                                            onclick="updateStatus({{ $request->id }}, 'pending')">
                                                            <i class="bi bi-hourglass-half me-2 text-warning"></i>Chờ xử lý
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#"
                                                            onclick="updateStatus({{ $request->id }}, 'in_progress')">
                                                            <i class="bi bi-arrow-clockwise me-2 text-info"></i>Đang xử lý
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#"
                                                            onclick="updateStatus({{ $request->id }}, 'resolved')">
                                                            <i class="bi bi-check-circle me-2 text-success"></i>Đã giải quyết
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#"
                                                            onclick="updateStatus({{ $request->id }}, 'rejected')">
                                                            <i class="bi bi-x-circle me-2 text-danger"></i>Từ chối
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
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
                    <h4 class="text-muted">Không tìm thấy yêu cầu hỗ trợ nào</h4>
                    <p class="text-muted mb-4">
                        @if(request()->hasAny(['search', 'status']))
                            Thử thay đổi điều kiện tìm kiếm hoặc
                            <a href="{{ route('admin.support.index') }}" class="text-decoration-none">xóa bộ lọc</a>
                        @else
                            Chưa có yêu cầu hỗ trợ nào từ khách hàng
                        @endif
                    </p>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($supportRequests->hasPages())
            <div class="row mt-4">
                <div class="col-md-6 d-flex align-items-center">
                    <p class="text-muted mb-0">
                        <i class="bi bi-arrow-left-right me-1"></i>
                        Hiển thị {{ $supportRequests->firstItem() }} - {{ $supportRequests->lastItem() }}
                        trong {{ $supportRequests->total() }} kết quả
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end">
                        <nav aria-label="Support Requests Pagination">
                            {{ $supportRequests->links('pagination::bootstrap-4') }}
                        </nav>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Status Update Form -->
    <form id="statusForm" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" id="statusInput">
    </form>

    <!-- CSS tùy chỉnh -->
    <style>
        .btn {
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }

        .badge {
            transition: all 0.2s ease;
        }

        .badge:hover {
            transform: scale(1.05);
        }

        .table th a {
            color: inherit;
        }

        .table th a:hover {
            color: #0d6efd;
        }

        /* Loading animation */
        .loading {
            position: relative;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid #ccc;
            border-top-color: #0d6efd;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .container-custom {
                padding: 0 10px;
            }

            .btn-group .btn {
                padding: 0.25rem 0.5rem;
            }

            /* Mobile pagination */
            .pagination {
                justify-content: center !important;
            }

            /* Stack pagination info on mobile */
            .row .col-md-6 {
                margin-bottom: 1rem;
            }
        }

        /* Status specific styles */
        .badge.bg-warning {
            color: #000 !important;
        }

        /* Dropdown improvements */
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 0.5rem;
        }

        .dropdown-item {
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }

        /* Pagination styling */
        .pagination {
            border-radius: 0.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .page-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
            margin: 0 2px;
            border-radius: 0.375rem !important;
            transition: all 0.2s ease;
        }

        .page-link:hover {
            background-color: #e9ecef;
            color: #0d6efd;
            transform: translateY(-1px);
        }

        .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
            box-shadow: 0 2px 5px rgba(13, 110, 253, 0.3);
        }

        .page-item.disabled .page-link {
            color: #adb5bd;
            background-color: transparent;
        }
    </style>

    <!-- JavaScript -->
    <script>
        function updateStatus(requestId, status) {
            if (confirm('Bạn có chắc chắn muốn cập nhật trạng thái yêu cầu hỗ trợ này?')) {
                const form = document.getElementById('statusForm');
                const statusInput = document.getElementById('statusInput');
                form.action = `/admin/support/${requestId}/status`;
                statusInput.value = status;
                form.submit();
            }
        }

        // Auto-submit form when filters change
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form[method="GET"]');
            const autoSubmitElements = form.querySelectorAll('select[name="status"], select[name="sort_by"], select[name="sort_order"]');

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
                if (element.value && element.value !== 'all' && element.value !== 'priority' && element.value !== 'asc') {
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