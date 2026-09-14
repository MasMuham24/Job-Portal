<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobStatusController extends Controller
{
    /**
     * Return lightweight status data for all active job postings.
     * Used by polling on the public jobs listing page.
     */
    public function index(): JsonResponse
    {
        $jobs = JobPosting::select('id', 'status', 'updated_at')
            ->where('status', 'active')
            ->latest('updated_at')
            ->get();

        return response()->json($jobs);
    }

    /**
     * Return lightweight status data for a single job posting.
     * Used by polling on the job detail page.
     */
    public function show(JobPosting $jobPosting): JsonResponse
    {
        return response()->json([
            'id' => $jobPosting->id,
            'status' => $jobPosting->status,
            'updated_at' => $jobPosting->updated_at,
        ]);
    }
}
