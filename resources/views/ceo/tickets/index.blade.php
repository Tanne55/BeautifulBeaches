@extends('layouts.auth')

@section('title', 'Manage Tickets')

@section('content')
<div class="container-fluid" style="padding-left: 100px;">
    <div class="row">
        <div class="col-12">
            <h3 class="mb-3"><i class="fas fa-ticket-alt me-2 text-primary"></i>Manage Tickets</h3>
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#Booking</th>
                            <th>Tên người đặt</th>
                            <th>Email</th>
                            <th>Tour</th>
                            <th>Số lượng vé</th>
                            <th>Vé hợp lệ</th>
                            <th>Trạng thái booking</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>#{{ $booking->id }}</td>
                                <td>{{ $booking->full_name }}</td>
                                <td>{{ $booking->contact_email }}</td>
                                <td>{{ $booking->tour->title ?? 'N/A' }}</td>
                                <td>{{ $booking->number_of_people }}</td>
                                <td>{{ $booking->tickets->where('status', 'valid')->count() }}</td>
                                <td>
                                    @if($booking->status === 'pending')
                                        <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                                    @elseif($booking->status === 'confirmed')
                                        <span class="badge bg-success">Đã xác nhận</span>
                                    @elseif($booking->status === 'cancelled')
                                        <span class="badge bg-danger">Đã hủy</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-info btn-sm" data-bs-toggle="offcanvas" data-bs-target="#ticketsOffcanvas{{ $booking->id }}">
                                        <i class="fas fa-list"></i> Xem chi tiết vé
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No bookings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Offcanvas danh sách vé cho từng booking -->
@foreach($bookings as $booking)
@php
    $uniqueNames = $booking->tickets->pluck('full_name')->unique();
    $showNameCol = $uniqueNames->count() > 1;
@endphp
<div class="offcanvas offcanvas-end offcanvas-wide" tabindex="-1" id="ticketsOffcanvas{{ $booking->id }}" aria-labelledby="ticketsOffcanvasLabel{{ $booking->id }}" style="width: 700px !important;">
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
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Lắng nghe tất cả nút mở modal chi tiết/chỉnh sửa
    document.querySelectorAll('[data-bs-target^="#ticketDetailOffcanvas"], [data-bs-target^="#ticketEditOffcanvas"]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            // Tìm modal cha (ticketsModal)
            var parentModalEl = btn.closest('.modal');
            if (!parentModalEl) return;
            var parentModalId = parentModalEl.id;
            var modalId = btn.getAttribute('data-bs-target');
            // Đóng modal cha
            var parentModal = bootstrap.Modal.getInstance(parentModalEl);
            if (parentModal) parentModal.hide();
            // Mở modal con
            var childModalEl = document.querySelector(modalId);
            var childModal = new bootstrap.Modal(childModalEl);
            childModal.show();
            // Khi đóng modal con, mở lại modal cha
            var handler = function() {
                var parentModal = new bootstrap.Modal(parentModalEl);
                parentModal.show();
                childModalEl.removeEventListener('hidden.bs.modal', handler);
            };
            childModalEl.addEventListener('hidden.bs.modal', handler);
        });
    });

    // Tự động mở offcanvas nếu có open_booking trên URL
    const urlParams = new URLSearchParams(window.location.search);
    const openBooking = urlParams.get('open_booking');
    if (openBooking) {
        const offcanvasEl = document.getElementById('ticketsOffcanvas' + openBooking);
        if (offcanvasEl) {
            const bsOffcanvas = new bootstrap.Offcanvas(offcanvasEl);
            bsOffcanvas.show();
        }
    }
});
</script>
@endsection

@section('styles')
<style>
  .offcanvas-wide {
    width: 700px !important;
    max-width: 90vw;
  }
  @media (max-width: 768px) {
    .offcanvas-wide {
      width: 100vw !important;
    }
  }
</style>
@endsection 