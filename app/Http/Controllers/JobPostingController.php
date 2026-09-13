<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobPostingController extends Controller
{
    public function index()
    {
        $company = Auth::user()->company;
        abort_unless($company, 404);
        $jobs = $company->jobs()->latest()->paginate(10);

        return view('job-postings.index', compact('jobs'));
    }

    public function create()
    {
        $company = Auth::user()->company;
        abort_unless($company, 404);

        return view('job-postings.create');
    }

    public function store(Request $request)
    {
        $company = Auth::user()->company;
        abort_unless($company, 404);
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'employment_type' => ['required', 'string', 'max:100'],
            'salary_min' => ['nullable', 'integer', 'min:0'],
            'salary_max' => ['nullable', 'integer', 'gte:salary_min'],
            'deadline' => ['nullable', 'date', 'after_or_equal:today'],
        ]);
        $validated['status'] = 'pending';
        $company->jobs()->create($validated);

        return redirect()->route('job-postings.index')->with('success', 'Lowongan berhasil dibuat dan menunggu persetujuan admin.');
    }

    public function show(JobPosting $jobPosting)
    {
        $company = Auth::user()->company;
        abort_unless($company, 404);
        abort_unless($jobPosting->company_id === $company->id, 403);

        return view('job-postings.show', compact('jobPosting'));
    }

    public function edit(JobPosting $jobPosting)
    {
        $company = Auth::user()->company;
        abort_unless($company, 404);
        abort_unless($jobPosting->company_id === $company->id, 403);

        return view('job-postings.edit', compact('jobPosting'));
    }

    public function update(Request $request, JobPosting $jobPosting)
    {
        $company = Auth::user()->company;
        abort_unless($company, 404);
        abort_unless($jobPosting->company_id === $company->id, 403);
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'employment_type' => ['required', 'string', 'max:100'],
            'salary_min' => ['nullable', 'integer', 'min:0'],
            'salary_max' => ['nullable', 'integer', 'gte:salary_min'],
            'deadline' => ['nullable', 'date', 'after_or_equal:today'],
        ]);
        $jobPosting->update($validated);

        return redirect()->route('job-postings.show', $jobPosting)->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function destroy(JobPosting $jobPosting)
    {
        $company = Auth::user()->company;
        abort_unless($company, 404);
        abort_unless($jobPosting->company_id === $company->id, 403);

        $jobPosting->delete();

        return redirect()->route('job-postings.index')->with('success', 'Lowongan berhasil dihapus.');
    }
}
