<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\Http\Request;

class JobPostingController extends Controller
{
    public function index(Request $request)
    {
        $query = JobPosting::with('company.user');

        if ($request->filled('status') && in_array($request->status, ['pending', 'active'])) {
            $query->where('status', $request->status);
        }

        $jobs = $query->latest()->paginate(15)->withQueryString();

        $pendingCount = JobPosting::where('status', 'pending')->count();
        $activeCount = JobPosting::where('status', 'active')->count();
        $totalCount = JobPosting::count();

        return view('admin.job-postings.index', compact('jobs', 'pendingCount', 'activeCount', 'totalCount'));
    }

    public function show(JobPosting $jobPosting)
    {
        $jobPosting->load('company.user');

        return view('admin.job-postings.show', compact('jobPosting'));
    }

    public function updateStatus(Request $request, JobPosting $jobPosting)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,active'],
        ]);

        $jobPosting->update(['status' => $validated['status']]);

        $statusLabel = $validated['status'] === 'active' ? 'disetujui' : 'ditandai sebagai pending';

        return redirect()->route('admin.job-postings.show', $jobPosting)
            ->with('success', "Lowongan \"{$jobPosting->title}\" berhasil {$statusLabel}.");
    }

    public function destroy(JobPosting $jobPosting)
    {
        $title = $jobPosting->title;
        $jobPosting->delete();

        return redirect()->route('admin.job-postings.index')
            ->with('success', "Lowongan \"{$title}\" berhasil dihapus.");
    }
}
