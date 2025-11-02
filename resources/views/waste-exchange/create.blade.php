@extends('layouts.app')

@section('title', 'Register Your Empties - Re-Glow')

@section('styles')
<style>
    .create-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 0 1.5rem 4rem;
    }

    .page-header {
        margin-bottom: 2rem;
    }

    .page-header h1 {
        font-size: 2rem;
        color: var(--green-dark);
        margin-bottom: 0.5rem;
    }

    .page-header p {
        color: var(--text-gray);
    }

    .form-section {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
    }

    .form-section h3 {
        color: var(--green-dark);
        margin-bottom: 0.5rem;
        font-size: 1.25rem;
    }

    .form-section .subtitle {
        color: var(--text-gray);
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--text-dark);
        font-weight: 500;
    }

    .form-group label .required {
        color: #dc3545;
    }

    .form-control {
        width: 100%;
        padding: 0.875rem;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--green-dark);
    }

    .form-control.error {
        border-color: #dc3545;
        background: #fff5f5;
    }

    .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: none;
    }

    .form-control.error + .error-message {
        display: block;
    }

    .map-placeholder {
        background: #f5f5f5;
        height: 300px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        border: 2px dashed #ccc;
    }

    .map-placeholder-content {
        text-align: center;
        color: #999;
    }

    .map-placeholder-content span {
        font-size: 3rem;
        display: block;
        margin-bottom: 0.5rem;
    }

    .product-item {
        background: #f9f9f9;
        padding: 1.5rem;
        border-radius: 15px;
        margin-bottom: 1rem;
        border: 2px solid #e0e0e0;
    }

    .product-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .product-header h4 {
        color: var(--green-dark);
        font-size: 1.125rem;
    }

    .btn-remove-product {
        background: #dc3545;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.875rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .btn-add-product {
        background: white;
        border: 2px dashed var(--pink-base);
        color: var(--pink-base);
        padding: 0.875rem 1.5rem;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        width: auto;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .upload-area {
        border: 2px dashed #ccc;
        border-radius: 15px;
        padding: 3rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
    }

    .upload-area:hover {
        border-color: var(--green-dark);
        background: #f9f9f9;
    }

    .upload-area.dragover {
        border-color: var(--green-dark);
        background: var(--pink-light);
    }

    .upload-icon {
        font-size: 3rem;
        color: #999;
        margin-bottom: 1rem;
    }

    .upload-text {
        color: var(--text-gray);
    }

    .upload-text strong {
        color: var(--green-dark);
    }

    .photo-guidelines {
        background: #e3f2fd;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        margin-top: 1rem;
    }

    .photo-guidelines ul {
        margin: 0.5rem 0 0 1.25rem;
        color: #1976d2;
    }

    .photo-guidelines li {
        margin-bottom: 0.25rem;
        font-size: 0.9rem;
    }

    .preview-image {
        max-width: 100%;
        border-radius: 10px;
        margin-top: 1rem;
        display: none;
    }

    .btn-submit {
        background: var(--green-dark);
        color: white;
        padding: 1rem 3rem;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1.125rem;
        cursor: pointer;
        width: 100%;
        transition: all 0.3s;
    }

    .btn-submit:hover:not(:disabled) {
        background: #163026;
        transform: translateY(-2px);
    }

    .btn-submit:disabled {
        background: #ccc;
        cursor: not-allowed;
    }

    .note-text {
        text-align: center;
        color: var(--text-gray);
        font-size: 0.9rem;
        margin-top: 1rem;
    }

    /* Modal Confirmation */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        max-width: 500px;
        width: 90%;
        text-align: center;
    }

    .modal-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
    }

    .modal-content h3 {
        color: var(--green-dark);
        margin-bottom: 1rem;
    }

    .modal-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn-cancel {
        flex: 1;
        background: #f5f5f5;
        color: var(--text-dark);
        padding: 1rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-confirm {
        flex: 1;
        background: var(--green-dark);
        color: white;
        padding: 1rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
    }

    /* Camera Modal */
    .camera-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.95);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        flex-direction: column;
        gap: 1rem;
    }

    .camera-modal.active {
        display: flex;
    }

    #cameraPreview {
        max-width: 90%;
        max-height: 70vh;
        border-radius: 10px;
    }

    .camera-controls {
        display: flex;
        gap: 1rem;
    }

    .btn-camera {
        background: white;
        color: var(--green-dark);
        padding: 1rem 2rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
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

        <button type="button" id="submitBtn" class="btn-submit">
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

// Form Validation & Submit
const form = document.getElementById('wasteForm');
const submitBtn = document.getElementById('submitBtn');
const confirmModal = document.getElementById('confirmModal');

submitBtn.addEventListener('click', function(e) {
    e.preventDefault();
    
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
    
    if (!isValid) {
        alert('Please fill in all required fields (marked with red border)');
        return;
    }
    
    // Show confirmation modal
    confirmModal.classList.add('active');
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