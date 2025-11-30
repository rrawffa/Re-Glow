@extends('layouts.app')

@section('title', 'Register Your Empties - Re-Glow')

@section('styles')
    @vite(['resources/css/waste-exchange/create.css'])
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""/>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    
    <style>
        /* CSS styles untuk halaman create */
        .create-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .form-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
        }
        
        .required {
            color: #d32f2f;
        }
        
        /* Tambahkan styles lainnya di sini */
    </style>
@endsection

@section('content')
<div class="create-container">
    <a href="{{ route('waste-exchange.index') }}" style="color: var(--text-gray); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
        <i class="bi bi-arrow-left-circle"></i> Back to Waste Exchange
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
                                {{ old('id_drop_point') == $point->id_drop_point ? 'selected' : '' }}>
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
                                <option value="Plastic Bottle" {{ old('products.0.packaging_category') == 'Plastic Bottle' ? 'selected' : '' }}>Plastic Bottle</option>
                                <option value="Glass Jar" {{ old('products.0.packaging_category') == 'Glass Jar' ? 'selected' : '' }}>Glass Jar</option>
                                <option value="Metal Tube" {{ old('products.0.packaging_category') == 'Metal Tube' ? 'selected' : '' }}>Metal Tube</option>
                                <option value="Compact Case" {{ old('products.0.packaging_category') == 'Compact Case' ? 'selected' : '' }}>Compact Case</option>
                            </select>
                            <div class="error-message">Packaging category harus dipilih</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Size Category <span class="required">*</span></label>
                            <select name="products[0][size_category]" class="form-control" required>
                                <option value="">Select size...</option>
                                <option value="Large" {{ old('products.0.size_category') == 'Large' ? 'selected' : '' }}>"Large" - >100ml/large palette</option>
                                <option value="Medium" {{ old('products.0.size_category') == 'Medium' ? 'selected' : '' }}>"Medium" - 50-100ml/standard jar</option>
                                <option value="Small" {{ old('products.0.size_category') == 'Small' ? 'selected' : '' }}>"Small" - <50ml/lipstick/eyeshadow single</option>
                            </select>
                            <div class="error-message">Size category harus dipilih</div>
                        </div>

                        <div class="form-group">
                            <label>Quantity <span class="required">*</span></label>
                            <input type="number" 
                                   name="products[0][quantity]" 
                                   class="form-control" 
                                   min="1" 
                                   value="{{ old('products.0.quantity', 1) }}"
                                   required>
                            <div class="error-message">Quantity harus diisi (minimal 1)</div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" id="addProduct" class="btn-add-product">
                <i class="bi bi-plus-circle"></i> Add New Product
            </button>
        </div>

        <!-- Step 3: Proof of Waste -->
        <div class="form-section">
            <h3>Step 3: Proof of Waste</h3>
            <p class="subtitle">Upload a clear photo of all your cosmetic empties grouped together</p>

            <div class="form-group">
                <label>Upload Photo Proof of Your Cosmetic Empties <span class="required">*</span></label>
                
                <div class="upload-area" id="uploadArea">
                    <div class="upload-icon">
                        <i class="bi bi-camera"></i>
                    </div>
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
                        <!-- TOMBOL X UNTUK HAPUS GAMBAR -->
                        <button type="button" id="removeImage" class="btn-remove-image" title="Remove photo">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        <img id="previewImage" class="preview-image" alt="Preview">
                    </div>
                </div>

                <div class="error-message" id="fotoError">Foto bukti harus diupload</div>
                @error('foto_bukti')
                <div class="error-message show">{{ $message }}</div>
                @enderror

                <!-- TOMBOL CAMERA DENGAN ICON -->
                <button type="button" id="openCamera" class="btn-add-product" style="width: 100%; justify-content: center; margin-top: 1rem;">
                    <i class="bi bi-camera-fill"></i> Open Camera
                </button>

                <div class="photo-guidelines">
                    <strong style="color: #1976d2;">
                        <i class="bi bi-info-circle"></i> Photo Guidelines:
                    </strong>
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
            <i class="bi bi-check-lg"></i> Submit Waste Exchange
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
        <h3>Confirm Your Submission</h3>
        <p>Are you sure all the information is correct? Once submitted, you can only edit if the status is still "Submitted".</p>
        
        <div class="modal-buttons">
            <button type="button" class="btn-cancel" id="cancelSubmit">
                <i class="bi bi-arrow-left"></i> Review Again
            </button>
            <button type="button" class="btn-confirm" id="confirmSubmit">
                <i class="bi bi-check-lg"></i> Yes, Submit
            </button>
        </div>
    </div>
</div>

<!-- Camera Modal -->
<div class="camera-modal" id="cameraModal">
    <video id="cameraPreview" autoplay playsinline></video>
    <canvas id="cameraCanvas" style="display: none;"></canvas>
    <div class="camera-controls">
        <button type="button" class="btn-camera" id="captureBtn">
            <i class="bi bi-camera-fill"></i> Capture
        </button>
        <button type="button" class="btn-camera" id="closeCameraBtn">
            <i class="bi bi-x-lg"></i> Close Camera
        </button>
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

    // Check if ada selected drop point dari old input
    const oldValue = "{{ old('id_drop_point') }}";
    if (oldValue) {
        const selectedOption = document.querySelector(`#dropPointSelect option[value="${oldValue}"]`);
        if (selectedOption) {
            updateMapMarker(selectedOption);
        }
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
    // Validasi ukuran file (max 10MB)
    if (file.size > 10 * 1024 * 1024) {
        alert('File size must be less than 10MB');
        return;
    }
    
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
        console.error('Camera error:', err);
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
    
    // Reset semua error
    document.querySelectorAll('.form-control').forEach(el => {
        el.classList.remove('error');
    });
    document.querySelectorAll('.error-message').forEach(el => {
        el.classList.remove('show');
    });
    uploadArea.classList.remove('error');
    
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
        if (field.id !== 'fotoInput') {
            if (!validateField(field)) {
                isValid = false;
                if (!firstError) firstError = field;
            }
        }
    });
    
    // Validasi file upload
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
    
    // Show confirmation modal
    confirmModal.classList.add('active');
});

document.getElementById('confirmSubmit').addEventListener('click', function() {
    confirmModal.classList.remove('active');
    
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Submitting...';
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

// Handle page refresh - maintain form state
window.addEventListener('beforeunload', function() {
    if (cameraStream) {
        stopCamera();
    }
});
</script>
@endsection