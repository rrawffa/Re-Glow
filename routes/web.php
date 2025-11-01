<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EducationController;

// Welcome/Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Authentication Routes
Route::middleware(['guest'])->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');
    
    // Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'processRegister'])->name('register.process');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes - Requires Authentication
Route::middleware(['auth.session'])->group(function () {
    
    // Dashboard Pengguna
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', function () {
            return view('user.dashboard');
        })->name('dashboard');
    });
    
    // Dashboard Admin
    Route::prefix('admin')->name('admin.')->middleware('check.role:admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
    });
    
    // Dashboard Tim Logistik
    Route::prefix('logistik')->name('logistik.')->middleware('check.role:tim_logistik')->group(function () {
        Route::get('/dashboard', function () {
            return view('logistik.dashboard');
        })->name('dashboard');
    });
});

// Fallback Route
Route::fallback(function () {
    return redirect()->route('welcome');
});

<<<<<<< HEAD
// Ke Halaman Edukasi
Route::get('/education', 'App\Http\Controllers\EducationController@index')->name('education.index');
Route::get('/education/{id}', 'App\Http\Controllers\EducationController@show')->name('education.show');
=======
///////////////////////////////////////////////////////////////////////////////////////////////////
// Public Education Routes
Route::get('/education', [EducationController::class, 'index'])->name('education.index');
Route::get('/education/{id}', [EducationController::class, 'show'])->name('education.show');

// Reaction Route (requires authentication or guest with IP tracking)
Route::post('/education/{id}/reaction', [EducationController::class, 'addReaction'])
    ->name('education.reaction');

// Admin Only Routes
Route::middleware(['auth.session', 'check.role:admin'])->group(function () {
    Route::get('/education/create', [EducationController::class, 'create'])->name('education.create');
    Route::post('/education', [EducationController::class, 'store'])->name('education.store');
    Route::get('/education/{id}/edit', [EducationController::class, 'edit'])->name('education.edit');
    Route::put('/education/{id}', [EducationController::class, 'update'])->name('education.update');
    Route::delete('/education/{id}', [EducationController::class, 'destroy'])->name('education.destroy');
});
>>>>>>> hal_edukasi

Route::get('/test-db', function () {
    try {
        $db   = DB::select("SELECT DATABASE() as db");
        $tbl  = DB::select('SHOW TABLES');
        return [
            'database' => $db,
            'tables'   => $tbl,
        ];
    } catch (\Exception $e) {
        return $e->getMessage();
    }
});

Route::get('/env-test', function () {
    return env('DB_DATABASE');
});