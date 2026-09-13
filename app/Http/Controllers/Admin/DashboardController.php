<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalEmployers = User::where('role', 'employer')->count();
        $totalJobSeekers = User::where('role', 'job_seeker')->count();
        $totalCompanies = Company::count();
        $totalJobs = JobPosting::count();
        $activeJobs = JobPosting::where('status', 'active')->count();
        $pendingJobs = JobPosting::where('status', 'pending')->count();

        $recentUsers = User::latest()->take(5)->get();
        $recentJobs = JobPosting::with('company')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalEmployers',
            'totalJobSeekers',
            'totalCompanies',
            'totalJobs',
            'activeJobs',
            'pendingJobs',
            'recentUsers',
            'recentJobs',
        ));
    }
}
