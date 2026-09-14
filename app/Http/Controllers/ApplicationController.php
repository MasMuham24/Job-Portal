<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Auth::user()
            ->applications()
            ->with(['jobPosting.company'])
            ->latest()
            ->paginate(10);

        return view('applications.index', compact('applications'));
    }

    public function show(Application $application)
    {
        abort_unless(
            $application->user_id === Auth::id(),
            403,
            'Akses ditolak. Anda tidak memiliki izin untuk melihat lamaran ini.'
        );

        $application->load(['jobPosting.company']);

        return view('applications.show', compact('application'));
    }

    public function store(Request $request, JobPosting $jobPosting)
    {
        abort_unless(
            Auth::user()->role === 'job_seeker',
            403,
            'Hanya pencari kerja yang dapat melamar lowongan ini.'
        );

        abort_if(
            $jobPosting->status !== 'active',
            403,
            'Lowongan ini tidak aktif dan tidak dapat dilamar.'
        );

        $existing = Application::where('user_id', Auth::id())
            ->where('job_posting_id', $jobPosting->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah melamar lowongan ini.');
        }

        if (! Auth::user()->isProfileComplete()) {
            return redirect()->route('profile.edit')->with('error', 'Lengkapi profil Anda terlebih dahulu sebelum melamar pekerjaan.');
        }

        $validated = $request->validate([
            'cover_letter' => ['nullable', 'string', 'max:2000'],
        ]);

        Application::create([
            'user_id' => Auth::id(),
            'job_posting_id' => $jobPosting->id,
            'status' => Application::STATUS_PENDING,
            'cover_letter' => $validated['cover_letter'] ?? null,
        ]);

        return redirect()->route('applications.index')->with('success', 'Lamaran Anda berhasil dikirim!');
    }
}
