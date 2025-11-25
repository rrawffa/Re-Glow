@extends('layouts.app')

@section('title', 'Re-Glow - Admin Vouchers')

@section('styles')
<style>
    body {
        background: linear-gradient(135deg, #f5faf8 0%, #f0f8f5 100%);
    }

    main {
        min-height: calc(100vh - 300px);
        padding: 40px 20px;
    }

    .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #2c5f4f 0%, #1f4438 100%);
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 32px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            color: #fff;
        }

        .header h1 {
            font-size: 32px;
            font-weight: 700;
            margin: 0 0 8px;
            letter-spacing: -0.5px;
        }

        .header p {
            font-size: 15px;
            line-height: 1.6;
            opacity: 0.95;
            margin: 0;
        }

        /* Controls */
        .controls {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-box {
            flex: 1;
            position: relative;
            min-width: 200px;
        }

        .search-box input {
            width: 100%;
            padding: 12px 16px 12px 44px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            background: #fff;
            font-family: inherit;
        }

        .search-box input:focus {
            outline: none;
            border-color: #2c5f4f;
            box-shadow: 0 0 0 3px rgba(44, 95, 79, 0.1);
        }

        .search-box::before {
            content: "🔍";
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
        }

        .status-filter {
            padding: 12px 40px 12px 16px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background: #fff;
            font-size: 14px;
            cursor: pointer;
            font-family: inherit;
        }

        .status-filter:focus {
            outline: none;
            border-color: #2c5f4f;
            box-shadow: 0 0 0 3px rgba(44, 95, 79, 0.1);
        }

        .add-btn {
            padding: 12px 24px;
            background: linear-gradient(135deg, #2c5f4f 0%, #1f4438 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .add-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(44, 95, 79, 0.3);
        }

        /* Table Card */
        .table-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .voucher-table {
            width: 100%;
            border-collapse: collapse;
        }

        .voucher-table thead th {
            text-align: left;
            padding: 16px;
            font-size: 13px;
            font-weight: 600;
            color: #1a1a1a;
            background: #f9f9f9;
            border-bottom: 2px solid #e0e0e0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .voucher-table tbody tr {
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.2s ease;
        }

        .voucher-table tbody tr:hover {
            background: #f9f9f9;
        }

        .voucher-table tbody td {
            padding: 16px;
            font-size: 14px;
            color: #1a1a1a;
        }

        .voucher-img {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .voucher-name {
            font-weight: 600;
            color: #1a1a1a;
        }

        .voucher-brand {
            color: #666;
            font-size: 13px;
            margin-top: 4px;
        }

        .points-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f7fff8;
            color: #2c5f4f;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
        }

        .points-badge::before {
            content: "🎫";
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            text-align: center;
        }

        .status-active {
            background: #d4f4dd;
            color: #1a7f37;
        }

        .status-low-stock {
            background: #fff3cd;
            color: #856404;
        }

        .status-out-of-stock {
            background: #f8d7da;
            color: #721c24;
        }

        .status-expired {
            background: #e0e0e0;
            color: #666;
        }

        .action-btns {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border: none;
            background: #f0f0f0;
            cursor: pointer;
            font-size: 16px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            background: #2c5f4f;
            transform: translateY(-2px);
        }

        /* Footer */
        .footer {
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            color: #666;
            font-size: 14px;
        }

        .pagination {
            display: flex;
            gap: 8px;
        }

        .page-btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e0e0e0;
            background: #fff;
            border-radius: 6px;
            color: #1a1a1a;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .page-btn:hover {
            background: #f0f0f0;
        }

        .page-btn.active {
            background: #2c5f4f;
            color: #fff;
            border-color: #2c5f4f;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 32px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .modal-header h2 {
            font-size: 24px;
            font-weight: 600;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #666;
            line-height: 1;
        }

        .close-btn:hover {
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 14px;
            color: #333;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #2c5f4f;
            box-shadow: 0 0 0 3px rgba(44, 95, 79, 0.1);
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #2c5f4f 0%, #1f4438 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(44, 95, 79, 0.3);
        }

        .image-preview {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-top: 8px;
            display: none;
        }

        .image-preview.active {
            display: block;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state p {
            font-size: 16px;
            margin-bottom: 16px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 20px 10px;
            }

            .header {
                padding: 24px;
            }

            .header h1 {
                font-size: 24px;
            }

            .controls {
                flex-direction: column;
            }

            .search-box {
                min-width: 100%;
            }

            .add-btn {
                width: 100%;
                justify-content: center;
            }

            .voucher-table {
                font-size: 13px;
            }

            .voucher-table thead th,
            .voucher-table tbody td {
                padding: 12px 8px;
            }

            .footer {
                flex-direction: column;
                text-align: center;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
<div class="container">
    <!-- Header -->
    <div class="header">
        <h1>🎫 Voucher Management</h1>
        <p>Create and manage vouchers. All vouchers will be visible to users upon publishing.</p>
    </div>

    <!-- Controls -->
    <div class="controls">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Search by voucher name or brand...">
        </div>
        <select class="status-filter" id="statusFilter">
            <option value="all">All Status</option>
            <option value="active">Active</option>
            <option value="low-stock">Low Stock</option>
            <option value="out-of-stock">Out of Stock</option>
            <option value="expired">Expired</option>
        </select>
        <button class="add-btn" onclick="openModal()">
            <span>+</span> Add Voucher
        </button>
    </div>

    <!-- Table -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="voucher-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">Image</th>
                        <th>Voucher Name</th>
                        <th style="width: 150px;">Brand</th>
                        <th style="width: 140px;">Points</th>
                        <th style="width: 140px;">Expires</th>
                        <th style="width: 80px;">Stock</th>
                        <th style="width: 120px;">Status</th>
                        <th style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="voucherTableBody">
                    <!-- Vouchers populated by JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div>Showing <strong id="voucherCount">0</strong> of <strong id="totalCount">0</strong> vouchers</div>
        <div class="pagination">
            <button class="page-btn">‹</button>
            <button class="page-btn active">1</button>
            <button class="page-btn">›</button>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal" id="voucherModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Add New Voucher</h2>
            <button class="close-btn" onclick="closeModal()">×</button>
        </div>
        <form id="voucherForm" onsubmit="saveVoucher(event)">
            <input type="hidden" id="voucherId">
            
            <div class="form-group">
                <label for="name">Voucher Name *</label>
                <input type="text" id="name" required>
            </div>

            <div class="form-group">
                <label for="brand">Brand</label>
                <input type="text" id="brand">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description"></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="requiredPoints">Required Points *</label>
                    <input type="number" id="requiredPoints" required min="0" value="0">
                </div>

                <div class="form-group">
                    <label for="stock">Stock *</label>
                    <input type="number" id="stock" required min="0" value="0">
                </div>
            </div>

            <div class="form-group">
                <label for="expirationDate">Expiration Date</label>
                <input type="date" id="expirationDate">
            </div>

            <div class="form-group">
                <label for="imageUrl">Image URL</label>
                <input type="url" id="imageUrl" placeholder="https://example.com/image.jpg" oninput="previewImage()">
                <img id="imagePreview" class="image-preview" alt="Preview">
            </div>

            <button type="submit" class="submit-btn">Save Voucher</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let vouchers = [];

    // Initialize with sample data
    function initSampleData() {
        vouchers = [
            {
                id: 1,
                name: 'Organic Facial Treatment',
                brand: 'Green Spa Co.',
                description: 'Relaxing organic facial treatment',
                required_points: 500,
                expiration_date: '2024-12-31',
                stock: 45,
                image_url: 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="100" height="100"%3E%3Crect fill="%23c8e6c9" width="100" height="100"/%3E%3Ctext x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" font-size="40"%3E🌿%3C/text%3E%3C/svg%3E'
            },
            {
                id: 2,
                name: 'Natural Skincare Bundle',
                brand: 'EcoGlow Beauty',
                description: 'Complete natural skincare set',
                required_points: 750,
                expiration_date: '2025-01-15',
                stock: 12,
                image_url: 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="100" height="100"%3E%3Crect fill="%23ffccbc" width="100" height="100"/%3E%3Ctext x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" font-size="40"%3E💆%3C/text%3E%3C/svg%3E'
            },
            {
                id: 3,
                name: 'Wellness Yoga Session',
                brand: 'Zen Wellness',
                description: 'One hour private yoga session',
                required_points: 300,
                expiration_date: '2024-11-20',
                stock: 0,
                image_url: 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="100" height="100"%3E%3Crect fill="%23fff9c4" width="100" height="100"/%3E%3Ctext x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" font-size="40"%3E🧘%3C/text%3E%3C/svg%3E'
            },
            {
                id: 4,
                name: 'Organic Food Basket',
                brand: 'Fresh Harvest',
                description: 'Weekly organic produce basket',
                required_points: 600,
                expiration_date: '2024-10-10',
                stock: 28,
                image_url: 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="100" height="100"%3E%3Crect fill="%23c5e1a5" width="100" height="100"/%3E%3Ctext x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" font-size="40"%3E🥬%3C/text%3E%3C/svg%3E'
            },
            {
                id: 5,
                name: 'Sustainable Fashion Voucher',
                brand: 'GreenThreads',
                description: '$50 voucher for sustainable clothing',
                required_points: 850,
                expiration_date: '2025-02-28',
                stock: 65,
                image_url: 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="100" height="100"%3E%3Crect fill="%23ce93d8" width="100" height="100"/%3E%3Ctext x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" font-size="40"%3E👗%3C/text%3E%3C/svg%3E'
            }
        ];
        renderVouchers();
    }

    function getStatus(voucher) {
        const today = new Date();
        const expDate = voucher.expiration_date ? new Date(voucher.expiration_date) : null;
        
        if (expDate && expDate < today) {
            return { class: 'status-expired', text: 'Expired' };
        } else if (voucher.stock === 0) {
            return { class: 'status-out-of-stock', text: 'Out of Stock' };
        } else if (voucher.stock <= 15) {
            return { class: 'status-low-stock', text: 'Low Stock' };
        } else {
            return { class: 'status-active', text: 'Active' };
        }
    }

    function renderVouchers() {
        const tbody = document.getElementById('voucherTableBody');
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        
        let filtered = vouchers.filter(v => {
            const matchesSearch = v.name.toLowerCase().includes(searchTerm) || 
                                (v.brand && v.brand.toLowerCase().includes(searchTerm));
            
            if (!matchesSearch) return false;
            if (statusFilter === 'all') return true;
            
            const status = getStatus(v);
            const statusMap = {
                'active': 'Active',
                'low-stock': 'Low Stock',
                'out-of-stock': 'Out of Stock',
                'expired': 'Expired'
            };
            
            return status.text === statusMap[statusFilter];
        });
        
        if (filtered.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <p>No vouchers found</p>
                        </div>
                    </td>
                </tr>
            `;
        } else {
            tbody.innerHTML = filtered.map(v => {
                const status = getStatus(v);
                const expDate = v.expiration_date ? new Date(v.expiration_date).toLocaleDateString('en-US', { 
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric' 
                }) : '-';
                
                return `
                    <tr>
                        <td><img src="${v.image_url || 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="100" height="100"%3E%3Crect fill="%23e0e0e0" width="100" height="100"/%3E%3Ctext x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" font-size="40"%3E🎫%3C/text%3E%3C/svg%3E'}" class="voucher-img" alt="${v.name}"></td>
                        <td>
                            <div class="voucher-name">${v.name}</div>
                            ${v.description ? `<div class="voucher-brand">${v.description.substring(0, 40)}${v.description.length > 40 ? '...' : ''}</div>` : ''}
                        </td>
                        <td>${v.brand || '-'}</td>
                        <td><span class="points-badge">${v.required_points}</span></td>
                        <td>${expDate}</td>
                        <td><strong>${v.stock}</strong></td>
                        <td><span class="status-badge ${status.class}">${status.text}</span></td>
                        <td>
                            <div class="action-btns">
                                <button class="action-btn" onclick="editVoucher(${v.id})" title="Edit">✏️</button>
                                <button class="action-btn" onclick="deleteVoucher(${v.id})" title="Delete">🗑️</button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }
        
        document.getElementById('voucherCount').textContent = filtered.length;
        document.getElementById('totalCount').textContent = vouchers.length;
    }

    function openModal(id = null) {
        const modal = document.getElementById('voucherModal');
        const form = document.getElementById('voucherForm');
        const title = document.getElementById('modalTitle');
        
        form.reset();
        document.getElementById('imagePreview').classList.remove('active');
        
        if (id) {
            const voucher = vouchers.find(v => v.id === id);
            title.textContent = 'Edit Voucher';
            document.getElementById('voucherId').value = voucher.id;
            document.getElementById('name').value = voucher.name;
            document.getElementById('brand').value = voucher.brand || '';
            document.getElementById('description').value = voucher.description || '';
            document.getElementById('requiredPoints').value = voucher.required_points;
            document.getElementById('stock').value = voucher.stock;
            document.getElementById('expirationDate').value = voucher.expiration_date || '';
            document.getElementById('imageUrl').value = voucher.image_url || '';
            
            if (voucher.image_url) {
                previewImage();
            }
        } else {
            title.textContent = 'Add New Voucher';
            document.getElementById('requiredPoints').value = '0';
            document.getElementById('stock').value = '0';
        }
        
        modal.classList.add('active');
    }

    function closeModal() {
        document.getElementById('voucherModal').classList.remove('active');
    }

    function previewImage() {
        const url = document.getElementById('imageUrl').value;
        const preview = document.getElementById('imagePreview');
        
        if (url) {
            preview.src = url;
            preview.classList.add('active');
        } else {
            preview.classList.remove('active');
        }
    }

    function saveVoucher(event) {
        event.preventDefault();
        
        const id = document.getElementById('voucherId').value;
        const voucherData = {
            name: document.getElementById('name').value,
            brand: document.getElementById('brand').value || null,
            description: document.getElementById('description').value || null,
            required_points: parseInt(document.getElementById('requiredPoints').value),
            expiration_date: document.getElementById('expirationDate').value || null,
            stock: parseInt(document.getElementById('stock').value),
            image_url: document.getElementById('imageUrl').value || null
        };
        
        if (id) {
            const index = vouchers.findIndex(v => v.id === parseInt(id));
            vouchers[index] = { ...vouchers[index], ...voucherData };
        } else {
            const newId = vouchers.length > 0 ? Math.max(...vouchers.map(v => v.id)) + 1 : 1;
            vouchers.push({ id: newId, ...voucherData });
        }
        
        renderVouchers();
        closeModal();
    }

    function editVoucher(id) {
        openModal(id);
    }

    function deleteVoucher(id) {
        if (confirm('Are you sure you want to delete this voucher?')) {
            vouchers = vouchers.filter(v => v.id !== id);
            renderVouchers();
        }
    }

    // Event listeners
    document.getElementById('searchInput').addEventListener('input', renderVouchers);
    document.getElementById('statusFilter').addEventListener('change', renderVouchers);
    
    // Close modal when clicking outside
    document.getElementById('voucherModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Initialize
    initSampleData();
</script>
@endsection