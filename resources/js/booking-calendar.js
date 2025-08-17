// Simple Calendar class for booking form
class Calendar {
    constructor(containerId, options = {}) {
        this.container = document.getElementById(containerId);
        this.availableDates = options.availableDates || [];
        this.onDateSelect = options.onDateSelect || (() => {});
        this.currentDate = new Date();
        this.selectedDate = null;

        this.monthNames = [
            'Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
            'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
        ];

        this.dayNames = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];

        this.init();
    }

    init() {
        if (!this.container) return;

        this.container.innerHTML = `
            <div class="calendar-booking">
                <div class="calendar-header d-flex justify-content-between align-items-center mb-3">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="prev-month-booking">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <h6 class="mb-0 fw-bold" id="calendar-title-booking"></h6>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="next-month-booking">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
                <div class="calendar-grid">
                    <div class="calendar-days-header d-grid mb-2" style="grid-template-columns: repeat(7, 1fr); gap: 2px;">
                        ${this.dayNames.map(day => `<div class="text-center fw-bold text-muted small">${day}</div>`).join('')}
                    </div>
                    <div id="calendar-dates-booking" class="calendar-dates d-grid" style="grid-template-columns: repeat(7, 1fr); gap: 2px;"></div>
                </div>
                <div class="calendar-legend mt-3">
                    <small class="text-muted">
                        <span class="badge bg-primary me-2">■</span> Ngày có tour
                        <span class="badge bg-success me-2">■</span> Ngày đã chọn
                        <span class="badge bg-light text-dark me-2">■</span> Ngày không khả dụ
                    </small>
                </div>
            </div>
        `;

        this.bindEvents();
        this.render();
    }

    bindEvents() {
        document.getElementById('prev-month-booking').addEventListener('click', () => {
            this.currentDate.setMonth(this.currentDate.getMonth() - 1);
            this.render();
        });

        document.getElementById('next-month-booking').addEventListener('click', () => {
            this.currentDate.setMonth(this.currentDate.getMonth() + 1);
            this.render();
        });
    }

    render() {
        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();

        // Update title
        document.getElementById('calendar-title-booking').textContent = 
            `${this.monthNames[month]} ${year}`;

        // Get first day of month and number of days
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const startingDayOfWeek = firstDay.getDay(); // 0 = Sunday
        const numDays = lastDay.getDate();

        const calendarDates = document.getElementById('calendar-dates-booking');
        calendarDates.innerHTML = '';

        // Add empty cells for days before the first day of the month
        for (let i = 0; i < startingDayOfWeek; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'calendar-day-empty p-2';
            calendarDates.appendChild(emptyDay);
        }

        // Add days of the month
        for (let day = 1; day <= numDays; day++) {
            const dayElement = document.createElement('div');
            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const isAvailable = this.isDateAvailable(dateStr);
            const isSelected = this.selectedDate === dateStr;
            const isPast = new Date(dateStr) < new Date().setHours(0, 0, 0, 0);

            dayElement.className = 'calendar-day p-2 text-center border rounded';
            dayElement.textContent = day;

            if (isPast) {
                dayElement.classList.add('text-muted', 'bg-light');
                dayElement.style.cursor = 'not-allowed';
            } else if (isAvailable) {
                if (isSelected) {
                    dayElement.classList.add('bg-success', 'text-white', 'fw-bold');
                } else {
                    dayElement.classList.add('bg-primary', 'text-white');
                    dayElement.style.cursor = 'pointer';
                }

                dayElement.addEventListener('click', () => {
                    this.selectDate(dateStr);
                });
            } else {
                dayElement.classList.add('text-muted', 'bg-light');
                dayElement.style.cursor = 'not-allowed';
            }

            calendarDates.appendChild(dayElement);
        }
    }

    isDateAvailable(dateStr) {
        return this.availableDates.some(availableDate => {
            // Handle both date strings and datetime strings
            const availableDateStr = availableDate.split('T')[0];
            return availableDateStr === dateStr;
        });
    }

    selectDate(dateStr) {
        this.selectedDate = dateStr;
        this.render();
        this.onDateSelect(dateStr);
    }
}

// Export for use in other scripts
window.Calendar = Calendar;
