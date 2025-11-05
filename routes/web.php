<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Education;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\WasteExchangeController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\Admin\AdminEducationController;
use App\Http\Controllers\Admin\AdminWasteExchangeController;

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
            $topArticles = Education::where('status', 'published')
                ->with('statistik')
                ->orderBy('tanggal_upload', 'desc')
                ->limit(3)
                ->get();

            return view('user.dashboard', compact('topArticles'));
        })->name('dashboard');
    });
    
    // Dashboard Admin
    Route::prefix('admin')->name('admin.')->middleware('check.role:admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
    });

    Route::get('/users', function () {
        // Sementara redirect ke dashboard dulu
        return redirect()->route('admin.dashboard')->with('info', 'User management feature coming soon!');
    })->name('users');
    
    // Dashboard Tim Logistik
    Route::prefix('logistik')->name('logistik.')->middleware('check.role:tim_logistik')->group(function () {
        Route::get('/dashboard', function () {
            return view('logistik.dashboard');
        })->name('dashboard');
    });
});

//  Fallback Route 
Route::fallback(function () {
    return redirect()->route('welcome');
});

// Public Education Routes
// Rute ini sekarang akan mengecek role admin dan me-redirect jika perlu.
Route::get('/education', function (Request $request) {
    // Cek apakah user sedang login dan memiliki role 'admin'
    if (Session::has('user_role') && Session::get('user_role') === 'admin') {
        // Jika admin, redirect ke halaman management admin
        return redirect()->route('admin.education.index');
    }
    
    // Jika bukan admin atau belum login, jalankan index method dari EducationController
    // Kita panggil controller secara langsung karena kita mengambil alih rute aslinya.
    return app(EducationController::class)->index($request);
})->name('education.index');
Route::get('/education/{id}', [EducationController::class, 'show'])->name('education.show');


// Reaction Route (requires authentication or guest with IP tracking)
Route::post('/education/{id}/reaction', [EducationController::class, 'addReaction'])
    ->name('education.reaction');

// Admin Only Routes
// Route::middleware(['auth.session', 'check.role:admin'])->prefix('admin')->name('admin.')->group(function () {
//    Route::get('/education', [AdminEducationController::class, 'index'])->name('education.index');
//    Route::get('/education', [AdminEducationController::class, 'index'])->name('education.index');
//    Route::get('/education/create', [AdminEducationController::class, 'create'])->name('education.create');
//    Route::post('/education', [AdminEducationController::class, 'store'])->name('education.store');
//    Route::get('/education/{id}', [AdminEducationController::class, 'show'])->name('education.show');
//    Route::get('/education/{id}/edit', [AdminEducationController::class, 'edit'])->name('education.edit');
//    Route::put('/education/{id}', [AdminEducationController::class, 'update'])->name('education.update');
//    Route::delete('/education/{id}', [AdminEducationController::class, 'destroy'])->name('education.destroy');});

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

// Waste Exchange Routes (require authentication)
Route::middleware(['auth.session','check.role:admin'])->group(function () {
    Route::prefix('waste-exchange')->name('waste-exchange.')->group(function () {
        Route::get('/', [WasteExchangeController::class, 'index'])->name('index');
        Route::get('/create', [WasteExchangeController::class, 'create'])->name('create');
        Route::post('/store', [WasteExchangeController::class, 'store'])->name('store');
        Route::get('/history', [WasteExchangeController::class, 'history'])->name('history');
        Route::get('/{id}', [WasteExchangeController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [WasteExchangeController::class, 'edit'])->name('edit');
        Route::put('/{id}', [WasteExchangeController::class, 'update'])->name('update');
        Route::delete('/{id}', [WasteExchangeController::class, 'destroy'])->name('destroy');
        
        // API endpoint
        Route::get('/api/drop-points', [WasteExchangeController::class, 'getDropPoints'])->name('api.drop-points');
    });
});

// FAQ Page
Route::get('/faq', [FaqController::class, 'index'])->name('faq.faq');

///////// ADMIN ADMIN ADMIN ////////////////////////
// Admin Education Routes
Route::middleware(['auth.session', 'check.role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Education Management
    Route::get('/education', [AdminEducationController::class, 'index'])->name('education.index');
    Route::get('/education/create', [AdminEducationController::class, 'create'])->name('education.create');
    Route::post('/education', [AdminEducationController::class, 'store'])->name('education.store');
    Route::get('/education/{id}', [AdminEducationController::class, 'show'])->name('education.show'); // Tambahkan show
    Route::get('/education/{id}/edit', [AdminEducationController::class, 'edit'])->name('education.edit');
    Route::put('/education/{id}', [AdminEducationController::class, 'update'])->name('education.update');
    Route::delete('/education/{id}', [AdminEducationController::class, 'destroy'])->name('education.destroy');

    // Waste Exchange Management
    Route::get('/waste-exchange', [AdminWasteExchangeController::class, 'index'])->name('waste.index');
    
    // Drop Point
    Route::get('/waste-exchange/droppoint', [AdminWasteExchangeController::class, 'dropPointIndex'])->name('waste.droppoint.index');
    Route::get('/waste-exchange/droppoint/create', [AdminWasteExchangeController::class, 'dropPointCreate'])->name('waste.droppoint.create');
    Route::post('/waste-exchange/droppoint', [AdminWasteExchangeController::class, 'dropPointStore'])->name('waste.droppoint.store');
    Route::get('/waste-exchange/droppoint/{id}', [AdminWasteExchangeController::class, 'dropPointShow'])->name('waste.droppoint.show');
    Route::get('/waste-exchange/droppoint/{id}/edit', [AdminWasteExchangeController::class, 'dropPointEdit'])->name('waste.droppoint.edit');
    Route::put('/waste-exchange/droppoint/{id}', [AdminWasteExchangeController::class, 'dropPointUpdate'])->name('waste.droppoint.update');
    Route::delete('/waste-exchange/droppoint/{id}', [AdminWasteExchangeController::class, 'dropPointDestroy'])->name('waste.droppoint.destroy');

    // Transaksi
    Route::get('/waste-exchange/transaksi', [AdminWasteExchangeController::class, 'transaksiIndex'])->name('waste.transaksi.index');
    Route::get('/waste-exchange/transaksi/{id}', [AdminWasteExchangeController::class, 'transaksiShow'])->name('waste.transaksi.show');
    Route::patch('/waste-exchange/transaksi/{id}/status', [AdminWasteExchangeController::class, 'transaksiUpdateStatus'])->name('waste.transaksi.status');
    Route::delete('/waste-exchange/transaksi/{id}', [AdminWasteExchangeController::class, 'transaksiDestroy'])->name('waste.transaksi.destroy');

    // Logistik
    Route::get('/waste-exchange/logistik', [AdminWasteExchangeController::class, 'logistikIndex'])->name('waste.logistik.index');
});