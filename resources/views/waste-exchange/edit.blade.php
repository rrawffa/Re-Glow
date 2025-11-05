@extends('layouts.app')

@section('title', 'Edit Your Transaction - Re-Glow')

@section('styles')
    @vite(['resources/css/waste-exchange/edit.css'])
@endsection

@section('content')
<div class="create-container">
    <a href="{{ route('waste-exchange.history') }}" style="color: var(--text-gray); text-decoration: none; display: inline-block; margin-bottom: 1rem;">
        ← Back
    </a>

    <div class="page-header">
        <h1>Edit Your Transaction here</h1>
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
                
                <div class="map-placeholder">
                    <div style="text-align:center;color:#999;">
                        <span style="font-size:3rem;display:block;margin-bottom:0.5rem;">📍</span>
                        <strong>Interactive Map</strong>
                        <p>Drop points marked with pins</p>
                    </div>
                </div>

                <select name="id_drop_point" class="form-control @error('id_drop_point') error @enderror" required>
                    <option value="">Choose a location...</option>
                    @foreach($dropPoints as $point)
                    <option value="{{ $point->id_drop_point }}" 
                        {{ $transaksi->id_drop_point == $point->id_drop_point ? 'selected' : '' }}>
                        {{ $point->nama_lokasi }} - {{ $point->alamat }}
                    </option>
                    @endforeach
                </select>
                @error('id_drop_point')
                <div style="color:#dc3545;font-size:0.875rem;margin-top:0.5rem;">{{ $message }}</div>
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
                        <button type="button" class="btn-remove-product" onclick="removeProduct(this)">Remove</button>
                        @endif
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Product Name/Type <span class="required">*</span></label>
                            <input type="text" 
                                   name="products[{{ $index }}][jenis_sampah]" 
                                   class="form-control"
                                   placeholder="e.g. Lipstick Tube"
                                   value="{{ $detail->jenis_sampah }}"
                                   required>
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
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Size Category <span class="required">*</span></label>
                            <select name="products[{{ $index }}][ukuran_sampah]" class="form-control" required>
                                <option value="">Select size...</option>
                                <option value="Large" {{ $detail->ukuran_sampah == 'Large' ? 'selected' : '' }}>"Large" - >100ml/large palette</option>
                                <option value="Medium" {{ $detail->ukuran_sampah == 'Medium' ? 'selected' : '' }}>"Medium" - 50-100ml/standard jar</option>
                                <option value="Small" {{ $detail->ukuran_sampah == 'Small' ? 'selected' : '' }}>"Small" - <50ml/lipstick/eyeshadow single</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Quantity <span class="required">*</span></label>
                            <input type="number" 
                                   name="products[{{ $index }}][quantity]" 
                                   class="form-control" 
                                   min="1" 
                                   value="{{ $detail->quantity }}"
                                   required>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="button" id="addProduct" class="btn-add-product">
                + Add New Product
            </button>
        </div>

        <!-- Step 3: Proof of Waste (Optional for Edit) -->
        <div class="form-section">
            <h3>Step 3: Proof of Waste</h3>
            <p class="subtitle">Upload a clear photo of all your cosmetic empties grouped together</p>

            <div class="form-group">
                <label>Upload Photo Proof of Your Cosmetic Empties (Optional)</label>
                
                @if($transaksi->foto_bukti)
                <div style="margin-bottom:1rem;">
                    <p style="color:var(--text-gray);font-size:0.9rem;">Current photo:</p>
                    <img src="{{ asset($transaksi->foto_bukti) }}" alt="Current proof" style="max-width:300px;border-radius:10px;">
                </div>
                @endif

                <div class="upload-area" id="uploadArea" style="padding:2rem;">
                    <div style="font-size:2rem;margin-bottom:1rem;">📷</div>
                    <div style="color:var(--text-gray);">
                        <strong style="color:var(--green-dark);">Click to upload new photo</strong><br>
                        <small>PNG, JPG up to 10MB (Leave empty to keep current photo)</small>
                    </div>
                    <input type="file" 
                           name="foto_bukti" 
                           id="fotoInput" 
                           accept="image/*"
                           style="display: none;">
                    <img id="previewImage" class="preview-image" alt="Preview" style="display:none;">
                </div>

                <div style="background:#e3f2fd;padding:1rem 1.5rem;border-radius:10px;margin-top:1rem;">
                    <strong style="color:#1976d2;">📋 Photo Guidelines:</strong>
                    <ul style="margin:0.5rem 0 0 1.25rem;color:#1976d2;">
                        <li style="margin-bottom:0.25rem;font-size:0.9rem;">Group all items together in one photo</li>
                        <li style="margin-bottom:0.25rem;font-size:0.9rem;">Ensure clear visibility of packaging types</li>
                        <li style="margin-bottom:0.25rem;font-size:0.9rem;">Use good lighting for accurate assessment</li>
                        <li style="font-size:0.9rem;">Include all items mentioned in your form</li>
                    </ul>
                </div>
            </div>
        </div>

        <button type="button" id="submitBtn" class="btn-submit">
            Submit Waste Exchange
        </button>
        <p style="text-align:center;color:var(--text-gray);font-size:0.9rem;margin-top:1rem;">Please complete all required fields to submit</p>
    </form>
</div>

<!-- Confirmation Modal -->
<div class="modal-overlay" id="confirmModal">
    <div style="background:white;padding:2rem;border-radius:20px;max-width:500px;width:90%;text-align:center;">
        <div style="font-size:4rem;margin-bottom:1rem;">✅</div>
        <h3 style="color:var(--green-dark);margin-bottom:1rem;">Confirm Your Changes</h3>
        <p style="color:var(--text-gray);margin-bottom:2rem;">Are you sure all the information is correct?</p>
        
        <div style="display:flex;gap:1rem;">
            <button type="button" id="cancelSubmit" style="flex:1;background:#f5f5f5;color:var(--text-dark);padding:1rem;border:none;border-radius:10px;font-weight:600;cursor:pointer;">
                Review Again
            </button>
            <button type="button" id="confirmSubmit" style="flex:1;background:var(--green-dark);color:white;padding:1rem;border:none;border-radius:10px;font-weight:600;cursor:pointer;">
                Yes, Submit
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let productIndex = {{ count($transaksi->details) }};

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
                <input type="text" name="products[${productIndex}][jenis_sampah]" class="form-control" placeholder="e.g. Lipstick Tube" required>
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
                <select name="products[${productIndex}][ukuran_sampah]" class="form-control" required>
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

fotoInput.addEventListener('change', (e) => {
    if (e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImage.src = e.target.result;
            previewImage.style.display = 'block';
        };
        reader.readAsDataURL(e.target.files[0]);
    }
});

// Form Submission
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
    
    confirmModal.classList.add('active');
});

document.getElementById('cancelSubmit').addEventListener('click', function() {
    confirmModal.classList.remove('active');
});

document.getElementById('confirmSubmit').addEventListener('click', function() {
    form.submit();
});

confirmModal.addEventListener('click', function(e) {
    if (e.target === confirmModal) {
        confirmModal.classList.remove('active');
    }
});
</script>
@endsection