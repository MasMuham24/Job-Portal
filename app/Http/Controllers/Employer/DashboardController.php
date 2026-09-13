<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $company = Auth::user()->company;

        if (! $company) {
            return view('employer.dashboard', [
                'hasCompany' => false,
                'totalJobs' => 0,
                'activeJobs' => 0,
                'pendingJobs' => 0,
                'closedJobs' => 0,
                'recentJobs' => collect(),
            ]);
        }

        $totalJobs = $company->jobs()->count();
        $activeJobs = $company->jobs()->where('status', 'active')->count();
        $pendingJobs = $company->jobs()->where('status', 'pending')->count();
        $closedJobs = $company->jobs()->where('status', 'closed')->count();

        $recentJobs = $company->jobs()->latest()->take(5)->get();

        return view('employer.dashboard', compact(
            'company',
            'totalJobs',
            'activeJobs',
            'pendingJobs',
            'closedJobs',
            'recentJobs',
        ))->with('hasCompany', true);
    }
}
