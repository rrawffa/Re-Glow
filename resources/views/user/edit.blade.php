@extends('layouts.app')

@section('title', 'Edit Profile')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
    * { box-sizing: border-box; }
    
    .edit-profile-wrapper {
        background: linear-gradient(135deg, #f5faf8 0%, #f0f8f5 100%);
        min-height: calc(100vh - 100px);
        padding: 40px 20px;
    }
    
    .edit-profile-container {
        max-width: 600px;
        margin: 0 auto;
    }
    
    .edit-profile-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }
    
    .edit-profile-header {
        background: linear-gradient(135deg, #2c5f4f 0%, #1f4438 100%);
        color: #fff;
        padding: 32px 24px;
        text-align: center;
    }
    
    .edit-profile-header h2 {
        font-size: 28px;
        margin: 0 0 8px;
        font-weight: 700;
    }
    
    .edit-profile-header p {
        opacity: 0.9;
        margin: 0;
    }
    
    .edit-profile-body {
        padding: 40px;
    }
    
    .form-group {
        margin-bottom: 28px;
    }
    
    .form-group:last-child {
        margin-bottom: 0;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #1a1a1a;
        font-size: 14px;
    }
    
    .form-input,
    .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s ease;
    }
    
    .form-input:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #2c5f4f;
        box-shadow: 0 0 0 3px rgba(44, 95, 79, 0.1);
    }
    
    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }
    
    /* Avatar Section */
    .avatar-section {
        background: #f9f9f9;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        margin-bottom: 28px;
    }
    
    .avatar-preview-wrapper {
        display: inline-block;
        position: relative;
        margin-bottom: 16px;
    }
    
    .avatar-preview {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .avatar-upload-label {
        display: inline-block;
        padding: 8px 16px;
        background: #2c5f4f;
        color: #fff;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s ease;
        margin-top: 12px;
    }
    
    .avatar-upload-label:hover {
        background: #1f4438;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(44, 95, 79, 0.2);
    }
    
    #avatar {
        display: none;
    }
    
    .avatar-help-text {
        font-size: 12px;
        color: #999;
        margin-top: 8px;
    }
    
    /* Error Messages */
    .error-alert {
        background: #fee;
        border: 1px solid #fcc;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
        color: #c33;
    }
    
    .error-alert ul {
        margin: 0;
        padding-left: 20px;
    }
    
    .error-alert li {
        margin-bottom: 6px;
    }
    
    .error-alert li:last-child {
        margin-bottom: 0;
    }
    
    /* Success Messages */
    .success-alert {
        background: #efe;
        border: 1px solid #cfc;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
        color: #2d8659;
        font-weight: 500;
    }
    
    /* Form Actions */
    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid #e0e0e0;
    }
    
    .btn {
        flex: 1;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #2c5f4f 0%, #1f4438 100%);
        color: #fff;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(44, 95, 79, 0.3);
    }
    
    .btn-secondary {
        background: #f0f0f0;
        color: #1a1a1a;
        border: 1px solid #e0e0e0;
    }
    
    .btn-secondary:hover {
        background: #e8e8e8;
        transform: translateY(-2px);
    }

    /* Section Headers */
    .section-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f0f0f0;
        color: #2c5f4f;
        font-weight: 600;
        font-size: 16px;
    }

    .section-header i {
        font-size: 18px;
    }

    /* Password Toggle */
    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #666;
        cursor: pointer;
    }

    .input-group {
        position: relative;
    }

    /* Responsive */
    @media (max-width: 600px) {
        .edit-profile-body {
            padding: 24px;
        }
        
        .edit-profile-header {
            padding: 24px 16px;
        }
        
        .edit-profile-header h2 {
            font-size: 24px;
        }
        
        .form-actions {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="edit-profile-wrapper">
    <div class="edit-profile-container">
        <div class="edit-profile-card">
            <div class="edit-profile-header">
                <h2><i class="bi bi-pencil-square"></i> Edit Profile</h2>
                <p>Update your profile information</p>
            </div>
            
            <div class="edit-profile-body">
                {{-- Success Message --}}
                @if (session('success'))
                    <div class="success-alert">
                        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                    </div>
                @endif

                {{-- Error Messages --}}
                @if ($errors->any())
                    <div class="error-alert">
                        <strong><i class="bi bi-exclamation-triangle-fill"></i> Terjadi kesalahan:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('error'))
                    <div class="error-alert">
                        <strong><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</strong>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data" id="profileForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information Section -->
                    <div class="section-header">
                        <i class="bi bi-person"></i>
                        <span>Basic Information</span>
                    </div>
                    
                    <!-- Username -->
                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <input 
                            type="text"
                            id="username"
                            name="username" 
                            class="form-input"
                            value="{{ old('username', $user->username) }}"
                            placeholder="Enter your username"
                        >
                        @error('username')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <!-- Contact Information Section -->
                    <div class="section-header">
                        <i class="bi bi-telephone"></i>
                        <span>Contact Information</span>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input 
                            type="email"
                            id="email"
                            name="email" 
                            class="form-input"
                            value="{{ old('email', $user->email) }}"
                            placeholder="Enter your email address"
                        >
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div class="form-group">
                        <label for="no_hp" class="form-label">Phone Number</label>
                        <input 
                            type="tel"
                            id="no_hp"
                            name="no_hp" 
                            class="form-input"
                            value="{{ old('no_hp', $user->no_hp ?? '') }}"
                            placeholder="Enter your phone number"
                        >
                        @error('no_hp')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Security Section -->
                    <div class="section-header">
                        <i class="bi bi-shield-lock"></i>
                        <span>Security</span>
                    </div>

                    <!-- Current Password -->
                    <div class="form-group">
                        <label for="current_password" class="form-label">Current Password</label>
                        <div class="input-group">
                            <input 
                                type="password"
                                id="current_password"
                                name="current_password" 
                                class="form-input"
                                placeholder="Enter current password to confirm changes"
                            >
                            <button type="button" class="password-toggle" onclick="togglePassword('current_password')">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <small class="text-muted">Required to save changes to email or phone number</small>
                        @error('current_password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Save Changes
                        </button>
                        <a href="{{ route('user.profile.show') }}" class="btn btn-secondary">
                            <i class="bi bi-x-lg"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function(){
        const fileInput = document.getElementById('avatar');
        const preview = document.getElementById('avatar-preview');
        const uploadLabel = document.querySelector('.avatar-upload-label');

        if(uploadLabel && fileInput && preview){
            uploadLabel.addEventListener('click', function(e) {
                e.preventDefault();
                fileInput.click();
            });

            fileInput.addEventListener('change', function(){
                const f = this.files && this.files[0];
                if(!f) return;

                if(!f.type || !f.type.match('image/(jpeg|jpg|png)')) {
                    alert('Silakan pilih file gambar (JPG, PNG)');
                    this.value = '';
                    return;
                }

                if(f.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e){
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(f);
            });
        }

        // PERBAIKAN: Form validation yang lebih sederhana
        const form = document.getElementById('profileForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const email = document.getElementById('email').value.trim();
                const currentPassword = document.getElementById('current_password').value.trim();
                
                // Email validation
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (email && !emailRegex.test(email)) {
                    e.preventDefault();
                    alert('Format email tidak valid');
                    return false;
                }

                // Biarkan server-side validation menangani sisanya
            });
        }
    });

    // Password toggle function
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const toggle = input.nextElementSibling;
        const icon = toggle.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
@endsection