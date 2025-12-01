<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Education;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\WasteExchangeController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\LogistikController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\Admin\AdminEducationController;
use App\Http\Controllers\Admin\AdminWasteExchangeController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminFaqController;
use App\Http\Controllers\Admin\AdminVoucherController;
use App\Http\Controllers\RiwayatPoinController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminPointController;

// =========================
//  WELCOME / LANDING PAGE
// =========================
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// =========================
//  AUTHENTICATION ROUTES
// =========================
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

// =========================
//  PUBLIC ROUTES (TANPA LOGIN)
// =========================

// Education - Public
Route::get('/education', function (Request $request) {
    if (Session::has('user_role') && Session::get('user_role') === 'admin') {
        return redirect()->route('admin.education.index');
    }
    return app(EducationController::class)->index($request);
})->name('education.index');

Route::get('/education/{id}', [EducationController::class, 'show'])->name('education.show');
Route::post('/education/{id}/reaction', [EducationController::class, 'addReaction'])->name('education.reaction');

// FAQ - Public
Route::get('/faq', [FaqController::class, 'index'])->name('faq.user');

// Voucher - Public
Route::get('/vouchers', [VoucherController::class,'index'])->name('vouchers.index');
Route::get('/vouchers/{voucher}', [VoucherController::class,'show'])->name('vouchers.show');
Route::get('/api/vouchers', [VoucherController::class,'apiIndex'])->name('vouchers.api.index');

// Community - Public
Route::get('/community', [CommunityController::class, 'index'])->name('community.index');

// =========================
//  ROUTES UNTUK PENGGUNA (ROLE: PENGGUNA)
// =========================
Route::middleware(['auth.session', 'check.role:pengguna'])->group(function () {
    
    // Dashboard User
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', function () {
            $topArticles = Education::where('status', 'published')
                ->with('statistik')
                ->orderBy('tanggal_upload', 'desc')
                ->limit(3)
                ->get();
            return view('user.dashboard', compact('topArticles'));
        })->name('dashboard');

        Route::get('/stats', [UserDashboardController::class, 'getStats'])->name('api.stats');
        
        // Profile Management
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update.post');
        
        // Riwayat Poin User
        Route::get('/riwayat-poin', [RiwayatPoinController::class, 'index'])->name('riwayat.poin');
    });

    // Waste Exchange untuk Pengguna
    Route::prefix('waste-exchange')->name('waste-exchange.')->group(function () {
        Route::get('/', [WasteExchangeController::class, 'index'])->name('index');
        Route::get('/create', [WasteExchangeController::class, 'create'])->name('create');
        Route::post('/store', [WasteExchangeController::class, 'store'])->name('store');
        Route::get('/history', [WasteExchangeController::class, 'history'])->name('history');
        Route::get('/{id}', [WasteExchangeController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [WasteExchangeController::class, 'edit'])->name('edit');
        Route::put('/{id}', [WasteExchangeController::class, 'update'])->name('update');
        Route::delete('/{id}', [WasteExchangeController::class, 'destroy'])->name('destroy');
        
        Route::get('/api/drop-points', [WasteExchangeController::class, 'getDropPoints'])->name('api.drop-points');
    });

    // Voucher Actions untuk Pengguna
    Route::middleware(['auth'])->group(function(){
        Route::post('/vouchers/{voucher}/redeem', [VoucherController::class,'redeem'])->name('vouchers.redeem');
        Route::get('/vouchers/favorites', [VoucherController::class, 'favorites'])->name('vouchers.favorites');
    });

    // Community Actions untuk Pengguna
    Route::middleware(['auth'])->group(function(){
        Route::post('/community', [CommunityController::class,'store'])->name('community.store');
        Route::post('/community/{post}/update', [CommunityController::class,'update'])->name('community.update');
        Route::post('/community/{post}/delete', [CommunityController::class,'destroy'])->name('community.delete');
        Route::post('/community/{post}/report', [CommunityController::class,'report'])->name('community.report');
    });

    // API untuk semua pengguna terautentikasi
    Route::get('/api/droppoint/{id}', [\App\Http\Controllers\Admin\AdminWasteExchangeController::class, 'dropPointShow']);
});

// =========================
//  ROUTES UNTUK ADMIN (ROLE: ADMIN)
// =========================
Route::middleware(['auth.session', 'check.role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Education Management
    Route::get('/education', [AdminEducationController::class, 'index'])->name('education.index');
    Route::get('/education/create', [AdminEducationController::class, 'create'])->name('education.create');
    Route::post('/education', [AdminEducationController::class, 'store'])->name('education.store');
    Route::get('/education/{id}', [AdminEducationController::class, 'show'])->name('education.show');
    Route::get('/education/{id}/edit', [AdminEducationController::class, 'edit'])->name('education.edit');
    Route::put('/education/{id}', [AdminEducationController::class, 'update'])->name('education.update');
    Route::delete('/education/{id}', [AdminEducationController::class, 'destroy'])->name('education.destroy');

    // Waste Exchange Management
    Route::get('/waste-exchange', [AdminWasteExchangeController::class, 'index'])->name('waste.index');

    // Drop Point Management
    Route::get('/waste-exchange/droppoint', [AdminWasteExchangeController::class, 'dropPointIndex'])->name('waste.droppoint.index');
    Route::get('/waste-exchange/droppoint/create', [AdminWasteExchangeController::class, 'dropPointCreate'])->name('waste.droppoint.create');
    Route::post('/waste-exchange/droppoint', [AdminWasteExchangeController::class, 'dropPointStore'])->name('waste.droppoint.store');
    Route::get('/waste-exchange/droppoint/{id}', [AdminWasteExchangeController::class, 'dropPointShow'])->name('waste.droppoint.show');
    Route::get('/waste-exchange/droppoint/{id}/edit', [AdminWasteExchangeController::class, 'dropPointEdit'])->name('waste.droppoint.edit');
    Route::put('/waste-exchange/droppoint/{id}', [AdminWasteExchangeController::class, 'dropPointUpdate'])->name('waste.droppoint.update');
    Route::delete('/waste-exchange/droppoint/{id}', [AdminWasteExchangeController::class, 'dropPointDestroy'])->name('waste.droppoint.destroy');

    // Transaksi Management
    Route::get('/waste-exchange/transaksi', [AdminWasteExchangeController::class, 'transaksiIndex'])->name('waste.transaksi.index');
    Route::get('/waste-exchange/transaksi/{id}', [AdminWasteExchangeController::class, 'transaksiShow'])->name('waste.transaksi.show');
    Route::patch('/waste-exchange/transaksi/{id}/status', [AdminWasteExchangeController::class, 'transaksiUpdateStatus'])->name('waste.transaksi.status');
    Route::delete('/waste-exchange/transaksi/{id}', [AdminWasteExchangeController::class, 'transaksiDestroy'])->name('waste.transaksi.destroy');

    // Logistik Management
    Route::get('/waste-exchange/logistik', [AdminWasteExchangeController::class, 'logistikIndex'])->name('waste.logistik.index');

    // Point Transaction Management (Riwayat Poin Admin)
    Route::prefix('riwayat_poin')->name('riwayat_poin.')->group(function () {
        Route::get('/', [AdminPointController::class, 'index'])->name('index');
        Route::get('/create', [AdminPointController::class, 'create'])->name('create');
        Route::post('/', [AdminPointController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminPointController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminPointController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminPointController::class, 'destroy'])->name('destroy');
    });

    // FAQ Management
    Route::get('/faq', [AdminFaqController::class, 'index'])->name('faq.index');
    Route::get('/faq/create', [AdminFaqController::class, 'create'])->name('faq.create');
    Route::post('/faq', [AdminFaqController::class, 'store'])->name('faq.store');
    Route::get('/faq/{id}/edit', [AdminFaqController::class, 'edit'])->name('faq.edit');
    Route::put('/faq/{id}', [AdminFaqController::class, 'update'])->name('faq.update');
    Route::delete('/faq/{id}', [AdminFaqController::class, 'destroy'])->name('faq.destroy');

    // Vouchers Management
    Route::get('/vouchers', [AdminVoucherController::class, 'index'])->name('vouchers.index');
    Route::get('/vouchers/create', [AdminVoucherController::class, 'create'])->name('vouchers.create');
    Route::post('/vouchers', [AdminVoucherController::class, 'store'])->name('vouchers.store');
    Route::get('/vouchers/{voucher}', [AdminVoucherController::class, 'show'])->name('vouchers.show');
    Route::get('/vouchers/{voucher}/edit', [AdminVoucherController::class, 'edit'])->name('vouchers.edit');
    Route::put('/vouchers/{voucher}', [AdminVoucherController::class, 'update'])->name('vouchers.update');
    Route::delete('/vouchers/{voucher}', [AdminVoucherController::class, 'destroy'])->name('vouchers.destroy');
});

// =========================
//  ROUTES UNTUK LOGISTIK (ROLE: TIM_LOGISTIK)
// =========================
Route::middleware(['auth.session', 'check.role:tim_logistik'])->prefix('logistik')->name('logistik.')->group(function () {
    Route::get('/dashboard', [LogistikController::class, 'dashboard'])->name('dashboard');
    Route::get('/schedule', [LogistikController::class, 'schedule'])->name('schedule');
    Route::get('/history', [LogistikController::class, 'history'])->name('history');
    Route::get('/api/stats', [LogistikController::class, 'getStats'])->name('api.stats');
    Route::get('/pickup/{id}', [LogistikController::class, 'getPickupDetails'])->name('pickup.details');
    Route::put('/pickup/{id}/status', [LogistikController::class, 'updatePickupStatus'])->name('pickup.update-status');
});

// =========================
//  TESTING ROUTES
// =========================
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

// =========================
//  FALLBACK ROUTE
// =========================
Route::fallback(function () {
    return redirect()->route('welcome');
});