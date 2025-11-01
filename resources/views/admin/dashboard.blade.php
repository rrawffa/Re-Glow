@extends('layouts.app')

@section('title', 'Re-Glow - Dashboard Admin')

@section('styles')
<style>
    :root {
        --pink-light: #fef5f8;
        --pink-base: #F9B6C7;
        --green-dark: #20413A;
        --text-gray: #666;
        --text-light: #999;
        --border-color: #e5e7eb;
    }

    body {
        background: #f9fafb;
    }

    /* Admin Header */
    .admin-header {
        background: white;
        padding: 2.5rem 5% 2rem;
        border-bottom: 1px solid var(--border-color);
    }

    .admin-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .admin-header p {
        color: var(--text-gray);
        font-size: 1rem;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        padding: 2rem 5%;
        max-width: 1400px;
        margin: 0 auto;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        position: relative;
        overflow: hidden;
    }

    .stat-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--text-gray);
        font-weight: 500;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .stat-icon.pink { background: #fef5f8; }
    .stat-icon.gray { background: #f3f4f6; }
    .stat-icon.yellow { background: #fef9e6; }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .stat-change {
        font-size: 0.875rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .stat-change.positive { color: #10b981; }
    .stat-change.negative { color: #ef4444; }

    /* Charts Section */
    .charts-container {
        padding: 0 5% 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .chart-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }

    .chart-card h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1.5rem;
    }

    /* Bar Chart */
    .bar-chart {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        height: 200px;
        gap: 1rem;
        padding: 0 0.5rem;
    }

    .bar-group {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }

    .bar {
        width: 100%;
        background: var(--pink-base);
        border-radius: 6px 6px 0 0;
        transition: all 0.3s;
        position: relative;
    }

    .bar:hover {
        opacity: 0.8;
        transform: translateY(-2px);
    }

    .bar-label {
        font-size: 0.75rem;
        color: var(--text-light);
        font-weight: 500;
    }

    /* Donut Chart */
    .donut-chart-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
    }

    .donut-chart {
        position: relative;
        width: 200px;
        height: 200px;
    }

    .donut-legend {
        flex: 1;
    }

    .legend-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .legend-item:last-child {
        border-bottom: none;
    }

    .legend-label {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.875rem;
        color: var(--text-gray);
    }

    .legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    .legend-dot.pink { background: var(--pink-base); }
    .legend-dot.dark { background: #1f2937; }
    .legend-dot.beige { background: #d1c4b0; }

    .legend-value {
        font-weight: 600;
        color: #1f2937;
    }

    /* Quick Actions */
    .quick-actions {
        margin-bottom: 2rem;
    }

    .quick-actions h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1rem;
    }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .action-card {
        background: white;
        padding: 1.25rem;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 1rem;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        color: inherit;
    }

    .action-card:hover {
        border-color: var(--pink-base);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(249, 182, 199, 0.15);
    }

    .action-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .action-icon.pink { background: #fef5f8; }
    .action-icon.gray { background: #f3f4f6; }
    .action-icon.yellow { background: #fef9e6; }

    .action-label {
        font-size: 0.95rem;
        font-weight: 500;
        color: #1f2937;
    }

    /* Recent Activities */
    .recent-activities h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1rem;
    }

    .activity-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }

    .activity-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .activity-item:first-child {
        padding-top: 0;
    }

    .activity-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1rem;
    }

    .activity-icon.pink { background: #fef5f8; color: var(--pink-base); }
    .activity-icon.green { background: #e8f5f0; color: var(--green-dark); }
    .activity-icon.yellow { background: #fef9e6; color: #d4a843; }

    .activity-content {
        flex: 1;
    }

    .activity-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }

    .activity-description {
        font-size: 0.875rem;
        color: var(--text-gray);
    }

    .activity-time {
        font-size: 0.875rem;
        color: var(--text-light);
        white-space: nowrap;
    }

    @media (max-width: 1024px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .admin-header {
            padding: 1.5rem 4%;
        }

        .admin-header h1 {
            font-size: 1.5rem;
        }

        .stats-grid {
            padding: 1.5rem 4%;
            grid-template-columns: 1fr;
        }

        .charts-container {
            padding: 0 4% 1.5rem;
        }

        .donut-chart-container {
            flex-direction: column;
        }

        .bar-chart {
            gap: 0.5rem;
            height: 180px;
        }
    }
</style>
@endsection

@section('content')
    <!-- Admin Header -->
    <section class="admin-header">
        <h1>Admin Dashboard</h1>
        <p>Monitor and manage your Re-Glow platform operations</p>
    </section>

    <!-- Stats Grid -->
    <section class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-label">Total Users</span>
                <div class="stat-icon pink">👥</div>
            </div>
            <div class="stat-value">12,847</div>
            <div class="stat-change positive">
                ↑ 8.2% from last month
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-label">Total Transactions</span>
                <div class="stat-icon gray">⇄</div>
            </div>
            <div class="stat-value">3,294</div>
            <div class="stat-change positive">
                ↑ 12.5% from last month
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-label">Active Vouchers</span>
                <div class="stat-icon yellow">🎫</div>
            </div>
            <div class="stat-value">1,856</div>
            <div class="stat-change negative">
                ↓ 2.1% from last month
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-label">Educational Posts</span>
                <div class="stat-icon pink">📚</div>
            </div>
            <div class="stat-value">247</div>
            <div class="stat-change positive">
                ↑ 15.3% engagement
            </div>
        </div>
    </section>

    <!-- Charts Section -->
    <section class="charts-container">
        <div class="charts-grid">
            <!-- User Growth Chart -->
            <div class="chart-card">
                <h3>User Growth</h3>
                <div class="bar-chart">
                    <div class="bar-group">
                        <div class="bar" style="height: 35%;"></div>
                        <span class="bar-label">Jan</span>
                    </div>
                    <div class="bar-group">
                        <div class="bar" style="height: 55%;"></div>
                        <span class="bar-label">Feb</span>
                    </div>
                    <div class="bar-group">
                        <div class="bar" style="height: 72%;"></div>
                        <span class="bar-label">Mar</span>
                    </div>
                    <div class="bar-group">
                        <div class="bar" style="height: 48%;"></div>
                        <span class="bar-label">Apr</span>
                    </div>
                    <div class="bar-group">
                        <div class="bar" style="height: 78%;"></div>
                        <span class="bar-label">May</span>
                    </div>
                    <div class="bar-group">
                        <div class="bar" style="height: 62%;"></div>
                        <span class="bar-label">Jun</span>
                    </div>
                    <div class="bar-group">
                        <div class="bar" style="height: 88%;"></div>
                        <span class="bar-label">Jul</span>
                    </div>
                    <div class="bar-group">
                        <div class="bar" style="height: 95%;"></div>
                        <span class="bar-label">Aug</span>
                    </div>
                </div>
            </div>

            <!-- Transaction Types Chart -->
            <div class="chart-card">
                <h3>Transaction Types</h3>
                <div class="donut-chart-container">
                    <div class="donut-chart">
                        <svg width="200" height="200" viewBox="0 0 200 200">
                            <!-- Background circle -->
                            <circle cx="100" cy="100" r="80" fill="none" stroke="#f3f4f6" stroke-width="30"/>
                            
                            <!-- Waste Recycling - 65% -->
                            <circle cx="100" cy="100" r="80" fill="none" 
                                    stroke="#F9B6C7" stroke-width="30"
                                    stroke-dasharray="326 502"
                                    stroke-dashoffset="0"
                                    transform="rotate(-90 100 100)"/>
                            
                            <!-- Voucher Redemption - 25% -->
                            <circle cx="100" cy="100" r="80" fill="none" 
                                    stroke="#1f2937" stroke-width="30"
                                    stroke-dasharray="126 502"
                                    stroke-dashoffset="-326"
                                    transform="rotate(-90 100 100)"/>
                            
                            <!-- Educational Rewards - 10% -->
                            <circle cx="100" cy="100" r="80" fill="none" 
                                    stroke="#d1c4b0" stroke-width="30"
                                    stroke-dasharray="50 502"
                                    stroke-dashoffset="-452"
                                    transform="rotate(-90 100 100)"/>
                        </svg>
                    </div>
                    <div class="donut-legend">
                        <div class="legend-item">
                            <div class="legend-label">
                                <span class="legend-dot pink"></span>
                                Waste Recycling
                            </div>
                            <span class="legend-value">65%</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-label">
                                <span class="legend-dot dark"></span>
                                Voucher Redemption
                            </div>
                            <span class="legend-value">25%</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-label">
                                <span class="legend-dot beige"></span>
                                Educational Rewards
                            </div>
                            <span class="legend-value">10%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h3>Quick Actions</h3>
            <div class="actions-grid">
                <a href="{{ route('admin.users') }}" class="action-card">
                    <div class="action-icon pink">👥</div>
                    <span class="action-label">Manage Users</span>
                </a>
                <a href="{{ route('admin.vouchers') }}" class="action-card">
                    <div class="action-icon gray">🎫</div>
                    <span class="action-label">Voucher Management</span>
                </a>
                <a href="{{ route('admin.education') }}" class="action-card">
                    <div class="action-icon yellow">📚</div>
                    <span class="action-label">Education Content</span>
                </a>
                <a href="{{ route('admin.faq') }}" class="action-card">
                    <div class="action-icon pink">❓</div>
                    <span class="action-label">FAQ Management</span>
                </a>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="recent-activities">
            <h3>Recent Activities</h3>
            <div class="activity-card">
                <div class="activity-item">
                    <div class="activity-icon pink">👤</div>
                    <div class="activity-content">
                        <div class="activity-title">New user registration</div>
                        <div class="activity-description">Sarah Johnson joined the platform</div>
                    </div>
                    <div class="activity-time">2 min ago</div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon green">♻️</div>
                    <div class="activity-content">
                        <div class="activity-title">Waste transaction completed</div>
                        <div class="activity-description">15kg plastic bottles recycled</div>
                    </div>
                    <div class="activity-time">5 min ago</div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon yellow">🎫</div>
                    <div class="activity-content">
                        <div class="activity-title">Voucher redeemed</div>
                        <div class="activity-description">$10 eco-friendly store voucher</div>
                    </div>
                    <div class="activity-time">12 min ago</div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    // Add animation on load
    document.addEventListener('DOMContentLoaded', function() {
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.4s, transform 0.4s';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 50);
            }, index * 100);
        });
    });
</script>
@endsection