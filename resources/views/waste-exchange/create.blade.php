@extends('layouts.app')

@section('title', 'Register Your Empties - Re-Glow')

@section('styles')
    @vite(['resources/css/waste-exchange/create.css'])
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""/>
    
    <style>
        #map {
            width: 100%;
            height: 300px;
            border-radius: 15px;
            z-index: 1;
        }
        
        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            font-family: 'DM Sans', sans-serif;
        }
        
        .leaflet-popup-content {
            margin: 15px;
        }
        
        .map-popup h4 {
            margin: 0 0 8px 0;
            color: var(--green-dark);
            font-size: 1rem;
            font-weight: 600;
        }
        
        .map-popup p {
            margin: 0 0 5px 0;
            font-size: 0.875rem;
            color: var(--text-gray);
            line-height: 1.4;
        }
        
        .map-popup .badge {
            display: inline-block;
            background: var(--pink-base);
            color: white;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 5px;
        }
        
        .selected-marker {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--green-dark);
            border: 4px solid var(--pink-base);
            border-radius: 50%;
            font-size: 24px;
            box-shadow: 0 4px 15px rgba(249, 182, 199, 0.6);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { 
                transform: scale(1);
                box-shadow: 0 4px 15px rgba(249, 182, 199, 0.6);
            }
            50% { 
                transform: scale(1.1);
                box-shadow: 0 6px 20px rgba(249, 182, 199, 0.8);
            }
        }
    </style>
@endsection

@section('content')
<div class="create-container">
    <a href="{{ route('waste-exchange.index') }}" style="color: var(--text-gray); text-decoration: none; display: inline-block; margin-bottom: 1rem;">
        ← Back
    </a>

    <div class="page-header">
        <h1>It's Time to Swap! Register Your Empties.</h1>
        <p>All fields are mandatory to ensure accurate point allocation and smooth logistics.</p>
    </div>

    <form id="wasteForm" method="POST" action="{{ route('waste-exchange.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Step 1: Drop Point Selection -->
        <div class="form-section">
            <h3>Step 1: Drop Point Selection</h3>
            <p class="subtitle">Select where you'll drop off your cosmetic waste</p>

            <div class="form-group">
                <label>Select Your Drop Point Location <span class="required">*</span></label>
                
                <div class="map-placeholder">
                    <div id="map"></div>
                </div>

                <select name="id_drop_point" id="dropPointSelect" class="form-control @error('id_drop_point') error @enderror" required>
                    <option value="">Choose a location...</option>
                    @foreach($dropPoints as $point)
                    <option value="{{ $point->id_drop_point }}" 
                            data-lat="{{ $point->latitude }}" 
                            data-lng="{{ $point->longitude }}"
                            data-nama="{{ $point->nama_lokasi }}"
                            data-alamat="{{ $point->alamat }}"
                            {{ old('id_drop_point') == $point->id_drop_point ? 'selected' : '' }}>
                        {{ $point->nama_lokasi }} - {{ $point->alamat }}
                    </option>
                    @endforeach
                </select>
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
                <div class="product-item" data-product-index="0">
                    <div class="product-header">
                        <h4>Product #1</h4>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Product Name/Type <span class="required">*</span></label>
                            <input type="text" 
                                   name="products[0][nama_produk]" 
                                   class="form-control"
                                   placeholder="e.g. Lipstick Tube, Face Wash Bottle"
                                   value="{{ old('products.0.nama_produk') }}"
                                   required>
                            <div class="error-message">Nama produk harus diisi</div>
                        </div>

                        <div class="form-group">
                            <label>Packaging Category <span class="required">*</span></label>
                            <select name="products[0][packaging_category]" class="form-control" required>
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
                            <select name="products[0][size_category]" class="form-control" required>
                                <option value="">Select size...</option>
                                <option value="Large">"Large" - >100ml/large palette</option>
                                <option value="Medium">"Medium" - 50-100ml/standard jar</option>
                                <option value="Small">"Small" - <50ml/lipstick/eyeshadow single</option>
                            </select>
                            <div class="error-message">Size category harus dipilih</div>
                        </div>

                        <div class="form-group">
                            <label>Quantity <span class="required">*</span></label>
                            <input type="number" 
                                   name="products[0][quantity]" 
                                   class="form-control" 
                                   min="1" 
                                   value="1"
                                   required>
                            <div class="error-message">Quantity harus diisi (minimal 1)</div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" id="addProduct" class="btn-add-product">
                + Add New Product
            </button>
        </div>

        <!-- Step 3: Proof of Waste -->
        <div class="form-section">
            <h3>Step 3: Proof of Waste</h3>
            <p class="subtitle">Upload a clear photo of all your cosmetic empties grouped together</p>

            <div class="form-group">
                <label>Upload Photo Proof of Your Cosmetic Empties <span class="required">*</span></label>
                
                <div class="upload-area" id="uploadArea">
                    <div class="upload-icon">📷</div>
                    <div class="upload-text">
                        <strong>Click to upload photo</strong><br>
                        or drag and drop your image here<br>
                        <small>PNG, JPG up to 10MB</small>
                    </div>
                    <input type="file" 
                           name="foto_bukti" 
                           id="fotoInput" 
                           accept="image/*"
                           style="display: none;"
                           required>
                    
                    <div class="image-preview-container" id="imagePreviewContainer">
                        <button type="button" id="removeImage" class="btn-remove-image" title="Remove photo">
                            ✕
                        </button>
                        <img id="previewImage" class="preview-image" alt="Preview">
                    </div>
                </div>

                <div class="error-message" id="fotoError">Foto bukti harus diupload</div>
                @error('foto_bukti')
                <div class="error-message show">{{ $message }}</div>
                @enderror

                <button type="button" id="openCamera" class="btn-add-product" style="width: 100%; justify-content: center; margin-top: 1rem;">
                    📸 Open Camera
                </button>

                <div class="photo-guidelines">
                    <strong style="color: #1976d2;">📋 Photo Guidelines:</strong>
                    <ul>
                        <li>Group all items together in one photo</li>
                        <li>Ensure clear visibility of packaging types</li>
                        <li>Use good lighting for accurate assessment</li>
                        <li>Include all items mentioned in your form</li>
                    </ul>
                </div>
            </div>
        </div>

        <button type="submit" id="submitBtn" class="btn-submit">
            Submit Waste Exchange
        </button>
        <p class="note-text">Please complete all required fields to submit</p>
    </form>
</div>

<!-- Confirmation Modal -->
<div class="modal-overlay" id="confirmModal">
    <div class="modal-content">
        <div class="modal-icon">✅</div>
        <h3>Confirm Your Submission</h3>
        <p>Are you sure all the information is correct? Once submitted, you can only edit if the status is still "Submitted".</p>
        
        <div class="modal-buttons">
            <button type="button" class="btn-cancel" id="cancelSubmit">Review Again</button>
            <button type="button" class="btn-confirm" id="confirmSubmit">Yes, Submit</button>
        </div>
    </div>
</div>

<!-- Camera Modal -->
<div class="camera-modal" id="cameraModal">
    <video id="cameraPreview" autoplay playsinline></video>
    <canvas id="cameraCanvas" style="display: none;"></canvas>
    <div class="camera-controls">
        <button type="button" class="btn-camera" id="captureBtn">📸 Capture</button>
        <button type="button" class="btn-camera" id="closeCameraBtn">✕ Close</button>
    </div>
</div>
@endsection

@section('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>

<script>
let productIndex = 1;
let cameraStream = null;
let map;
let marker = null;
let selectedMarkerId = null; // ID dari drop point yang sedang dipilih
let allMarkers = [];

// Initialize Map
function initMap() {
    // Center di Jawa Timur (Surabaya)
    const centerJawaTimur = [-7.2575, 112.7521];

    // Icon Kustom untuk marker default dan terpilih
    const defaultIcon = L.divIcon({
        className: 'custom-marker', // Sesuaikan dengan style .custom-marker Anda
        html: '📍', 
        iconSize: [40, 40],
        iconAnchor: [20, 40],
        popupAnchor: [0, -40]
    });

    // Fungsi Helper: Tambah Marker
    function addMarker(lat, lng, nama, alamat, id_drop_point) {
        const popupContent = `
            <div class="map-popup">
                <h4>${nama}</h4>
                <p>${alamat}</p>
                <span class="badge">📍 Klik untuk memilih lokasi ini</span>
            </div>
        `;
        
        const marker = L.marker([lat, lng], {
            icon: defaultIcon, // Default icon
            id_drop_point: id_drop_point // Simpan ID Drop Point
        }).addTo(map);

        marker.bindPopup(popupContent, { maxWidth: 250 });
        
        // Event klik pada marker
        marker.on('click', function() {
            // Set nilai dropdown dan trigger event 'change'
            document.getElementById('dropPointSelect').value = id_drop_point;
            document.getElementById('dropPointSelect').dispatchEvent(new Event('change'));
        });
        
        allMarkers.push(marker); // Simpan marker ke array
    }
    
    const selectedIcon = L.divIcon({
        className: 'selected-marker', // Sesuaikan dengan style .selected-marker Anda
        html: '⭐', // Ganti icon terpilih jika perlu
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

    // Tambahkan OpenStreetMap tile layer - GRATIS, TANPA API KEY!
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19,
    }).addTo(map);
    
    // Check if ada selected drop point dari old input
    const oldValue = "{{ old('id_drop_point') }}";
    if (oldValue) {
        const selectedOption = document.querySelector(`#dropPointSelect option[value="${oldValue}"]`);
        if (selectedOption) {
            updateMapMarker(selectedOption);
        }
    }
}

// Update marker ketika dropdown berubah
document.getElementById('dropPointSelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    
    if (selectedOption.value) {
        updateMapMarker(selectedOption);
    } else {
        // Reset map jika tidak ada yang dipilih
        if (marker) {
            map.removeLayer(marker);
            marker = null;
        }
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
                marker.setIcon(selectedIcon); // Set icon terpilih
                selectedMarkerId = id; // Simpan ID terpilih
                marker.openPopup();
                map.setView(marker.getLatLng(), 15, { // Zoom ke marker terpilih
                    animate: true,
                    duration: 0.5
                });
            } else {
                marker.setIcon(defaultIcon); // Reset ke icon default
            }
        });
    }

// Initialize map setelah DOM ready
document.addEventListener('DOMContentLoaded', initMap);

// Add Product
document.getElementById('addProduct').addEventListener('click', function() {
    const container = document.getElementById('productContainer');
    const newProduct = document.createElement('div');
    newProduct.className = 'product-item';
    newProduct.dataset.productIndex = productIndex;
    
    newProduct.innerHTML = `
        <div class="product-header">
            <h4>Product #${productIndex + 1}</h4>
            <button type="button" class="btn-remove-product" onclick="removeProduct(this)">Remove</button>
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
    btn.closest('.product-item').remove();
}

// File Upload Handler
const uploadArea = document.getElementById('uploadArea');
const fotoInput = document.getElementById('fotoInput');
const previewImage = document.getElementById('previewImage');
const imagePreviewContainer = document.getElementById('imagePreviewContainer');
const removeImageBtn = document.getElementById('removeImage');

uploadArea.addEventListener('click', function(e) {
    if (e.target.closest('#removeImage')) return;
    fotoInput.click();
});

uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('dragover');
});

uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('dragover');
});

uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        handleFileSelect(file);
    }
});

fotoInput.addEventListener('change', (e) => {
    if (e.target.files[0]) {
        handleFileSelect(e.target.files[0]);
    }
});

function handleFileSelect(file) {
    const reader = new FileReader();
    reader.onload = (e) => {
        previewImage.src = e.target.result;
        previewImage.style.display = 'block';
        imagePreviewContainer.classList.add('show');
        uploadArea.classList.add('has-image');
        
        uploadArea.classList.remove('error');
        document.getElementById('fotoError').classList.remove('show');
    };
    reader.readAsDataURL(file);
}

removeImageBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    
    fotoInput.value = '';
    
    previewImage.src = '';
    previewImage.style.display = 'none';
    imagePreviewContainer.classList.remove('show');
    uploadArea.classList.remove('has-image');
});

// Camera Functions
document.getElementById('openCamera').addEventListener('click', async function() {
    try {
        cameraStream = await navigator.mediaDevices.getUserMedia({ 
            video: { facingMode: 'environment' } 
        });
        document.getElementById('cameraPreview').srcObject = cameraStream;
        document.getElementById('cameraModal').classList.add('active');
    } catch (err) {
        alert('Cannot access camera: ' + err.message);
    }
});

document.getElementById('closeCameraBtn').addEventListener('click', function() {
    stopCamera();
});

document.getElementById('captureBtn').addEventListener('click', function() {
    const video = document.getElementById('cameraPreview');
    const canvas = document.getElementById('cameraCanvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    
    canvas.toBlob(function(blob) {
        const file = new File([blob], 'camera-capture.jpg', { type: 'image/jpeg' });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fotoInput.files = dataTransfer.files;
        
        handleFileSelect(file);
        stopCamera();
    }, 'image/jpeg', 0.9);
});

function stopCamera() {
    if (cameraStream) {
        cameraStream.getTracks().forEach(track => track.stop());
        cameraStream = null;
    }
    document.getElementById('cameraModal').classList.remove('active');
}

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
    
    document.querySelectorAll('.form-control').forEach(el => {
        el.classList.remove('error');
    });
    document.querySelectorAll('.error-message').forEach(el => {
        el.classList.remove('show');
    });
    
    let isValid = true;
    let firstError = null;
    
    const dropPoint = document.getElementById('dropPointSelect');
    if (!validateField(dropPoint)) {
        isValid = false;
        if (!firstError) firstError = dropPoint;
        document.getElementById('dropPointError').classList.add('show');
    }
    
    const requiredFields = form.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        if (field.id !== 'fotoInput') {
            if (!validateField(field)) {
                isValid = false;
                if (!firstError) firstError = field;
            }
        }
    });
    
    const fileInput = document.getElementById('fotoInput');
    if (!fileInput.files.length) {
        uploadArea.classList.add('error');
        document.getElementById('fotoError').classList.add('show');
        isValid = false;
        if (!firstError) firstError = uploadArea;
    } else {
        uploadArea.classList.remove('error');
        document.getElementById('fotoError').classList.remove('show');
    }
    
    if (!isValid) {
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        alert('Mohon lengkapi semua field yang wajib diisi (ditandai dengan border merah)');
        return;
    }
    
    confirmModal.classList.add('active');
});

document.getElementById('confirmSubmit').addEventListener('click', function() {
    confirmModal.classList.remove('active');
    
    submitBtn.textContent = 'Submitting...';
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