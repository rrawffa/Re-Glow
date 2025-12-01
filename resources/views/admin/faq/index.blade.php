@extends('layouts.app')

@section('title', 'FAQ Management - Re-Glow')

@vite(['resources/css/admin/faq/faq.css'])

@section('content')
<div class="faq-admin-container">
    <div class="admin-header">
        <h2>FAQ Management</h2>
        <a href="{{ route('admin.faq.create') }}" class="btn-add">+ Add New FAQ</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success fade-alert">
            <strong>✓ Success!</strong> {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="35%">Question</th>
                    <th width="45%">Answer Preview</th>
                    <th width="15%" style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($faqs as $faq)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $faq->question }}</td>
                    <td>{{ Str::limit($faq->answer, 80) }}</td>
                    <td style="text-align: center;">
                        <a href="{{ route('admin.faq.edit', $faq->id) }}" class="btn-edit">Edit</a>
                        <form action="{{ route('admin.faq.destroy', $faq->id) }}"
                              method="POST"
                              style="display: inline;"
                              onsubmit="return confirm('Are you sure you want to delete this FAQ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 3rem;">
                        <div>
                            <div style="font-size: 3rem; margin-bottom: 1rem;">❓</div>
                            <h3>No FAQs Added Yet</h3>
                            <p>Create your first FAQ to help users understand Re-Glow better.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
