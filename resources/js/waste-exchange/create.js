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