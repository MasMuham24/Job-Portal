<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationStatusController extends Controller
{
    /**
     * Polling endpoint for a single application's status.
     * Accessible by either the job seeker applicant or the employer owner.
     */
    public function show(Application $application): JsonResponse
    {
        $user = Auth::user();
        $isApplicant = ($application->user_id === $user->id);
        $isEmployerOwner = ($user->company && $application->jobPosting->company_id === $user->company->id);

        if (! $isApplicant && ! $isEmployerOwner && $user->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        return response()->json([
            'id' => $application->id,
            'status' => $application->status,
            'updated_at' => $application->updated_at,
        ]);
    }

    /**
     * Polling endpoint for all applications belonging to the authenticated job seeker.
     */
    public function myStatuses(): JsonResponse
    {
        $statuses = Auth::user()
            ->applications()
            ->select('id', 'status', 'updated_at')
            ->latest('updated_at')
            ->get();

        return response()->json($statuses);
    }
}
