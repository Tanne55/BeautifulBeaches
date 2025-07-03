@extends('layouts.auth')

@section('title', 'Ticket Details')

@section('content')
    <div class="container container-custom py-4">
        <div class="row">
            <div class="col-12">
                <h3 class="mb-3"><i class="fas fa-ticket-alt me-2 text-primary"></i>Ticket Details</h3>
                <a href="{{ route('ceo.tickets.index', ['open_booking' => $ticket->tour_booking_id]) }}"
                    class="btn btn-outline-secondary btn-sm mb-3">
                    <i class="fas fa-arrow-left me-1"></i>Back
                </a>
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <h5 class="mb-3 text-primary"><i class="fas fa-info-circle me-1"></i>Ticket Info</h5>
                            <ul class="list-unstyled mb-0">
                                <li><strong>ID:</strong> {{ $ticket->id }}</li>
                                <li><strong>Code:</strong> <span
                                        class="badge bg-secondary fs-6">{{ $ticket->ticket_code }}</span></li>
                                <li><strong>Status:</strong>
                                    @if($ticket->status === 'valid')
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Valid</span>
                                    @elseif($ticket->status === 'used')
                                        <span class="badge bg-warning text-dark"><i
                                                class="fas fa-user-check me-1"></i>Used</span>
                                    @else
                                        <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Cancelled</span>
                                    @endif
                                </li>
                                <li><strong>Created:</strong> {{ $ticket->created_at->format('Y-m-d H:i') }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <h5 class="mb-3 text-primary"><i class="fas fa-user me-1"></i>Customer</h5>
                            <ul class="list-unstyled mb-0">
                                <li><strong>Name:</strong> {{ $ticket->full_name }}</li>
                                <li><strong>Email:</strong> <a href="mailto:{{ $ticket->email }}">{{ $ticket->email }}</a>
                                </li>
                                <li><strong>Phone:</strong> {{ $ticket->phone ?? 'N/A' }}</li>
                            </ul>
                        </div>
                    </div>
                </div>

                @if($ticket->tourBooking && $ticket->tourBooking->tour)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="border rounded p-3">
                                <h5 class="mb-3 text-primary"><i class="fas fa-umbrella-beach me-1"></i>Tour Info</h5>
                                <ul class="list-unstyled mb-0">
                                    <li><strong>Title:</strong> <span
                                            class="text-primary">{{ $ticket->tourBooking->tour->title }}</span></li>
                                    <li><strong>Price:</strong> ₫{{ number_format($ticket->tourBooking->tour->price ?? 0) }}
                                    </li>
                                    <li><strong>Booking Date:</strong> {{ $ticket->tourBooking->booking_date ?? 'N/A' }}</li>
                                    <li><strong>People:</strong> {{ $ticket->tourBooking->number_of_people ?? 'N/A' }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row mt-4">
                    <div class="col-12 d-flex flex-wrap gap-2">
                        <a href="{{ route('ceo.tickets.edit', $ticket) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i>Edit Ticket
                        </a>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-sync-alt me-1"></i>Update Status
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="updateStatus('valid')"><i
                                            class="fas fa-check text-success me-1"></i>Mark as Valid</a></li>
                                <li><a class="dropdown-item" href="#" onclick="updateStatus('used')"><i
                                            class="fas fa-user-check text-warning me-1"></i>Mark as Used</a></li>
                                <li><a class="dropdown-item" href="#" onclick="updateStatus('cancelled')"><i
                                            class="fas fa-times text-danger me-1"></i>Mark as Cancelled</a></li>
                            </ul>
                        </div>
                        <form action="{{ route('ceo.tickets.destroy', $ticket) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to delete this ticket?')">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Form -->
    <form id="statusForm" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" id="statusInput">
    </form>

    <script>
        function updateStatus(status) {
            if (confirm('Are you sure you want to update the ticket status?')) {
                const form = document.getElementById('statusForm');
                const statusInput = document.getElementById('statusInput');
                form.action = `{{ route('ceo.tickets.updateStatus', $ticket) }}`;
                statusInput.value = status;
                form.submit();
            }
        }
    </script>
@endsection