<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobPosting;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $completionPercentage = $user->profileCompletionPercentage();
        $isProfileComplete = $user->isProfileComplete();

        $totalApplications = $user->applications()->count();
        $pendingApplications = $user->applications()->where('status', Application::STATUS_PENDING)->count();
        $reviewedApplications = $user->applications()->where('status', Application::STATUS_REVIEWED)->count();
        $acceptedApplications = $user->applications()->where('status', Application::STATUS_ACCEPTED)->count();
        $rejectedApplications = $user->applications()->where('status', Application::STATUS_REJECTED)->count();

        $recentJobs = JobPosting::with('company')
            ->where('status', 'active')
            ->latest()
            ->take(5)
            ->get();

        $recentApplications = $user->applications()
            ->with('jobPosting.company')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'user',
            'completionPercentage',
            'isProfileComplete',
            'totalApplications',
            'pendingApplications',
            'reviewedApplications',
            'acceptedApplications',
            'rejectedApplications',
            'recentJobs',
            'recentApplications'
        ));
    }
}
