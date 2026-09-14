<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\JobPostingController as AdminJobPostingController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ApplicationStatusController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Employer\ApplicationController as EmployerApplicationController;
use App\Http\Controllers\Employer\DashboardController as EmployerDashboardController;
use App\Http\Controllers\JobPostingController;
use App\Http\Controllers\JobStatusController;
use App\Http\Controllers\PublicJobController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/jobs', [PublicJobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{jobPosting}', [PublicJobController::class, 'show'])->name('jobs.show');

// Lightweight polling endpoints for real-time status updates
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/job-postings/statuses', [JobStatusController::class, 'index'])->name('job-postings.statuses');
    Route::get('/job-postings/{jobPosting}/status', [JobStatusController::class, 'show'])->name('job-postings.status');

    Route::middleware('auth')->group(function () {
        Route::get('/applications/{application}/status', [ApplicationStatusController::class, 'show'])->name('applications.status');
        Route::get('/my-applications/statuses', [ApplicationStatusController::class, 'myStatuses'])->name('applications.my-statuses');
    });
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

Route::middleware(['auth', 'role:job_seeker'])->group(function () {
    Route::post('/jobs/{jobPosting}/apply', [ApplicationController::class, 'store'])->name('jobs.apply');
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
});

Route::middleware(['auth', 'role:employer'])->prefix('employer')->name('employer.')->group(function () {
    Route::get('/dashboard', [EmployerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/applications', [EmployerApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [EmployerApplicationController::class, 'show'])->name('applications.show');
    Route::put('/applications/{application}/status', [EmployerApplicationController::class, 'updateStatus'])->name('applications.status');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/job-postings', [AdminJobPostingController::class, 'index'])->name('job-postings.index');
    Route::get('/job-postings/{jobPosting}', [AdminJobPostingController::class, 'show'])->name('job-postings.show');
    Route::put('/job-postings/{jobPosting}/status', [AdminJobPostingController::class, 'updateStatus'])->name('job-postings.status');
    Route::delete('/job-postings/{jobPosting}', [AdminJobPostingController::class, 'destroy'])->name('job-postings.destroy');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
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
