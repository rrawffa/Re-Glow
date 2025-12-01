@extends('layouts.app')

@section('title', 'Edit FAQ - Re-Glow')

@vite(['resources/css/admin/faq/faq.css'])

@push('styles')
<style>
    :root {
        --reglow-pink: #d85f8c;
        --reglow-green-dark: #20413a;
    }

    main {
        background: #f8f9fa;
        min-height: calc(100vh - 120px);
        padding: 3rem 0;
    }

    .form-container {
        max-width: 700px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .form-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .form-header h2 {
        font-family: "Bricolage Grotesque", sans-serif;
        font-weight: 700;
        color: var(--reglow-green-dark);
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .form-header p {
        color: #666666;
    }

    .form-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    label {
        display: block;
        font-weight: 600;
        color: #2D2D2D;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    input[type="text"],
    textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #e8e8e8;
        border-radius: 8px;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    input[type="text"]:focus,
    textarea:focus {
        outline: none;
        border-color: var(--reglow-pink);
        box-shadow: 0 0 0 3px rgba(216, 95, 140, 0.1);
    }

    textarea {
        resize: vertical;
        min-height: 150px;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: space-between;
        margin-top: 2rem;
    }

    .btn-back {
        background: #e9ecef;
        color: #2D2D2D;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .btn-back:hover {
        background: #dee2e6;
        transform: translateY(-2px);
    }

    .btn-submit {
        background: var(--reglow-pink);
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        background: #bf4f7a;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(216, 95, 140, 0.3);
    }

    small {
        display: block;
        margin-top: 0.5rem;
        color: #dc3545;
    }

    @media (max-width: 768px) {
        .form-container {
            padding: 0 1rem;
        }

        .form-card {
            padding: 1.5rem;
        }

        .form-header h2 {
            font-size: 1.5rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-back, .btn-submit {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<main>
    <div class="form-container">
        <div class="form-header">
            <h2>Edit FAQ</h2>
            <p>Update the FAQ content to keep information current</p>
        </div>

        <div class="form-card">
            <form action="{{ route('admin.faq.update', $faq->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="question">Question</label>
                    <input type="text" id="question" name="question" value="{{ old('question', $faq->question ?? '') }}" placeholder="Enter the FAQ question" required>
                    @error('question')
                        <small>{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="answer">Answer</label>
                    <textarea id="answer" name="answer" placeholder="Enter the detailed answer" required>{{ old('answer', $faq->answer ?? '') }}</textarea>
                    @error('answer')
                        <small>{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.faq.index') }}" class="btn-back">Back</a>
                    <button type="submit" class="btn-submit">Update FAQ</button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
