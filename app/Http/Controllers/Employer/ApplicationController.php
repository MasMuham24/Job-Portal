<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $company = Auth::user()->company;
        abort_unless($company, 404, 'Profil perusahaan tidak ditemukan.');

        $query = Application::whereHas('jobPosting', function ($q) use ($company) {
            $q->where('company_id', $company->id);
        })->with(['user', 'jobPosting']);

        if ($request->filled('job_id')) {
            $query->where('job_posting_id', $request->job_id);
        }

        if ($request->filled('status') && in_array($request->status, Application::STATUSES)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $applications = $query->latest()->paginate(10)->withQueryString();
        $companyJobs = $company->jobs()->select('id', 'title')->get();

        return view('employer.applications.index', compact('applications', 'companyJobs'));
    }

    public function show(Application $application)
    {
        $company = Auth::user()->company;
        abort_unless($company, 404, 'Profil perusahaan tidak ditemukan.');

        $application->load(['user', 'jobPosting']);
        abort_unless(
            $application->jobPosting->company_id === $company->id,
            403,
            'Akses ditolak. Lamaran ini bukan untuk lowongan perusahaan Anda.'
        );

        return view('employer.applications.show', compact('application'));
    }

    public function updateStatus(Request $request, Application $application)
    {
        $company = Auth::user()->company;
        abort_unless($company, 404, 'Profil perusahaan tidak ditemukan.');

        $application->load('jobPosting');
        abort_unless(
            $application->jobPosting->company_id === $company->id,
            403,
            'Akses ditolak. Anda tidak memiliki izin untuk mengubah status lamaran ini.'
        );

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', Application::STATUSES)],
        ]);

        $application->update(['status' => $validated['status']]);

        $statusLabels = [
            Application::STATUS_PENDING => 'Pending',
            Application::STATUS_REVIEWED => 'Direview',
            Application::STATUS_ACCEPTED => 'Diterima',
            Application::STATUS_REJECTED => 'Ditolak',
        ];

        $label = $statusLabels[$validated['status']] ?? $validated['status'];

        return back()->with('success', "Status lamaran berhasil diperbarui menjadi {$label}.");
    }
}
