@extends('layouts.app')

@section('title', 'Register Your Empties - Re-Glow')

@section('styles')
    @vite(['resources/css/waste-exchange/create.css'])
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
                    <div class="map-placeholder-content">
                        <span>📍</span>
                        <strong>Interactive Map</strong>
                        <p>Drop points marked with pins</p>
                    </div>
                </div>

                <select name="id_drop_point" class="form-control @error('id_drop_point') error @enderror" required>
                    <option value="">Choose a location...</option>
                    @foreach($dropPoints as $point)
                    <option value="{{ $point->id_drop_point }}" {{ old('id_drop_point') == $point->id_drop_point ? 'selected' : '' }}>
                        {{ $point->nama_lokasi }} - {{ $point->alamat }}
                    </option>
                    @endforeach
                </select>
                @error('id_drop_point')
                <div class="error-message">{{ $message }}</div>
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
                        </div>

                        <div class="form-group">
                            <label>Quantity <span class="required">*</span></label>
                            <input type="number" 
                                   name="products[0][quantity]" 
                                   class="form-control" 
                                   min="1" 
                                   value="1"
                                   required>
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
                    <img id="previewImage" class="preview-image" alt="Preview">
                </div>

                <button type="button" id="openCamera" class="btn-add-product" style="width: 100%; justify-content: center;">
                    📸 Open Camera
                </button>

                @error('foto_bukti')
                <div class="error-message" style="display: block;">{{ $message }}</div>
                @enderror

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
<script>
let productIndex = 1;
let cameraStream = null;

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
            </div>
            <div class="form-group">
                <label>Quantity <span class="required">*</span></label>
                <input type="number" name="products[${productIndex}][quantity]" class="form-control" min="1" value="1" required>
            </div>
        </div>
    `;
    
    container.appendChild(newProduct);
    productIndex++;
});

function removeProduct(btn) {
    btn.closest('.product-item').remove();
}

// File Upload
const uploadArea = document.getElementById('uploadArea');
const fotoInput = document.getElementById('fotoInput');
const previewImage = document.getElementById('previewImage');

uploadArea.addEventListener('click', () => fotoInput.click());

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
    };
    reader.readAsDataURL(file);
}

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

// Form Validation & Submit - SOLUSI YANG BENAR
const form = document.getElementById('wasteForm');
const submitBtn = document.getElementById('submitBtn');
const confirmModal = document.getElementById('confirmModal');

// Hapus event listener click yang lama
// submitBtn.addEventListener('click', function(e) { ... }); // HAPUS BARIS INI

// Gunakan submit event pada form
form.addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent default dulu untuk validasi
    
    // Clear previous errors
    document.querySelectorAll('.form-control').forEach(el => el.classList.remove('error'));
    
    // Validate form
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        if (!field.value || field.value.trim() === '') {
            field.classList.add('error');
            isValid = false;
        }
    });
    
    // Validasi file upload
    const fileInput = document.getElementById('fotoInput');
    if (!fileInput.files.length) {
        document.getElementById('uploadArea').style.borderColor = '#dc3545';
        isValid = false;
    }
    
    if (!isValid) {
        alert('Please fill in all required fields (marked with red border)');
        return;
    }
    
    // Show confirmation modal
    confirmModal.classList.add('active');
});

// Konfirmasi submit
document.getElementById('confirmSubmit').addEventListener('click', function() {
    // Sembunyikan modal
    confirmModal.classList.remove('active');
    
    // Tampilkan loading state
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Submitting...';
    submitBtn.disabled = true;
    
    // HAPUS event listener submit untuk mencegah loop
    form.removeEventListener('submit', arguments.callee);
    
    // Submit form secara normal
    form.submit();
});

document.getElementById('cancelSubmit').addEventListener('click', function() {
    confirmModal.classList.remove('active');
});

document.getElementById('confirmSubmit').addEventListener('click', function() {
    form.submit();
});

// Close modal on outside click
confirmModal.addEventListener('click', function(e) {
    if (e.target === confirmModal) {
        confirmModal.classList.remove('active');
    }
});
</script>
@endsection