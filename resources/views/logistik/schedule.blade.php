@extends('layouts.app')

@section('title', 'Re-Glow - Schedule')

@section('styles')
<style>
    :root {
        --pink-base: #F9B6C7;
        --green-dark: #20413A;
        --green-light: #BAC2AB;
        --text-gray: #666666;
        --text-dark: #2D2D2D;
        --border-light: #e8e8e8;
    }

    .schedule-container {
        padding: 3rem 5%;
        background: #f8f9fa;
        min-height: calc(100vh - 120px);
    }

    .schedule-header {
        max-width: 1200px;
        margin: 0 auto 3rem;
    }

    .schedule-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--green-dark);
        margin-bottom: 0.5rem;
    }

    .schedule-header p {
        font-size: 1.1rem;
        color: var(--text-gray);
    }

    .schedule-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }

    .view-toggle {
        display: flex;
        gap: 0.5rem;
        background: white;
        padding: 0.5rem;
        border-radius: 8px;
        border: 1px solid var(--border-light);
    }

    .view-btn {
        padding: 0.5rem 1rem;
        border: none;
        background: white;
        color: var(--text-gray);
        font-weight: 600;
        cursor: pointer;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .view-btn.active {
        background: var(--pink-base);
        color: white;
    }

    .filter-group {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .filter-select {
        padding: 0.75rem 1rem;
        border: 1px solid var(--border-light);
        border-radius: 8px;
        background: white;
        color: var(--text-dark);
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--pink-base);
    }

    .filter-select:hover {
        border-color: var(--pink-base);
    }

    /* Calendar View */
    .calendar-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .calendar-nav {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .calendar-nav button {
        background: var(--pink-base);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .calendar-nav button:hover {
        background: #F7A3B8;
    }

    .calendar-month {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--green-dark);
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .calendar-day-header {
        text-align: center;
        font-weight: 700;
        color: var(--green-dark);
        padding: 0.75rem;
        border-bottom: 2px solid var(--border-light);
        margin-bottom: 0.5rem;
    }

    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        min-height: 80px;
    }

    .calendar-day.other-month {
        color: #ccc;
    }

    .calendar-day.has-pickup {
        background: linear-gradient(135deg, #fff5f7 0%, #ffe0e6 100%);
        border: 2px solid var(--pink-base);
    }

    .calendar-day.today {
        background: linear-gradient(135deg, var(--pink-base) 0%, #F7A3B8 100%);
        color: white;
        font-weight: 700;
    }

    .calendar-day:hover:not(.other-month) {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .calendar-day-label {
        font-size: 0.9rem;
        font-weight: 600;
    }

    .pickup-count {
        position: absolute;
        top: 4px;
        right: 4px;
        background: var(--green-dark);
        color: white;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: 700;
    }

    /* List View */
    .schedule-list {
        max-width: 1200px;
        margin: 0 auto;
    }

    .schedule-date-section {
        margin-bottom: 3rem;
    }

    .schedule-date-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border-light);
    }

    .date-indicator {
        background: linear-gradient(135deg, var(--pink-base) 0%, #ffc9d4 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 700;
        min-width: 150px;
        text-align: center;
    }

    .date-info {
        flex: 1;
    }

    .date-info h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--green-dark);
        margin-bottom: 0.25rem;
    }

    .date-info p {
        color: var(--text-gray);
        font-size: 0.95rem;
    }

    .pickup-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border-left: 4px solid var(--pink-base);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .pickup-card:hover {
        transform: translateX(4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .pickup-card-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
    }

    .pickup-time {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
        color: var(--green-dark);
        font-size: 1.1rem;
    }

    .pickup-status {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .pickup-status.pending {
        background: #fff4cc;
        color: #ca8a04;
    }

    .pickup-status.confirmed {
        background: #dbeafe;
        color: #0284c7;
    }

    .pickup-status.completed {
        background: #d4f4dd;
        color: #16a34a;
    }

    .pickup-status.issue {
        background: #fee2e2;
        color: #dc2626;
    }

    .pickup-card-body {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1rem;
    }

    .pickup-info {
        display: flex;
        gap: 1rem;
    }

    .pickup-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--pink-base) 0%, #ffc9d4 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .pickup-details {
        flex: 1;
    }

    .pickup-location-name {
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
    }

    .pickup-location-address {
        font-size: 0.9rem;
        color: var(--text-gray);
        margin-bottom: 0.5rem;
    }

    .pickup-meta {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .pickup-meta-item {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.85rem;
        color: var(--text-gray);
    }

    .pickup-actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        justify-content: flex-end;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border: none;
        background: #f0f0f0;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-gray);
        transition: all 0.3s ease;
        font-size: 1rem;
    }

    .action-btn:hover {
        background: var(--pink-base);
        color: white;
    }

    .pickup-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid var(--border-light);
    }

    .pickup-contact {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: var(--text-gray);
    }

    .contact-name {
        font-weight: 600;
        color: var(--text-dark);
    }

    .btn-update-status {
        background: var(--green-dark);
        color: white;
        border: none;
        padding: 0.5rem 1.25rem;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-update-status:hover {
        background: #15342c;
        transform: translateY(-2px);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--green-dark);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--text-gray);
        margin-bottom: 2rem;
    }

    .btn-empty-action {
        background: var(--pink-base);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-empty-action:hover {
        background: #F7A3B8;
        transform: translateY(-2px);
    }

    /* Stats Bar */
    .schedule-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }

    .stat-box {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--pink-base);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: var(--text-gray);
        font-size: 0.9rem;
        font-weight: 600;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .schedule-container {
            padding: 2rem 5%;
        }

        .schedule-header h1 {
            font-size: 1.75rem;
        }

        .calendar-grid {
            gap: 0.5rem;
        }

        .calendar-day {
            min-height: 60px;
            font-size: 0.85rem;
        }

        .pickup-card-body {
            grid-template-columns: 1fr;
        }

        .pickup-actions {
            justify-content: flex-start;
        }

        .schedule-controls {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-group {
            flex-direction: column;
        }

        .filter-select {
            width: 100%;
        }

        .view-toggle {
            width: 100%;
            justify-content: center;
        }

        .date-indicator {
            min-width: 100px;
            font-size: 0.9rem;
        }

        .schedule-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endsection

@section('content')
<div class="schedule-container">
    <!-- Header -->
    <div class="schedule-header">
        <h1>Schedule</h1>
        <p>Maintain your pick-up schedule here</p>
    </div>

    <!-- Stats Bar -->
    <div class="schedule-stats">
        <div class="stat-box">
            <div class="stat-value" id="stat-today">{{ $todayPickups->count() }}</div>
            <div class="stat-label">Today</div>
        </div>
        <div class="stat-box">
            <div class="stat-value" id="stat-pending">{{ $pendingToday }}</div>
            <div class="stat-label">Pending</div>
        </div>
        <div class="stat-box">
            <div class="stat-value" id="stat-completed">{{ $completedToday }}</div>
            <div class="stat-label">Completed</div>
        </div>
        <div class="stat-box">
            <div class="stat-value" id="stat-total">{{ $totalPickups }}</div>
            <div class="stat-label">Total</div>
        </div>
    </div>

    <!-- Controls -->
    <div class="schedule-controls">
        <div class="view-toggle">
            <button class="view-btn active" data-view="list">📋 List</button>
            <button class="view-btn" data-view="calendar">📅 Calendar</button>
        </div>
        <div class="filter-group">
            <select class="filter-select" id="filter-status">
                <option value="">All</option>
                <option value="Pending">Pending</option>
                <option value="Dikonfirmasi">Confirmed</option>
                <option value="Selesai">Finished</option>
                <option value="Bermasalah">Incomplete</option>
            </select>
            <select class="filter-select" id="filter-date">
                <option value="all">All Day</option>
                <option value="today">Today</option>
                <option value="upcoming">Upcoming</option>
                <option value="past">Past</option>
            </select>
        </div>
    </div>

    <!-- List View -->
    <div id="list-view" class="schedule-list">
        @if($todayPickups->count() > 0)
            <div class="schedule-date-section">
                <div class="schedule-date-header">
                    <div class="date-indicator">{{ now()->format('D, M d') }}</div>
                    <div class="date-info">
                        <h3>Schedule</h3>
                        <p>{{ $todayPickups->count() }} pickup</p>
                    </div>
                </div>

                @foreach($todayPickups as $pickup)
                <div class="pickup-card" data-pickup-id="{{ $pickup->id_jadwal_pengambilan }}" data-status="{{ $pickup->status }}">
                    <div class="pickup-card-header">
                        <div class="pickup-time">
                            🕐 {{ $pickup->waktu_pengambilan->format('H:i') }}
                        </div>
                        <span class="pickup-status {{ strtolower(str_replace('Dikonfirmasi', 'confirmed', str_replace('Bermasalah', 'issue', $pickup->status))) }}">
                            {{ $pickup->status }}
                        </span>
                    </div>

                    <div class="pickup-card-body">
                        <div class="pickup-info">
                            <div class="pickup-icon">📍</div>
                            <div class="pickup-details">
                                <div class="pickup-location-name">{{ $pickup->dropPoint->nama_droppoint ?? 'Lokasi Tidak Diketahui' }}</div>
                                <div class="pickup-location-address">{{ $pickup->lokasi_droppoint ?? 'Alamat tidak tersedia' }}</div>
                                <div class="pickup-meta">
                                    <div class="pickup-meta-item">📦 {{ $pickup->jenis_sampah ?? 'Umum' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="pickup-actions">
                            <button class="action-btn" title="Lihat Detail">👁</button>
                            <button class="action-btn" title="Edit">✏️</button>
                            <button class="action-btn" title="Laporkan Masalah">⚠️</button>
                        </div>
                    </div>

                    <div class="pickup-footer">
                        <div class="pickup-contact">
                            Kontak: <span class="contact-name">{{ $pickup->user->name ?? 'N/A' }}</span>
                        </div>
                        <button class="btn-update-status" data-pickup="{{ $pickup->id_jadwal_pengambilan }}">
                            Update Status
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <h3>No Schedule Today</h3>
                <p>There is no schedule listed today</p>
            </div>
        @endif
    </div>

    <!-- Calendar View -->
    <div id="calendar-view" class="calendar-container" style="display: none;">
        <div class="calendar-header">
            <h2 class="calendar-month" id="current-month">November 2025</h2>
            <div class="calendar-nav">
                <button id="prev-month">← Previous</button>
                <button id="next-month">Next →</button>
            </div>
        </div>
        <div class="calendar-grid" id="calendar-grid">
            <!-- Generated by JavaScript -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentMonth = new Date();
    let allPickups = @json($todayPickups->groupBy(function($item) { 
        return $item->waktu_pengambilan->format('Y-m-d'); 
    }));

    // View Toggle
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const view = this.dataset.view;
            
            document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            if (view === 'list') {
                document.getElementById('list-view').style.display = 'block';
                document.getElementById('calendar-view').style.display = 'none';
            } else {
                document.getElementById('list-view').style.display = 'none';
                document.getElementById('calendar-view').style.display = 'block';
                generateCalendar();
            }
        });
    });

    // Update Status Button
    document.querySelectorAll('.btn-update-status').forEach(btn => {
        btn.addEventListener('click', function() {
            const pickupId = this.dataset.pickup;
            const statusSelect = prompt('Pilih status baru:\n1. Pending\n2. Dikonfirmasi\n3. Selesai\n4. Bermasalah');
            
            if (statusSelect) {
                // Here you can add API call to update status
                console.log('Update pickup', pickupId, 'to status', statusSelect);
                alert('Status berhasil diupdate!');
            }
        });
    });

    // Generate Calendar
    function generateCalendar() {
        const year = currentMonth.getFullYear();
        const month = currentMonth.getMonth();
        
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDayOfWeek = firstDay.getDay();

        document.getElementById('current-month').textContent = 
            currentMonth.toLocaleString('id-ID', { month: 'long', year: 'numeric' });

        let calendarGrid = '';
        
        // Day headers
        const dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        dayNames.forEach(day => {
            calendarGrid += `<div class="calendar-day-header">${day}</div>`;
        });

        // Previous month days
        for (let i = 0; i < startingDayOfWeek; i++) {
            calendarGrid += `<div class="calendar-day other-month"></div>`;
        }

        // Current month days
        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const isToday = new Date().toDateString() === new Date(year, month, day).toDateString();
            const hasPickup = false; // You can check this from your data

            let classes = 'calendar-day';
            if (isToday) classes += ' today';
            if (hasPickup) classes += ' has-pickup';

            calendarGrid += `
                <div class="${classes}" data-date="${dateStr}">
                    <span class="calendar-day-label">${day}</span>
                    ${hasPickup ? '<span class="pickup-count">3</span>' : ''}
                </div>
            `;
        }

        // Next month days
        const remainingCells = 42 - (startingDayOfWeek + daysInMonth);
        for (let i = 0; i < remainingCells; i++) {
            calendarGrid += `<div class="calendar-day other-month"></div>`;
        }

        document.getElementById('calendar-grid').innerHTML = calendarGrid;
    }

    // Calendar Navigation
    document.getElementById('prev-month').addEventListener('click', () => {
        currentMonth.setMonth(currentMonth.getMonth() - 1);
        generateCalendar();
    });

    document.getElementById('next-month').addEventListener('click', () => {
        currentMonth.setMonth(currentMonth.getMonth() + 1);
        generateCalendar();
    });

    // Auto-refresh stats every 30 seconds
    setInterval(() => {
        fetch('{{ route("logistik.api.stats") }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('stat-today').textContent = data.today_pickups;
                document.getElementById('stat-pending').textContent = data.pending_today;
                document.getElementById('stat-completed').textContent = data.completed_today;
                document.getElementById('stat-total').textContent = data.total_pickups;
            });
    }, 30000);

    // Filter functionality
    document.getElementById('filter-status').addEventListener('change', function() {
        const status = this.value;
        document.querySelectorAll('.pickup-card').forEach(card => {
            if (!status || card.dataset.status === status) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>
@endsection
