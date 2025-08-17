{{-- Ticket Display Partial --}}
<div class="ticket-list">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">
            <i class="bi bi-ticket-perforated me-2 text-primary"></i>
            Danh sách vé ({{ count($tickets ?? []) }})
        </h5>
    </div>
    
    <div id="ticketContainer">
        <!-- Tickets will be loaded here -->
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Đang tải...</span>
            </div>
            <p class="text-muted mt-2">Đang tải thông tin vé...</p>
        </div>
    </div>
</div>

<style>
.ticket-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.ticket-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.ticket-card::before {
    content: '';
    position: absolute;
    top: 50%;
    left: -10px;
    width: 20px;
    height: 20px;
    background: #f8f9fa;
    border-radius: 50%;
    transform: translateY(-50%);
}

.ticket-card::after {
    content: '';
    position: absolute;
    top: 50%;
    right: -10px;
    width: 20px;
    height: 20px;
    background: #f8f9fa;
    border-radius: 50%;
    transform: translateY(-50%);
}

.ticket-divider {
    position: relative;
    height: 2px;
    background: rgba(255,255,255,0.3);
    margin: 15px 0;
    overflow: hidden;
}

.ticket-divider::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 100%;
    background: repeating-linear-gradient(
        to right,
        transparent,
        transparent 5px,
        rgba(255,255,255,0.6) 5px,
        rgba(255,255,255,0.6) 10px
    );
}

.ticket-code {
    font-family: 'Courier New', monospace;
    font-size: 1.1em;
    font-weight: bold;
    letter-spacing: 1px;
    padding: 8px 12px;
    background: rgba(255,255,255,0.2);
    border-radius: 8px;
    display: inline-block;
}

.status-badge-ticket {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.85em;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-success {
    background: rgba(25, 135, 84, 0.2);
    color: #198754;
    border: 1px solid rgba(25, 135, 84, 0.3);
}

.status-warning {
    background: rgba(255, 193, 7, 0.2);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.3);
}

.status-danger {
    background: rgba(220, 53, 69, 0.2);
    color: #dc3545;
    border: 1px solid rgba(220, 53, 69, 0.3);
}

.no-tickets {
    text-align: center;
    padding: 40px 20px;
}

.no-tickets-icon {
    font-size: 3rem;
    color: #6c757d;
    margin-bottom: 15px;
}
</style>
