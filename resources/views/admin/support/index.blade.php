@extends('layouts.auth')

@section('title', 'Manage Support Requests')

@section('content')
    <div class="container container-custom py-4">
        <div class=" row">
            <div class="col-12">
                <div class="">

                    <div class="">
                        <h1 class="text-2xl font-bold text-center">Manage Support Requests</h1>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped text-center align-middle">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle">Name</th>
                                        <th class="text-center align-middle">Email</th>
                                        <th class="text-center align-middle">Phone</th>
                                        <th class="text-center align-middle">Title</th>
                                        <th class="text-center align-middle">Message</th>
                                        <th class="text-center align-middle">Status</th>
                                        <th class="text-center align-middle">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($supportRequests as $request)
                                                                <tr>
                                                                    <td class="text-center align-middle">
                                                                        {{ $request->name ?? $request->user->name ?? 'Anonymous' }}
                                                                    </td>
                                                                    <td class="text-center align-middle">{{ $request->email }}</td>
                                                                    <td class="text-center align-middle">{{ $request->phone }}</td>
                                                                    <td class="text-center align-middle">{{ $request->title }}</td>
                                                                    <td class="text-center align-middle">{{ $request->message }}</td>
                                                                    <td class="text-center align-middle">
                                                                        <span
                                                                            class="badge bg-{{ 
                                                                                                                                                                                                                $request->status === 'pending' ? 'warning' :
                                        ($request->status === 'in_progress' ? 'info' :
                                            ($request->status === 'resolved' ? 'success' : 'danger')) 
                                                                                                                                                                                                            }}">
                                                                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="text-center align-middle">
                                                                        <div class="status-dropdown-container" style="position: relative;">
                                                                            <button type="button" class="btn btn-sm btn-outline-primary status-trigger"
                                                                                data-request-id="{{ $request->id }}">
                                                                                <i class="fa fa-exchange-alt"></i> Change Status
                                                                            </button>
                                                                            <div class="status-dropdown-menu" style="display: none;">
                                                                                <a class="dropdown-item status-pending" href="#" data-status="pending">
                                                                                    <i class="fa fa-hourglass-half"></i> Mark as Pending
                                                                                </a>
                                                                                <a class="dropdown-item status-in-progress" href="#"
                                                                                    data-status="in_progress">
                                                                                    <i class="fa fa-spinner"></i> Mark as In Progress
                                                                                </a>
                                                                                <a class="dropdown-item status-resolved" href="#"
                                                                                    data-status="resolved">
                                                                                    <i class="fa fa-check-circle"></i> Mark as Resolved
                                                                                </a>
                                                                                <a class="dropdown-item status-rejected" href="#"
                                                                                    data-status="rejected">
                                                                                    <i class="fa fa-times-circle"></i> Mark as Rejected
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No support requests found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $supportRequests->links() }}
                        </div>
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
        function updateStatus(requestId, status) {
            if (confirm('Are you sure you want to update the support request status?')) {
                const form = document.getElementById('statusForm');
                const statusInput = document.getElementById('statusInput');
                form.action = `/admin/support/${requestId}/status`;
                statusInput.value = status;
                form.submit();
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            let currentDropdown = null;

            document.querySelectorAll('.status-trigger').forEach(trigger => {
                trigger.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Đóng dropdown cũ nếu có
                    if (currentDropdown) {
                        document.body.removeChild(currentDropdown);
                        currentDropdown = null;
                    }

                    // Lấy dropdown gốc và clone
                    const container = trigger.closest('.status-dropdown-container');
                    const dropdown = container.querySelector('.status-dropdown-menu');
                    const clone = dropdown.cloneNode(true);
                    clone.style.display = 'block';
                    clone.style.position = 'absolute';
                    clone.style.zIndex = 99999;
                    clone.classList.add('cloned-status-dropdown');
                    clone.style.minWidth = '180px';

                    // Tính toán vị trí (căn giữa phía trên nút)
                    const rect = trigger.getBoundingClientRect();
                    clone.style.left = (rect.left + rect.width / 2 - 90 + window.scrollX) + 'px'; // 90 = min-width/2
                    clone.style.top = (rect.top - clone.offsetHeight - 10 + window.scrollY) + 'px';

                    // Nếu dropdown bị tràn lên trên, thì hiển thị xuống dưới
                    setTimeout(function () {
                        const cloneRect = clone.getBoundingClientRect();
                        if (cloneRect.top < 0) {
                            clone.style.top = (rect.bottom + 10 + window.scrollY) + 'px';
                        }
                    }, 0);

                    // Gắn lại sự kiện cho các item
                    clone.querySelectorAll('.dropdown-item').forEach(item => {
                        item.onclick = function (ev) {
                            ev.preventDefault();
                            updateStatus(trigger.dataset.requestId, item.getAttribute('data-status'));
                        }
                    });

                    document.body.appendChild(clone);
                    currentDropdown = clone;
                });
            });

            // Đóng dropdown khi click ngoài
            document.addEventListener('click', function (e) {
                if (currentDropdown && (!e.target.closest('.cloned-status-dropdown'))) {
                    document.body.removeChild(currentDropdown);
                    currentDropdown = null;
                }
            });
        });
    </script>

    <style>
        .status-dropdown-menu .dropdown-item.status-pending {
            color: #856404;
            background: #fff3cd;
        }

        .status-dropdown-menu .dropdown-item.status-in-progress {
            color: #0c5460;
            background: #d1ecf1;
        }

        .status-dropdown-menu .dropdown-item.status-resolved {
            color: #155724;
            background: #d4edda;
        }

        .status-dropdown-menu .dropdown-item.status-rejected {
            color: #721c24;
            background: #f8d7da;
        }

        .status-dropdown-menu .dropdown-item i {
            margin-right: 8px;
        }

        /* Hover màu nền rõ ràng, không trong suốt */
        .status-dropdown-menu .dropdown-item.status-pending:hover {
            background: #ffe082 !important;
            color: #856404;
        }

        .status-dropdown-menu .dropdown-item.status-in-progress:hover {
            background: #81d4fa !important;
            color: #0c5460;
        }

        .status-dropdown-menu .dropdown-item.status-resolved:hover {
            background: #a5d6a7 !important;
            color: #155724;
        }

        .status-dropdown-menu .dropdown-item.status-rejected:hover {
            background: #ef9a9a !important;
            color: #721c24;
        }
    </style>
@endsection