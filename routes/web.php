<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\JobPostingController as AdminJobPostingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Employer\DashboardController as EmployerDashboardController;
use App\Http\Controllers\JobPostingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        if ($user->role === 'employer') {
            return redirect()->route('employer.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'role:employer'])->prefix('employer')->name('employer.')->group(function () {
    Route::get('/dashboard', [EmployerDashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/job-postings', [AdminJobPostingController::class, 'index'])->name('job-postings.index');
    Route::get('/job-postings/{jobPosting}', [AdminJobPostingController::class, 'show'])->name('job-postings.show');
    Route::put('/job-postings/{jobPosting}/status', [AdminJobPostingController::class, 'updateStatus'])->name('job-postings.status');
    Route::delete('/job-postings/{jobPosting}', [AdminJobPostingController::class, 'destroy'])->name('job-postings.destroy');
});

Route::middleware(['auth', 'role:employer,admin'])->prefix('company')->name('company.')->group(function () {
    Route::get('/create', [CompanyController::class, 'create'])->name('create');
    Route::post('/', [CompanyController::class, 'store'])->name('store');
    Route::get('/', [CompanyController::class, 'show'])->name('show');
    Route::get('/edit', [CompanyController::class, 'edit'])->name('edit');
    Route::put('/', [CompanyController::class, 'update'])->name('update');
});

Route::middleware(['auth', 'role:employer'])->prefix('job-postings')->name('job-postings.')->group(function () {
    Route::get('/', [JobPostingController::class, 'index'])->name('index');
    Route::get('/create', [JobPostingController::class, 'create'])->name('create');
    Route::post('/', [JobPostingController::class, 'store'])->name('store');
    Route::get('/{jobPosting}', [JobPostingController::class, 'show'])->name('show');
    Route::get('/{jobPosting}/edit', [JobPostingController::class, 'edit'])->name('edit');
    Route::put('/{jobPosting}', [JobPostingController::class, 'update'])->name('update');
    Route::delete('/{jobPosting}', [JobPostingController::class, 'destroy'])->name('destroy');
});
