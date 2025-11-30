# Re-Glow Codebase Guidelines for AI Agents

## Project Overview

**Re-Glow** is a Laravel 12 waste management and recycling application with a Vite + Tailwind frontend. The system manages waste transactions, education content, voucher rewards, user points, and role-based dashboards (user/admin/logistik).

### Key Tech Stack

-   **Backend**: Laravel 12, PHP 8.2, Eloquent ORM
-   **Frontend**: Blade templating, Vite, Tailwind CSS 4.0
-   **Database**: Custom table naming (e.g., `pengguna`, `kontenedukasi`, `transaksisampah`)
-   **Testing**: Pest PHP with Pest Plugin for Laravel
-   **Build**: Composer + NPM, custom Artisan scripts

---

## Architecture & Core Components

### Custom Authentication System

Unlike Laravel's default Auth, this project implements **session-based custom authentication**:

-   **Location**: `app/Http/Controllers/AuthController.php`, `app/Http/Middleware/CheckSession.php`
-   **Session Model**: Custom `session` table (not Laravel's default) storing `session_id`, `id_user`, `waktu_expire`, `ip_address`
-   **Session Data**: Stored in PHP Session: `user_id`, `username`, `email`, `user_role`, `session_id`
-   **Validation**: `CheckSession` middleware validates session expiration (24-hour lifespan) and checks database
-   **Convention**: Email must end with `@gmail.com` in login/register forms
-   **Password Hashing**: Auto-hashed in User model `boot()` method (don't hash before saving)

### Role-Based Access Control

-   **Roles**: `pengguna` (user), `admin`, `tim_logistik` (logistics team)
-   **Middleware**: `CheckRole.php` accepts variadic role parameters: `middleware('check.role:admin')` or `middleware('check.role:admin,tim_logistik')`
-   **Dashboard Routing**: Routes auto-redirect to role-specific dashboards (e.g., `/user/dashboard`, `/admin/dashboard`, `/logistik/dashboard`)
-   **Session-Based**: Checks `user_role` from PHP Session, **not** database each time

### Data Models & Custom Table Names

All models use custom primary keys and table names:

-   **User**: Table `pengguna`, PK `id_user` (int), auto-hashes passwords via boot
-   **Education**: Table `kontenedukasi`, PK `id_konten`, has relationships to `media`, `statistik`, `reaksi`
-   **TransaksiSampah**: Table `transaksisampah`, PK `id_tSampah`, tracks waste exchanges with statuses (Menunggu → Diproses → Selesai)
-   **DropPoint**: Waste collection centers, used in transactions
-   **Voucher**: Reward system with stock, redemption tracking
-   **ReaksiKonten**: User reactions (suka, membantu, menarik, inspiratif) to education content

**Important**: Always check model files for custom table/key names—don't assume Laravel defaults.

### Key Relationships

-   **Transactions** → User, DropPoint, Details (via `id_tSampah`), History (`riwayatSampah`)
-   **Education** → Media (ordered by `urutan`), Statistics, Reactions
-   **Users** → Points, Voucher Redemptions, Sessions
-   **Auto-Deletion**: Education model cascades delete to media, reactions, statistics

---

## Critical Workflows

### Development & Local Server

```bash
# Full setup
composer run setup

# Development with live reload (runs 4 processes in parallel)
composer run dev
  # Starts: Artisan server, queue listener, Pail logs, Vite HMR

# Run tests
composer run test

# Clear config before running tests (important!)
php artisan config:clear --ansi
```

### Database Migrations

-   **Path**: `database/migrations/` with custom naming timestamps
-   **Key Tables**: `users` (legacy), `pengguna` (custom), `kontenedukasi`, `transaksisampah`, etc.
-   **Command**: `php artisan migrate --force` (used in setup)
-   **Convention**: Use snake_case in migration but check actual table name used in model

### File Storage

-   **Driver**: `disk('public')` configured in `config/filesystems.php`
-   **Paths**: Education media stored as paths in `MediaKonten.path_file`
-   **Cleanup**: `Storage::disk('public')->delete($path)` when deleting media/content

---

## Blade Templating & View Patterns

### Common Directives

```blade
@extends('layouts.app')              {{-- Inherit main layout --}}
@section('title', 'Page Title')      {{-- Set page title --}}
@section('content') ... @endsection  {{-- Define content block --}}
@vite(['resources/css/...', ...])    {{-- Load CSS/JS via Vite --}}

@foreach($items as $item)
  @if($item->status === 'published')
    {{-- Conditional rendering --}}
  @endif
@endforeach
```

### Inline Styling Pattern

Views often include `<style>` blocks with scoped utilities (CSS Grid, Flexbox, custom shadows). This is common—keep inline styles organized with consistent property order (padding, background, border-radius, etc.).

### Layout Structure

-   **Main Layout**: `resources/views/layouts/app.blade.php` (navbar, footer, auth check)
-   **User Dashboard**: Shows top 3 articles from Education ordered by date
-   **Role-Specific Dashboards**: Separate views for admin, logistics, users with different content

---

## Model & Relationship Conventions

### Naming Inconsistencies (Real Project Quirks)

-   Table names: Indonesian/mixed-case (`pengguna`, `kontenedukasi`, `transaksisampah`, `session`)
-   Primary keys: Prefixed with model context (`id_user`, `id_konten`, `id_tSampah`)
-   Foreign keys: Match the convention (e.g., `id_user` in related tables)
-   Always validate exact column names in migrations before assuming

### Query Patterns

```php
// Typical pattern: with relationships, order, filter by status
Education::where('status', 'published')
  ->with(['statistik', 'media'])
  ->orderBy('tanggal_upload', 'desc')
  ->paginate(9);

// Session-aware user data
$userId = Session::get('user_id');
ReaksiKonten::where('id_user', $userId)->pluck('tipe_reaksi', 'id_konten')->toArray();
```

### Helper Methods on Models

-   **TransaksiSampah**: `canEdit()`, `canDelete()` (check status === 'Menunggu'), `isCompleted()`, `isProcessing()`
-   **Education**: `incrementView()` method updates `statistik` relationship
-   Always check model for business logic methods—don't duplicate in controllers

---

## Testing

### Pest Configuration

-   **Test Location**: `tests/` with `Feature/` and `Unit/` subdirectories
-   **Base TestCase**: `tests/TestCase.php` configured for Laravel
-   **Plugins**: Pest Laravel plugin provides Eloquent factories, database assertions
-   **Command**: `@php artisan test` (clears config first)

### Writing Tests

```php
// Use database transactions to rollback between tests
use Tests\TestCase;
test('user can login', function () {
    // arrange
    // act
    // assert
})->group('auth');
```

---

## Common Pitfalls & Conventions

### Session Not Eloquent Guard

-   **Don't** use `Auth::user()` or `auth()` helper—this project uses `Session::get('user_id')`
-   **Always** check `Session::has('user_id')` instead of `auth()->check()`
-   Logging out: `Session::flush()` not `Auth::logout()`

### Custom Table Names Everywhere

-   Every model has custom `protected $table = 'tablename'` and `protected $primaryKey = 'id_xx'`
-   Migrations use custom names—always verify model file before writing queries

### Email Validation

-   Login/Register forms validate `email.ends_with:@gmail.com`
-   This is intentional, not a bug—update validation rules if needed

### Media & File Storage

-   Education media ordered by `urutan` column
-   Always include `with(['media'])` when eager-loading to avoid N+1 queries
-   Delete media files from disk when soft-deleting content is not used (cascade in boot)

### Status Fields Use Indonesian

-   TransaksiSampah statuses: `'Menunggu'`, `'Diproses'`, `'Selesai'` (not English)
-   Reaction types: `'suka'`, `'membantu'`, `'menarik'`, `'inspiratif'`
-   Always use exact string matches in validations and comparisons

---

## File Organization

```
app/
  Http/Controllers/
    AuthController.php (custom auth)
    Admin/AdminEducationController.php
    EducationController.php (education CRUD + reactions)
  Http/Middleware/
    CheckSession.php (session validation)
    CheckRole.php (variadic role checking)
  Models/ (custom tables, relationships, boot hooks)
    User.php, Education.php, TransaksiSampah.php, etc.

resources/
  views/
    layouts/app.blade.php (main layout)
    education/ (public + admin views)
    waste-exchange/ (transaction pages)
    vouchers/ (reward system)
    user/profile.blade.php
  css/pages/ (page-specific styles)
  js/ (Vite entry point)

database/
  migrations/ (custom table names)
  seeders/
```

---

## Quick Reference: Adding Features

### Add a New Model with Custom Table

1. Create migration with custom table name
2. Create model with `protected $table` and `protected $primaryKey`
3. Define relationships and boot hooks
4. Create controller with session-aware queries (use `Session::get('user_id')`)

### Add Authentication Check

Use middleware: `middleware('auth.session')` (not `auth`)

### Add Role-Based Page

Route with: `middleware('check.role:role_name')` and create dashboard view with role-specific content

### File Upload

Use `Storage::disk('public')->put()` and store path in model (see MediaKonten pattern)

---

## Useful Commands

```bash
php artisan tinker                    # Interactive shell
php artisan serve                     # Dev server
php artisan queue:listen --tries=1    # Background job queue
php artisan pail --timeout=0          # Log streaming
php artisan migrate:fresh             # Reset DB (dev only)
php artisan migrate --force           # Production migration
npm run build                         # Production CSS/JS
npm run dev                           # Dev server with HMR
```

---

## External Documentation

-   [Laravel 12 Docs](https://laravel.com/docs/12.x/)
-   [Eloquent ORM](https://laravel.com/docs/12.x/eloquent)
-   [Blade Templating](https://laravel.com/docs/12.x/blade)
-   [Tailwind CSS](https://tailwindcss.com)
-   [Vite](https://vitejs.dev)
-   [Pest Testing](https://pestphp.com/)
