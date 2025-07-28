@php
    use Carbon\Carbon;
    use App\Models\CancellationRequest;
@endphp
@extends('layouts.auth')

@section('content')
    <div class="container my-5 container-custom">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Quản lý booking các tour của bạn tháng {{ request('month', now()->format('Y-m')) }}</h2>
            <div>
                <a href="{{ route('ceo.bookings.groups') }}" class="btn btn-info me-2">
                    <i class="fas fa-layer-group me-1"></i> Xem nhóm bookings
                </a>
                @php
                    $pendingCancellationCount = CancellationRequest::where('status', 'pending')->count();
                @endphp
                <a href="{{ route('ceo.cancellation_requests.index') }}" class="btn btn-warning position-relative">
                    <i class="fas fa-ban me-1"></i> Quản lý yêu cầu hủy
                    @if($pendingCancellationCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $pendingCancellationCount }}
                        </span>
                    @endif
                </a>
            </div>
        </div>

        <!-- Bộ lọc tìm kiếm và chọn tháng -->
        <form class="row g-2 mb-3 align-items-end" method="GET">
            <div class="col-md-3">
                <label class="form-label mb-1">Chọn tháng</label>
                <input type="month" name="month" class="form-control" value="{{ request('month', now()->format('Y-m')) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1">Tìm tour</label>
                <input type="text" name="tour" class="form-control" placeholder="Nhập tên tour..."
                    value="{{ request('tour') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1">Người đặt</label>
                <input type="text" name="user" class="form-control" placeholder="Tên người đặt..."
                    value="{{ request('user') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Lọc</button>
            </div>
        </form>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

<!-- Offcanvas danh sách vé cho từng booking (đặt ngoài bảng, sau endif) -->
@foreach($bookings as $booking)
    @php
        $uniqueNames = $booking->tickets->pluck('full_name')->unique();
        $showNameCol = $uniqueNames->count() > 1;
    @endphp
    <div class="offcanvas offcanvas-end offcanvas-wide" tabindex="-1" id="ticketsOffcanvas{{ $booking->id }}"
        aria-labelledby="ticketsOffcanvasLabel{{ $booking->id }}" style="width: 700px !important;">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="ticketsOffcanvasLabel{{ $booking->id }}">Danh sách vé của booking #{{ $booking->id }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Đóng"></button>
        </div>
        <div class="offcanvas-body">
            <table class="table table-sm table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Mã vé</th>
                        @if($showNameCol)
                            <th>Họ tên</th>
                        @endif
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($booking->tickets as $i => $ticket)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><code>{{ $ticket->ticket_code }}</code></td>
                            @if($showNameCol)
                                <td>{{ $ticket->full_name }}</td>
                            @endif
                            <td>
                                @if($ticket->status === 'valid')
                                    <span class="badge bg-success">Valid</span>
                                @elseif($ticket->status === 'used')
                                    <span class="badge bg-warning text-dark">Used</span>
                                @else
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </td>
                            <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('ceo.tickets.show', $ticket) }}" class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('ceo.tickets.edit', $ticket) }}" class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" title="Update Status">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <form action="{{ route('ceo.tickets.updateStatus', $ticket) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="valid">
                                                <button type="submit" class="dropdown-item"><i class="fas fa-check text-success me-1"></i>Mark as Valid</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('ceo.tickets.updateStatus', $ticket) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="used">
                                                <button type="submit" class="dropdown-item"><i class="fas fa-user-check text-warning me-1"></i>Mark as Used</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('ceo.tickets.updateStatus', $ticket) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="dropdown-item"><i class="fas fa-times text-danger me-1"></i>Mark as Cancelled</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                                <form action="{{ route('ceo.tickets.destroy', $ticket) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this ticket?')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endforeach

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($bookings->isEmpty())
            <div class="alert alert-info text-center">Chưa có booking nào cho các tour của bạn.</div>
        @else
            <!-- Hiển thị theo nhóm -->
            @foreach($groupedBookings as $groupKey => $groupBookings)
                @php
                    $firstBooking = $groupBookings->first();
                    $tour = $firstBooking->tour;
                    $bookingDate = $firstBooking->booking_date;
                    // Đảm bảo logic lọc bookings theo booking_date, không phải created_at
                    $filterMonth = request('month', now()->format('Y-m'));
                    $filteredBookings = $groupBookings->filter(function ($booking) use ($filterMonth) {
                        return Carbon::parse($booking->booking_date)->format('Y-m') === $filterMonth;
                    });
                    $pendingBookings = $filteredBookings->where('status', 'pending');
                    $groupedBookings = $filteredBookings->where('status', 'grouped');
                    $confirmedBookings = $filteredBookings->where('status', 'confirmed');
                    $cancelledBookings = $filteredBookings->where('status', 'cancelled');
                @endphp

                <div class="mb-4 p-3 bg-secondary">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <strong>{{ $tour->title }}</strong> -
                            Ngày: {{ Carbon::parse($bookingDate)->format('d/m/Y') }}
                            <span class="badge bg-primary ms-2">{{ $filteredBookings->count() }} bookings</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Thống kê nhanh -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h6 class="text-warning">{{ $pendingBookings->count() }}</h6>
                                    <small>Chờ xử lý</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h6 class="text-info">{{ $groupedBookings->count() }}</h6>
                                    <small>Đã nhóm</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h6 class="text-success">{{ $confirmedBookings->count() }}</h6>
                                    <small>Đã xác nhận</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h6 class="text-danger">{{ $cancelledBookings->count() }}</h6>
                                    <small>Đã hủy</small>
                                </div>
                            </div>
                        </div>

                        <!-- Actions cho nhóm -->
                        @if($pendingBookings->count() > 0)
                            <div class="mb-3">
                                <div class="alert alert-info">
                                    <h6 class="mb-2">
                                        <i class="fas fa-info-circle me-1"></i> Gom nhóm bookings:
                                    </h6>
                                    <ul class="mb-0 small">
                                        <li>Chọn các bookings bạn muốn gom thành 1 nhóm</li>
                                        <li>Có thể tạo nhiều nhóm khác nhau cho cùng 1 tour và ngày</li>
                                        <li>Chỉ có thể gom các bookings có trạng thái "Chờ xử lý"</li>
                                    </ul>
                                </div>

                                <form action="{{ route('ceo.bookings.createGroup') }}" method="POST" id="groupForm">
                                    @csrf
                                    <input type="hidden" name="tour_id" value="{{ $tour->id }}">
                                    <input type="hidden" name="booking_date" value="{{ $bookingDate }}">

                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="selectAll()">
                                                <i class="fas fa-check-square me-1"></i> Chọn tất cả
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="deselectAll()">
                                                <i class="fas fa-square me-1"></i> Bỏ chọn tất cả
                                            </button>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <span class="text-muted">Đã chọn: <span id="selectedCount">0</span> /
                                                {{ $pendingBookings->count() }}</span>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        @foreach($pendingBookings as $booking)
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check border rounded p-2">
                                                    <input class="form-check-input booking-checkbox" type="checkbox" name="booking_ids[]"
                                                        value="{{ $booking->id }}" id="booking_{{ $booking->id }}"
                                                        onchange="updateSelectedCount()">
                                                    <label class="form-check-label" for="booking_{{ $booking->id }}">
                                                        <strong>{{ $booking->full_name }}</strong><br>
                                                        <small class="text-muted">
                                                            {{ $booking->contact_email }} | {{ $booking->number_of_people }} người
                                                            @if($booking->note)
                                                                <br><em>"{{ Str::limit($booking->note, 50) }}"</em>
                                                            @endif
                                                        </small>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <button type="submit" class="btn btn-info" id="groupBtn" disabled
                                        onclick="return confirm('Gom các bookings đã chọn thành 1 nhóm?')">
                                        <i class="fas fa-layer-group me-1"></i> Gom nhóm (<span id="selectedCountBtn">0</span> bookings)
                                    </button>
                                </form>
                            </div>
                        @endif


                        <!-- Hiển thị tổng tiền cho tất cả bookings (pending + grouped) của tour/ngày này -->
                        @php
                            // Tổng tiền cần thanh toán: chỉ tính các booking đang ở trạng thái 'pending'
                            $totalPendingAmount = $pendingBookings->sum('total_amount');
                        @endphp
                        <div class="mb-3">
                            <h6 class="text-primary">
                                <i class="fas fa-coins me-1"></i> Tổng tiền cần thanh toán (chỉ các booking đang chờ xử lý):
                                <strong>{{ number_format($totalPendingAmount, 0, ',', '.') }} đ</strong>
                            </h6>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Người đặt</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Số người</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Vé đã sinh</th>
                                        <th>Thao tác</th>
                                        <th>Vé</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($filteredBookings as $i => $booking)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $booking->full_name }}</td>
                                            <td>{{ $booking->contact_email }}</td>
                                            <td>{{ $booking->contact_phone }}</td>
                                            <td>{{ $booking->number_of_people }}</td>
                                            <td>{{ number_format($booking->total_amount, 0, ',', '.') }} đ</td>
                                            <td>
                                                @if($booking->status === 'pending')
                                                    <span class="badge bg-warning">Chờ xử lý</span>
                                                @elseif($booking->status === 'grouped')
                                                    <span class="badge bg-info">Đã nhóm</span>
                                                @elseif($booking->status === 'confirmed')
                                                    <span class="badge bg-success">Đã xác nhận</span>
                                                @elseif($booking->status === 'cancelled')
                                                    <span class="badge bg-danger">Đã hủy</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $booking->tickets->whereIn('status', ['valid', 'used'])->count() }} /
                                                {{ $booking->number_of_people }}
                                            </td>
                                            <td>
                                                @if($booking->status === 'pending')
                                                    <form action="{{ route('ceo.bookings.confirm', $booking->id) }}" method="POST"
                                                        style="display:inline-block">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-success btn-sm"
                                                            onclick="return confirm('Xác nhận và sinh vé cho booking này?')">
                                                            <i class="fas fa-check"></i> Xác nhận & sinh vé
                                                        </button>
                                                    </form>
                                                @elseif($booking->status === 'grouped')
                                                    <span class="text-muted">Đã gom nhóm</span>
                                                @elseif($booking->status === 'confirmed')
                                                    <span class="text-success">Đã xác nhận</span>
                                                @elseif($booking->status === 'cancelled')
                                                    <span class="text-danger">Đã hủy</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-info btn-sm" data-bs-toggle="offcanvas"
                                                    data-bs-target="#ticketsOffcanvas{{ $booking->id }}">
                                                    <i class="fas fa-list"></i> Xem vé
                                        <tr>
                                            ...existing code...
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Ghi chú -->
                        @if($filteredBookings->whereNotNull('note')->count() > 0)
                            <div class="mt-3">
                                <h6>Ghi chú:</h6>
                                @foreach($filteredBookings->whereNotNull('note') as $booking)
                                    <div class="small text-muted">
                                        <strong>{{ $booking->full_name }}:</strong> {{ $booking->note }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif

        <!-- Phân trang cho danh sách bookings đã xác nhận -->
        @if(isset($bookings) && method_exists($bookings, 'withQueryString'))
            <div class="d-flex justify-content-center mt-3">
                {{ $bookings->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>

    <script>
        function selectAll() {
            document.querySelectorAll('.booking-checkbox').forEach(checkbox => {
                checkbox.checked = true;
            });
            updateSelectedCount();
        }

        function deselectAll() {
            document.querySelectorAll('.booking-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            updateSelectedCount();
        }

        function updateSelectedCount() {
            const checkboxes = document.querySelectorAll('.booking-checkbox:checked');
            const count = checkboxes.length;

            document.getElementById('selectedCount').textContent = count;
            document.getElementById('selectedCountBtn').textContent = count;

            const groupBtn = document.getElementById('groupBtn');
            if (count > 0) {
                groupBtn.disabled = false;
                groupBtn.classList.remove('btn-secondary');
                groupBtn.classList.add('btn-info');
            } else {
                groupBtn.disabled = true;
                groupBtn.classList.remove('btn-info');
                groupBtn.classList.add('btn-secondary');
            }
        }

        // Khởi tạo khi trang load
        document.addEventListener('DOMContentLoaded', function () {
            updateSelectedCount();
        });
    </script>
@endsection