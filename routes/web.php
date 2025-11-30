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
use App\Http\Controllers\Admin\AdminEducationController;
use App\Http\Controllers\Admin\AdminWasteExchangeController;
use App\Http\Controllers\Admin\AdminPointController;
use App\Http\Controllers\RiwayatPoinController;
use App\Http\Controllers\ProfileController;


// =========================
//  WELCOME / LANDING PAGE
// =========================
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


// =========================
//  PROTECTED ROUTES
// =========================
Route::middleware(['auth.session'])->group(function () {

    // ----- USER DASHBOARD -----
    Route::prefix('user')->name('user.')->group(function () {

        Route::get('/dashboard', function () {
            $topArticles = Education::where('status', 'published')
                ->with('statistik')
                ->orderBy('tanggal_upload', 'desc')
                ->limit(3)
                ->get();

            return view('user.dashboard', compact('topArticles'));
        })->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update.post');
    });

    // ----- ADMIN DASHBOARD -----
    Route::prefix('admin')->name('admin.')->middleware('check.role:admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
    });

    // ----- LOGISTIK DASHBOARD -----
    Route::prefix('logistik')->name('logistik.')->middleware('check.role:tim_logistik')->group(function () {
        Route::get('/dashboard', [LogistikController::class, 'dashboard'])->name('dashboard');
        Route::get('/schedule', [LogistikController::class, 'schedule'])->name('schedule');
        Route::get('/api/stats', [LogistikController::class, 'getStats'])->name('api.stats');
        Route::get('/pickup/{id}', [LogistikController::class, 'getPickupDetails'])->name('pickup.details');
        Route::put('/pickup/{id}/status', [LogistikController::class, 'updatePickupStatus'])->name('pickup.update-status');
    });
});


// =========================
//  FALLBACK
// =========================
Route::fallback(function () {
    return redirect()->route('welcome');
});


// =========================
//  PUBLIC EDUCATION ROUTES
// =========================
Route::get('/education', function (Request $request) {

    // jika admin → redirect ke halaman admin education
    if (Session::has('user_role') && Session::get('user_role') === 'admin') {
        return redirect()->route('admin.education.index');
    }

    return app(EducationController::class)->index($request);

})->name('education.index');

Route::get('/education/{id}', [EducationController::class, 'show'])
    ->name('education.show');

// Reaction
Route::post('/education/{id}/reaction', [EducationController::class, 'addReaction'])
    ->name('education.reaction');


// =========================
//  WASTE EXCHANGE (PENGGUNA)
// =========================
Route::middleware(['auth.session', 'check.role:pengguna'])
    ->prefix('waste-exchange')
    ->name('waste-exchange.')
    ->group(function () {

        Route::get('/', [WasteExchangeController::class, 'index'])->name('index');
        Route::get('/create', [WasteExchangeController::class, 'create'])->name('create');
        Route::post('/store', [WasteExchangeController::class, 'store'])->name('store');
        Route::get('/history', [WasteExchangeController::class, 'history'])->name('history');
        Route::get('/{id}', [WasteExchangeController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [WasteExchangeController::class, 'edit'])->name('edit');
        Route::put('/{id}', [WasteExchangeController::class, 'update'])->name('update');
        Route::delete('/{id}', [WasteExchangeController::class, 'destroy'])->name('destroy');

        // API
        Route::get('/api/drop-points', [WasteExchangeController::class, 'getDropPoints'])
            ->name('api.drop-points');
});

//RIWAYAT POIN
// User (Public)
Route::get('/riwayat-poin', function (Request $request) {
    if (!Session::has('user_id')) {
        return redirect()->route('login');
    }

    return app(RiwayatPoinController::class)->index($request);
})->name('riwayat.poin');

// Admin (CRUD)
Route::middleware(['auth.session', 'check.role:admin'])
    ->prefix('admin/riwayat_poin')
    ->name('admin.riwayat_poin.')
    ->group(function () {
        Route::get('/', [AdminPointController::class, 'index'])->name('index');
        Route::get('/create', [AdminPointController::class, 'create'])->name('create');
        Route::post('/', [AdminPointController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminPointController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [AdminPointController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminPointController::class, 'destroy'])->name('destroy');
    });



// =========================
//  FAQ PAGE
// =========================
Route::get('/faq', [FaqController::class, 'index'])->name('faq.faq');


// =========================
//  ADMIN EDUCATION MANAGEMENT
// =========================
Route::middleware(['auth.session', 'check.role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/education', [AdminEducationController::class, 'index'])->name('education.index');
        Route::get('/education/create', [AdminEducationController::class, 'create'])->name('education.create');
        Route::post('/education', [AdminEducationController::class, 'store'])->name('education.store');
        Route::get('/education/{id}', [AdminEducationController::class, 'show'])->name('education.show');
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


// =========================
//  VOUCHER PUBLIC ROUTES
// =========================

Route::get('/vouchers', [VoucherController::class,'index'])->name('vouchers.index');
Route::get('/vouchers/{voucher}', [VoucherController::class,'show'])->name('vouchers.show');
Route::post('/vouchers/{voucher}/redeem', [VoucherController::class,'redeem'])->name('vouchers.redeem');
Route::get('/api/vouchers', [VoucherController::class,'apiIndex'])->name('vouchers.apiIndex');

// Favorite Vouchers (sementara)
Route::get('/vouchers/favorites', [VoucherController::class, 'favorites'])->name('vouchers.favorites');

// community sharing 
Route::get('/community', [CommunityController::class, 'index'])->name('community.index');

