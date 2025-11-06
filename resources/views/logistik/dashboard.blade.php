@extends('layouts.app')

@section('title', 'Re-Glow - Logistics Dashboard')

@section('styles')
<style>
    .dashboard-container {
        background-color: #f8f9fa;
        min-height: calc(100vh - 120px);
        padding: 2rem;
    }

    .dashboard-header {
        margin-bottom: 2rem;
    }

    .dashboard-header h1 {
        font-size: 2rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
    }

    .dashboard-header p {
        color: #6c757d;
        font-size: 0.95rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-icon.pink { background-color: #ffe0e6; }
    .stat-icon.green { background-color: #d4f4dd; }
    .stat-icon.yellow { background-color: #fff4cc; }
    .stat-icon.blue { background-color: #dbeafe; }

    .stat-info h3 {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .stat-info .stat-number {
        font-size: 1.75rem;
        font-weight: 600;
        color: #1a1a1a;
    }

    .content-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    @media (min-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    .card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .card-header h2 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1a1a1a;
    }

    .btn-add {
        background-color: #2d4a3e;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.9rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: background-color 0.2s;
    }

    .btn-add:hover {
        background-color: #234032;
    }

    .pickup-item {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 0.75rem;
        background-color: #fafafa;
        transition: background-color 0.2s;
    }

    .pickup-item:hover {
        background-color: #f5f5f5;
    }

    .pickup-icon {
        width: 40px;
        height: 40px;
        background-color: #ffe0e6;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #e91e63;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .pickup-details {
        flex: 1;
    }

    .pickup-details h3 {
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.25rem;
    }

    .pickup-details .address {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }

    .pickup-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.85rem;
    }

    .pickup-time {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .time-badge {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .time-badge.green { background-color: #22c55e; }
    .time-badge.yellow { background-color: #eab308; }
    .time-badge.blue { background-color: #3b82f6; }

    .pickup-contact {
        color: #6c757d;
    }

    .pickup-actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border: none;
        background-color: white;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        transition: all 0.2s;
    }

    .action-btn:hover {
        background-color: #f5f5f5;
        color: #1a1a1a;
    }

    .action-btn.alert {
        color: #ef4444;
    }

    .history-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .history-item:last-child {
        border-bottom: none;
    }

    .history-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .history-icon.success {
        background-color: #d4f4dd;
        color: #22c55e;
    }

    .history-icon.warning {
        background-color: #fff4cc;
        color: #eab308;
    }

    .history-info {
        flex: 1;
    }

    .history-info h3 {
        font-size: 0.95rem;
        font-weight: 500;
        color: #1a1a1a;
        margin-bottom: 0.25rem;
    }

    .history-info .date {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .history-status {
        font-size: 0.85rem;
        font-weight: 500;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
    }

    .history-status.completed {
        background-color: #d4f4dd;
        color: #16a34a;
    }

    .history-status.issue {
        background-color: #fff4cc;
        color: #ca8a04;
    }

    .notification-item {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
    }

    .notification-item:hover {
        background-color: #fafafa;
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .notification-icon.pink {
        background-color: #ffe0e6;
        color: #e91e63;
    }

    .notification-icon.red {
        background-color: #fee2e2;
        color: #ef4444;
    }

    .notification-icon.blue {
        background-color: #dbeafe;
        color: #3b82f6;
    }

    .notification-icon.green {
        background-color: #d4f4dd;
        color: #22c55e;
    }

    .notification-content {
        flex: 1;
    }

    .notification-content h3 {
        font-size: 0.9rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.25rem;
    }

    .notification-content p {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }

    .notification-time {
        font-size: 0.75rem;
        color: #9ca3af;
    }

    .full-width {
        grid-column: 1 / -1;
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Logistics Dashboard</h1>
        <p>Manage waste collection schedules and track operational performance</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon pink">📦</div>
            <div class="stat-info">
                <h3>Today's Pickups</h3>
                <div class="stat-number">12</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">✓</div>
            <div class="stat-info">
                <h3>Completed</h3>
                <div class="stat-number">8</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon yellow">⏱</div>
            <div class="stat-info">
                <h3>Pending</h3>
                <div class="stat-number">4</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">🚛</div>
            <div class="stat-info">
                <h3>Active Vehicles</h3>
                <div class="stat-number">6</div>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="content-grid">
        <!-- Scheduled Today -->
        <div class="card full-width">
            <div class="card-header">
                <h2>Scheduled Today</h2>
                <button class="btn-add">
                    <span>+</span>
                    Add Pickup
                </button>
            </div>

            <div class="pickup-item">
                <div class="pickup-icon">📍</div>
                <div class="pickup-details">
                    <h3>Posko Soekarno-Hatta</h3>
                    <div class="address">123 Business Street, City Center</div>
                    <div class="pickup-meta">
                        <div class="pickup-time">
                            <span class="time-badge green"></span>
                            <span>09:30 AM</span>
                        </div>
                        <div class="pickup-contact">Contact: Sarah Johnson</div>
                    </div>
                </div>
                <div class="pickup-actions">
                    <button class="action-btn">👁</button>
                    <button class="action-btn">✏️</button>
                    <button class="action-btn alert">⚠️</button>
                </div>
            </div>

            <div class="pickup-item">
                <div class="pickup-icon">📍</div>
                <div class="pickup-details">
                    <h3>Posko Klojen</h3>
                    <div class="address">456 Residential Ave, Suburb District</div>
                    <div class="pickup-meta">
                        <div class="pickup-time">
                            <span class="time-badge yellow"></span>
                            <span>11:15 AM</span>
                        </div>
                        <div class="pickup-contact">Contact: Mike Chen</div>
                    </div>
                </div>
                <div class="pickup-actions">
                    <button class="action-btn">👁</button>
                    <button class="action-btn">✏️</button>
                    <button class="action-btn alert">⚠️</button>
                </div>
            </div>

            <div class="pickup-item">
                <div class="pickup-icon">📍</div>
                <div class="pickup-details">
                    <h3>Posko Universitas Brawijaya</h3>
                    <div class="address">789 Innovation Drive, Tech Park</div>
                    <div class="pickup-meta">
                        <div class="pickup-time">
                            <span class="time-badge blue"></span>
                            <span>02:00 PM</span>
                        </div>
                        <div class="pickup-contact">Contact: Emma Davis</div>
                    </div>
                </div>
                <div class="pickup-actions">
                    <button class="action-btn">👁</button>
                    <button class="action-btn">✏️</button>
                    <button class="action-btn alert">⚠️</button>
                </div>
            </div>
        </div>

        <!-- Recent Pick-Ups -->
        <div class="card">
            <div class="card-header">
                <h2>Recent Pick-Ups</h2>
            </div>

            <div class="history-item">
                <div class="history-icon success">✓</div>
                <div class="history-info">
                    <h3>Metro Shopping Center</h3>
                    <div class="date">Oct 25, 2024 - 10:45 AM</div>
                </div>
                <div class="history-status completed">Completed</div>
            </div>

            <div class="history-item">
                <div class="history-icon success">✓</div>
                <div class="history-info">
                    <h3>University Campus</h3>
                    <div class="date">Oct 25, 2024 - 08:30 AM</div>
                </div>
                <div class="history-status completed">Completed</div>
            </div>

            <div class="history-item">
                <div class="history-icon success">✓</div>
                <div class="history-info">
                    <h3>Corporate Plaza</h3>
                    <div class="date">Oct 24, 2024 - 03:15 PM</div>
                </div>
                <div class="history-status completed">Completed</div>
            </div>

            <div class="history-item">
                <div class="history-icon warning">!</div>
                <div class="history-info">
                    <h3>Riverside Mall</h3>
                    <div class="date">Oct 24, 2024 - 01:20 PM</div>
                </div>
                <div class="history-status issue">Issue Reported</div>
            </div>
        </div>

        <!-- System Notifications -->
        <div class="card">
            <div class="card-header">
                <h2>System Notifications</h2>
            </div>

            <div class="notification-item">
                <div class="notification-icon pink">📋</div>
                <div class="notification-content">
                    <h3>New Pickup Assigned</h3>
                    <p>You have been assigned a new collection at Eastside Community Center for tomorrow at 9:00 AM.</p>
                    <div class="notification-time">2 hours ago</div>
                </div>
            </div>

            <div class="notification-item">
                <div class="notification-icon red">⚠️</div>
                <div class="notification-content">
                    <h3>Urgent: Route Change</h3>
                    <p>Route 5 has been modified due to road construction. Please check updated directions.</p>
                    <div class="notification-time">4 hours ago</div>
                </div>
            </div>

            <div class="notification-item">
                <div class="notification-icon blue">🔔</div>
                <div class="notification-content">
                    <h3>Schedule Update</h3>
                    <p>Green Valley Apartments pickup has been rescheduled from 10:30 AM to 11:15 AM.</p>
                    <div class="notification-time">6 hours ago</div>
                </div>
            </div>

            <div class="notification-item">
                <div class="notification-icon green">✓</div>
                <div class="notification-content">
                    <h3>Vehicle Maintenance Complete</h3>
                    <p>Truck LG-003 has completed scheduled maintenance and is ready for service.</p>
                    <div class="notification-time">1 day ago</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Add any JavaScript functionality here
    document.addEventListener('DOMContentLoaded', function() {
        // Add Pickup button handler
        const addPickupBtn = document.querySelector('.btn-add');
        if (addPickupBtn) {
            addPickupBtn.addEventListener('click', function() {
                // Handle add pickup action
                console.log('Add pickup clicked');
            });
        }

        // Action buttons handlers
        const actionBtns = document.querySelectorAll('.action-btn');
        actionBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Handle action button clicks
                console.log('Action button clicked');
            });
        });
    });
</script>
@endsection