@extends('layouts.app')

@section('title', 'Edit Your Transaction - Re-Glow')

@section('styles')
    @vite(['resources/css/waste-exchange/create.css'])]
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        /* Additional styles */
        .current-photo-container {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 12px;
            border: 2px solid #e9ecef;
            position: relative;
        }
        
        .current-photo-label {
            font-weight: 600;
            color: var(--green-dark);
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .current-photo-wrapper {
            position: relative;
            display: inline-block;
        }
        
        .current-photo-image {
            max-width: 300px;
            max-height: 200px;
            width: auto;
            height: auto;
            border-radius: 8px;
            border: 2px solid #dee2e6;
            object-fit: contain;
        }
        
        .btn-remove-current {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(220, 53, 69, 0.9);
            color: white;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        
        .btn-remove-current:hover {
            background: rgba(200, 35, 51, 1);
            transform: scale(1.1);
        }
        
        .upload-section {
            margin-top: 1.5rem;
        }
        
        .upload-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .upload-header h4 {
            color: var(--green-dark);
            margin: 0;
            font-size: 1.1rem;
        }
        
        .btn-change-photo {
            background: var(--green-dark);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-change-photo:hover {
            background: #163026;
            transform: translateY(-1px);
        }
        
        .upload-instructions {
            background: #e3f2fd;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
            font-size: 0.9rem;
        }
        
        .upload-instructions ul {
            margin: 0.5rem 0 0 1rem;
            color: #1976d2;
        }
        
        .upload-instructions li {
            margin-bottom: 0.25rem;
        }
        
        .no-photo-message {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 1rem;
        }

        /* Styles untuk preview foto baru */
        .new-photo-preview {
            margin-top: 1rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 12px;
            border: 2px dashed #28a745;
        }
        
        .new-photo-label {
            font-weight: 600;
            color: #28a745;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .new-photo-wrapper {
            position: relative;
            display: inline-block;
        }
        
        .new-photo-image {
            max-width: 300px;
            max-height: 200px;
            width: auto;
            height: auto;
            border-radius: 8px;
            border: 2px solid #28a745;
            object-fit: contain;
        }
        
        .btn-remove-new {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(220, 53, 69, 0.9);
            color: white;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        
        .btn-remove-new:hover {
            background: rgba(200, 35, 51, 1);
            transform: scale(1.1);
        }
    </style>
@endsection

@section('content')
<div class="create-container">
    <a href="{{ route('waste-exchange.history') }}" style="color: var(--text-gray); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
        <i class="bi bi-arrow-left-circle"></i> Back to History
    </a>

    <div class="page-header">
        <h1>Edit Your Transaction</h1>
        <p>To process your changes correctly, please ensure all information below is filled in.</p>
    </div>

    <form id="wasteForm" method="POST" action="{{ route('waste-exchange.update', $transaksi->id_tSampah) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Step 1: Drop Point Selection -->
        <div class="form-section">
            <h3>Step 1: Drop Point Selection</h3>
            <p class="subtitle">Select where you'll drop off your cosmetic waste</p>

            <div class="form-group">
                <label>Select Your Drop Point Location <span class="required">*</span></label>
                
                <!-- Map Section dengan jarak yang tepat -->
                <div class="map-section">
                    <div class="map-placeholder">
                        <div id="map"></div>
                    </div>
                </div>

                <!-- DROPDOWN DROP POINT dengan margin yang cukup -->
                <div style="margin-top: 1.5rem;">
                    <select name="id_drop_point" id="dropPointSelect" class="form-control @error('id_drop_point') error @enderror" required>
                        <option value="">Choose a location...</option>
                        @foreach($dropPoints as $point)
                        <option value="{{ $point->id_drop_point }}" 
                                data-lat="{{ $point->latitude }}" 
                                data-lng="{{ $point->longitude }}"
                                data-nama="{{ $point->nama_lokasi }}"
                                data-alamat="{{ $point->alamat }}"
                                {{ $transaksi->id_drop_point == $point->id_drop_point ? 'selected' : '' }}>
                            {{ $point->nama_lokasi }} - {{ $point->alamat }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="error-message" id="dropPointError">Drop point harus dipilih</div>
                @error('id_drop_point')
                <div class="error-message show">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Step 2: Waste Item Details -->
        <div class="form-section">
            <h3>Step 2: Waste Item Details</h3>
            <p class="subtitle">Add details for each cosmetic item you're exchanging</p>

            <div id="productContainer">
                @foreach($transaksi->details as $index => $detail)
                <div class="product-item" data-product-index="{{ $index }}">
                    <div class="product-header">
                        <h4>Product #{{ $index + 1 }}</h4>
                        @if($index > 0)
                        <button type="button" class="btn-remove-product" onclick="removeProduct(this)">
                            <i class="bi bi-trash"></i> Remove
                        </button>
                        @endif
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Product Name/Type <span class="required">*</span></label>
                            <input type="text" 
                                   name="products[{{ $index }}][nama_produk]" 
                                   class="form-control"
                                   placeholder="e.g. Lipstick Tube, Face Wash Bottle"
                                   value="{{ $detail->jenis_sampah }}"
                                   required>
                            <div class="error-message">Nama produk harus diisi</div>
                        </div>

                        <div class="form-group">
                            <label>Packaging Category <span class="required">*</span></label>
                            <select name="products[{{ $index }}][packaging_category]" class="form-control" required>
                                <option value="">Select packaging type...</option>
                                <option value="Plastic Bottle" {{ $detail->jenis_sampah == 'Plastic Bottle' ? 'selected' : '' }}>Plastic Bottle</option>
                                <option value="Glass Jar" {{ $detail->jenis_sampah == 'Glass Jar' ? 'selected' : '' }}>Glass Jar</option>
                                <option value="Metal Tube" {{ $detail->jenis_sampah == 'Metal Tube' ? 'selected' : '' }}>Metal Tube</option>
                                <option value="Compact Case" {{ $detail->jenis_sampah == 'Compact Case' ? 'selected' : '' }}>Compact Case</option>
                            </select>
                            <div class="error-message">Packaging category harus dipilih</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Size Category <span class="required">*</span></label>
                            <select name="products[{{ $index }}][size_category]" class="form-control" required>
                                <option value="">Select size...</option>
                                <option value="Large" {{ $detail->ukuran_sampah == 'Large' ? 'selected' : '' }}>"Large" - >100ml/large palette</option>
                                <option value="Medium" {{ $detail->ukuran_sampah == 'Medium' ? 'selected' : '' }}>"Medium" - 50-100ml/standard jar</option>
                                <option value="Small" {{ $detail->ukuran_sampah == 'Small' ? 'selected' : '' }}>"Small" - <50ml/lipstick/eyeshadow single</option>
                            </select>
                            <div class="error-message">Size category harus dipilih</div>
                        </div>

                        <div class="form-group">
                            <label>Quantity <span class="required">*</span></label>
                            <input type="number" 
                                   name="products[{{ $index }}][quantity]" 
                                   class="form-control" 
                                   min="1" 
                                   value="{{ $detail->quantity }}"
                                   required>
                            <div class="error-message">Quantity harus diisi (minimal 1)</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="button" id="addProduct" class="btn-add-product">
                <i class="bi bi-plus-circle"></i> Add New Product
            </button>
        </div>

        <!-- Step 3: Proof of Waste -->
        <div class="form-section">
            <h3>Step 3: Proof of Waste</h3>
            <p class="subtitle">Update your proof photo if needed</p>

            <div class="form-group">
                <!-- Current Photo Display -->
                <div id="currentPhotoSection" @if(!$transaksi->foto_bukti) style="display: none;" @endif>
                    <div class="current-photo-container">
                        <span class="current-photo-label">Current Proof Photo:</span>
                        <div class="current-photo-wrapper">
                            <button type="button" id="removeCurrentPhoto" class="btn-remove-current" title="Remove current photo">
                                <i class="bi bi-x-lg"></i>
                            </button>
                            <img src="{{ $transaksi->foto_bukti ? asset($transaksi->foto_bukti) : '' }}" alt="Current proof" class="current-photo-image" id="currentPhotoImage">
                        </div>
                        <p style="color: var(--text-gray); font-size: 0.9rem; margin-top: 0.5rem;">
                            Click the X button to remove this photo. You'll need to upload a new one.
                        </p>
                    </div>
                </div>

                <!-- No Photo Message -->
                <div id="noPhotoMessage" class="no-photo-message" @if($transaksi->foto_bukti) style="display: none;" @endif>
                    <i class="bi bi-exclamation-triangle"></i>
                    No proof photo available. Please upload one using the button below.
                </div>

                <!-- New Photo Preview -->
                <div id="newPhotoPreview" class="new-photo-preview" style="display: none;">
                    <span class="new-photo-label">New Photo Preview:</span>
                    <div class="new-photo-wrapper">
                        <button type="button" id="removeNewPhoto" class="btn-remove-new" title="Remove new photo">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        <img id="previewImage" class="new-photo-image" alt="New photo preview">
                    </div>
                    <p style="color: var(--text-gray); font-size: 0.9rem; margin-top: 0.5rem;">
                        This is your new photo preview. Click the X button to cancel and keep the current photo.
                    </p>
                </div>

                <!-- Hidden input to track photo removal -->
                <input type="hidden" name="remove_current_photo" id="removeCurrentPhotoInput" value="0">

                <!-- Upload Section -->
                <div class="upload-section">
                    <div class="upload-header">
                        <h4 id="uploadSectionTitle">{{ $transaksi->foto_bukti ? 'Change Proof Photo' : 'Upload Proof Photo' }}</h4>
                        <button type="button" id="changePhotoBtn" class="btn-change-photo">
                            <i class="bi bi-cloud-upload"></i> 
                            <span id="changePhotoText">{{ $transaksi->foto_bukti ? 'Change Photo' : 'Upload Photo' }}</span>
                        </button>
                    </div>
                    
                    <p style="color: var(--text-gray); font-size: 0.9rem; margin-bottom: 1rem;" id="uploadDescription">
                        @if($transaksi->foto_bukti)
                        Upload a new photo if you want to update your proof. Leave empty to keep the current photo.
                        @else
                        Upload a clear photo of all your cosmetic empties grouped together.
                        @endif
                    </p>

                    <input type="file" 
                           name="foto_bukti" 
                           id="fotoInput" 
                           accept="image/*"
                           style="display: none;">
                </div>

                @error('foto_bukti')
                <div class="error-message show">{{ $message }}</div>
                @enderror

                <!-- Upload Instructions -->
                <div class="upload-instructions">
                    <strong style="color: #1976d2;">
                        <i class="bi bi-info-circle"></i> Photo Guidelines:
                    </strong>
                    <ul>
                        <li>Group all items together in one photo</li>
                        <li>Ensure clear visibility of packaging types</li>
                        <li>Use good lighting for accurate assessment</li>
                        <li>Include all items mentioned in your form</li>
                        <li>Maximum file size: 10MB</li>
                    </ul>
                </div>
            </div>
        </div>

        <button type="submit" id="submitBtn" class="btn-submit">
            <i class="bi bi-check-lg"></i> Update Transaction
        </button>
        <p class="note-text">Please complete all required fields to submit</p>
    </form>
</div>

<!-- Confirmation Modal -->
<div class="modal-overlay" id="confirmModal">
    <div class="modal-content">
        <div class="modal-icon">
            <i class="bi bi-check-circle"></i>
        </div>
        <h3>Confirm Your Changes</h3>
        <p>Are you sure all the information is correct? Once submitted, changes will be updated.</p>
        
        <div class="modal-buttons">
            <button type="button" class="btn-cancel" id="cancelSubmit">
                <i class="bi bi-arrow-left"></i> Review Again
            </button>
            <button type="button" class="btn-confirm" id="confirmSubmit">
                <i class="bi bi-check-lg"></i> Yes, Update
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>

<script>
let productIndex = {{ count($transaksi->details) }};
let map;
let selectedMarkerId = null;
let allMarkers = [];
let defaultIcon, selectedIcon;

// Initialize Map
function initMap() {
    // Center di Jawa Timur (Surabaya)
    const centerJawaTimur = [-7.2575, 112.7521];

    // Icon Kustom untuk marker default dan terpilih
    defaultIcon = L.divIcon({
        className: 'custom-marker',
        html: '<i class="bi bi-geo-alt-fill"></i>',
        iconSize: [40, 40],
        iconAnchor: [20, 40],
        popupAnchor: [0, -40]
    });

    selectedIcon = L.divIcon({
        className: 'selected-marker',
        html: '<i class="bi bi-geo-alt-fill"></i>',
        iconSize: [45, 45],
        iconAnchor: [22, 45],
        popupAnchor: [0, -45]
    });
    
    map = L.map('map', {
        center: centerJawaTimur,
        zoom: 11,
        scrollWheelZoom: true,
        zoomControl: true
    });

    // Tambahkan OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19,
    }).addTo(map);

    // Tambahkan semua markers dari dropdown
    const dropPointSelect = document.getElementById('dropPointSelect');
    for (let i = 1; i < dropPointSelect.options.length; i++) {
        const option = dropPointSelect.options[i];
        const lat = parseFloat(option.dataset.lat);
        const lng = parseFloat(option.dataset.lng);
        const nama = option.dataset.nama;
        const alamat = option.dataset.alamat;
        const id_drop_point = option.value;

        if (!isNaN(lat) && !isNaN(lng)) {
            addMarker(lat, lng, nama, alamat, id_drop_point);
        }
    }

    // Set selected drop point dari data transaksi
    const selectedOption = document.querySelector(`#dropPointSelect option[value="{{ $transaksi->id_drop_point }}"]`);
    if (selectedOption) {
        updateMapMarker(selectedOption);
    }
}

// Fungsi Helper: Tambah Marker
function addMarker(lat, lng, nama, alamat, id_drop_point) {
    const popupContent = `
        <div class="map-popup">
            <h4>${nama}</h4>
            <p>${alamat}</p>
            <span class="badge"><i class="bi bi-geo-alt-fill me-1"></i> Click to select this location</span>
        </div>
    `;
    
    const marker = L.marker([lat, lng], {
        icon: defaultIcon,
        id_drop_point: id_drop_point
    }).addTo(map);

    marker.bindPopup(popupContent, { maxWidth: 250 });
    
    // Event klik pada marker
    marker.on('click', function() {
        document.getElementById('dropPointSelect').value = id_drop_point;
        document.getElementById('dropPointSelect').dispatchEvent(new Event('change'));
    });
    
    allMarkers.push(marker);
}

// Update marker ketika dropdown berubah
document.getElementById('dropPointSelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    
    if (selectedOption.value) {
        updateMapMarker(selectedOption);
    } else {
        // Reset map jika tidak ada yang dipilih
        allMarkers.forEach(marker => {
            marker.setIcon(defaultIcon);
        });
        map.setView([-7.2575, 112.7521], 11);
    }
});

function updateMapMarker(selectedOption) {
    const lat = parseFloat(selectedOption.dataset.lat);
    const lng = parseFloat(selectedOption.dataset.lng);
    const id = selectedOption.value;

    // Reset semua marker ke icon default
    allMarkers.forEach(marker => {
        if (marker.options.id_drop_point == id) {
            marker.setIcon(selectedIcon);
            selectedMarkerId = id;
            marker.openPopup();
            map.setView(marker.getLatLng(), 15, {
                animate: true,
                duration: 0.5
            });
        } else {
            marker.setIcon(defaultIcon);
        }
    });
}

// Initialize map setelah DOM ready
document.addEventListener('DOMContentLoaded', function() {
    initMap();
});

// Add Product
document.getElementById('addProduct').addEventListener('click', function() {
    const container = document.getElementById('productContainer');
    const newProduct = document.createElement('div');
    newProduct.className = 'product-item';
    newProduct.dataset.productIndex = productIndex;
    
    newProduct.innerHTML = `
        <div class="product-header">
            <h4>Product #${productIndex + 1}</h4>
            <button type="button" class="btn-remove-product" onclick="removeProduct(this)">
                <i class="bi bi-trash"></i> Remove
            </button>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Product Name/Type <span class="required">*</span></label>
                <input type="text" name="products[${productIndex}][nama_produk]" class="form-control" placeholder="e.g. Lipstick Tube" required>
                <div class="error-message">Nama produk harus diisi</div>
            </div>
            <div class="form-group">
                <label>Packaging Category <span class="required">*</span></label>
                <select name="products[${productIndex}][packaging_category]" class="form-control" required>
                    <option value="">Select packaging type...</option>
                    <option value="Plastic Bottle">Plastic Bottle</option>
                    <option value="Glass Jar">Glass Jar</option>
                    <option value="Metal Tube">Metal Tube</option>
                    <option value="Compact Case">Compact Case</option>
                </select>
                <div class="error-message">Packaging category harus dipilih</div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Size Category <span class="required">*</span></label>
                <select name="products[${productIndex}][size_category]" class="form-control" required>
                    <option value="">Select size...</option>
                    <option value="Large">"Large" - >100ml/large palette</option>
                    <option value="Medium">"Medium" - 50-100ml/standard jar</option>
                    <option value="Small">"Small" - <50ml/lipstick/eyeshadow single</option>
                </select>
                <div class="error-message">Size category harus dipilih</div>
            </div>
            <div class="form-group">
                <label>Quantity <span class="required">*</span></label>
                <input type="number" name="products[${productIndex}][quantity]" class="form-control" min="1" value="1" required>
                <div class="error-message">Quantity harus diisi (minimal 1)</div>
            </div>
        </div>
    `;
    
    container.appendChild(newProduct);
    productIndex++;
});

function removeProduct(btn) {
    if (document.querySelectorAll('.product-item').length > 1) {
        btn.closest('.product-item').remove();
        // Update product numbers
        document.querySelectorAll('.product-item').forEach((item, index) => {
            item.querySelector('h4').textContent = `Product #${index + 1}`;
        });
    } else {
        alert('You need at least one product item.');
    }
}

// Photo Management
const removeCurrentPhotoBtn = document.getElementById('removeCurrentPhoto');
const currentPhotoSection = document.getElementById('currentPhotoSection');
const noPhotoMessage = document.getElementById('noPhotoMessage');
const newPhotoPreview = document.getElementById('newPhotoPreview');
const removeCurrentPhotoInput = document.getElementById('removeCurrentPhotoInput');
const changePhotoBtn = document.getElementById('changePhotoBtn');
const fotoInput = document.getElementById('fotoInput');
const previewImage = document.getElementById('previewImage');
const removeNewPhotoBtn = document.getElementById('removeNewPhoto');
const uploadSectionTitle = document.getElementById('uploadSectionTitle');
const changePhotoText = document.getElementById('changePhotoText');
const uploadDescription = document.getElementById('uploadDescription');

// Remove current photo
removeCurrentPhotoBtn.addEventListener('click', function() {
    if (confirm('Are you sure you want to remove the current photo? You will need to upload a new one.')) {
        // Hide current photo section, show no photo message
        currentPhotoSection.style.display = 'none';
        noPhotoMessage.style.display = 'block';
        
        // Set flag to remove current photo
        removeCurrentPhotoInput.value = '1';
        
        // Update upload section text
        uploadSectionTitle.textContent = 'Upload Proof Photo';
        changePhotoText.textContent = 'Upload Photo';
        uploadDescription.textContent = 'Upload a clear photo of all your cosmetic empties grouped together.';
        
        // Clear any existing file input and hide new photo preview
        fotoInput.value = '';
        newPhotoPreview.style.display = 'none';
    }
});

// Change/Upload photo button
changePhotoBtn.addEventListener('click', function() {
    fotoInput.click();
});

// File input change
fotoInput.addEventListener('change', (e) => {
    if (e.target.files[0]) {
        handleFileSelect(e.target.files[0]);
    }
});

function handleFileSelect(file) {
    // Validasi ukuran file (max 10MB)
    if (file.size > 10 * 1024 * 1024) {
        alert('File size must be less than 10MB');
        return;
    }
    
    // Validasi tipe file
    if (!file.type.startsWith('image/')) {
        alert('Please select an image file');
        return;
    }
    
    const reader = new FileReader();
    reader.onload = (e) => {
        previewImage.src = e.target.result;
        newPhotoPreview.style.display = 'block';
        
        // Scroll ke preview image
        newPhotoPreview.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        
        // Reset remove current photo flag if user uploads a new one
        removeCurrentPhotoInput.value = '0';
        
        // Jika ada current photo, sembunyikan pesan no photo
        if (currentPhotoSection.style.display !== 'none') {
            noPhotoMessage.style.display = 'none';
        }
    };
    reader.readAsDataURL(file);
}

// Remove new photo preview
removeNewPhotoBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    
    fotoInput.value = '';
    previewImage.src = '';
    newPhotoPreview.style.display = 'none';
    
    // Jika current photo dihapus sebelumnya, tampilkan kembali pesan no photo
    if (removeCurrentPhotoInput.value === '1') {
        noPhotoMessage.style.display = 'block';
    }
});

// Form Validation
const form = document.getElementById('wasteForm');
const submitBtn = document.getElementById('submitBtn');
const confirmModal = document.getElementById('confirmModal');

function validateField(field) {
    const errorMsg = field.parentElement.querySelector('.error-message');
    
    if (field.type === 'select-one') {
        if (!field.value || field.value === '') {
            field.classList.add('error');
            if (errorMsg) errorMsg.classList.add('show');
            return false;
        }
    } else if (field.type === 'number') {
        if (!field.value || parseInt(field.value) < 1) {
            field.classList.add('error');
            if (errorMsg) errorMsg.classList.add('show');
            return false;
        }
    } else {
        if (!field.value || field.value.trim() === '') {
            field.classList.add('error');
            if (errorMsg) errorMsg.classList.add('show');
            return false;
        }
    }
    
    field.classList.remove('error');
    if (errorMsg) errorMsg.classList.remove('show');
    return true;
}

document.addEventListener('blur', function(e) {
    if (e.target.classList.contains('form-control')) {
        validateField(e.target);
    }
}, true);

document.addEventListener('input', function(e) {
    if (e.target.classList.contains('form-control')) {
        e.target.classList.remove('error');
        const errorMsg = e.target.parentElement.querySelector('.error-message');
        if (errorMsg) errorMsg.classList.remove('show');
    }
}, true);

form.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Reset semua error
    document.querySelectorAll('.form-control').forEach(el => {
        el.classList.remove('error');
    });
    document.querySelectorAll('.error-message').forEach(el => {
        el.classList.remove('show');
    });
    
    let isValid = true;
    let firstError = null;
    
    // Validasi drop point
    const dropPoint = document.getElementById('dropPointSelect');
    if (!validateField(dropPoint)) {
        isValid = false;
        if (!firstError) firstError = dropPoint;
        document.getElementById('dropPointError').classList.add('show');
    }
    
    // Validasi semua field required
    const requiredFields = form.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        if (!validateField(field)) {
            isValid = false;
            if (!firstError) firstError = field;
        }
    });
    
    // Validasi foto (jika current photo dihapus dan tidak ada upload baru)
    if (removeCurrentPhotoInput.value === '1' && !fotoInput.files.length) {
        alert('Please upload a new photo since you removed the current one.');
        isValid = false;
        if (!firstError) firstError = changePhotoBtn;
    }
    
    if (!isValid) {
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        alert('Mohon lengkapi semua field yang wajib diisi (ditandai dengan border merah)');
        return;
    }
    
    // Show confirmation modal
    confirmModal.classList.add('active');
});

document.getElementById('confirmSubmit').addEventListener('click', function() {
    confirmModal.classList.remove('active');
    
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Updating...';
    submitBtn.disabled = true;
    
    form.submit();
});

document.getElementById('cancelSubmit').addEventListener('click', function() {
    confirmModal.classList.remove('active');
});

confirmModal.addEventListener('click', function(e) {
    if (e.target === confirmModal) {
        confirmModal.classList.remove('active');
    }
});
</script>
@endsection