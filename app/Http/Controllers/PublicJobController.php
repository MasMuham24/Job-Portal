<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicJobController extends Controller
{
    public function index(Request $request)
    {
        $query = JobPosting::with('company')->where('status', 'active');

        // Search by title, company name, or location
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhereHas('company', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filters
        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        if ($request->filled('type')) {
            $query->where('employment_type', $request->type);
        }

        if ($request->filled('salary_min')) {
            $query->where('salary_min', '>=', $request->salary_min);
        }

        $jobs = $query->latest()->paginate(10)->withQueryString();

        // Get unique locations and types for filters
        $locations = JobPosting::where('status', 'active')->distinct()->pluck('location');
        $types = JobPosting::where('status', 'active')->distinct()->pluck('employment_type');

        return view('jobs.index', compact('jobs', 'locations', 'types'));
    }

    public function show(JobPosting $jobPosting)
    {
        if ($jobPosting->status !== 'active') {
            abort(404);
        }

        $jobPosting->load('company');

        $hasApplied = false;
        $userApplication = null;
        $isProfileComplete = false;
        if (Auth::check() && Auth::user()->role === 'job_seeker') {
            $userApplication = Auth::user()->applications()->where('job_posting_id', $jobPosting->id)->first();
            $hasApplied = $userApplication !== null;
            $isProfileComplete = Auth::user()->isProfileComplete();
        }

        return view('jobs.show', compact('jobPosting', 'hasApplied', 'userApplication', 'isProfileComplete'));
    }
}
