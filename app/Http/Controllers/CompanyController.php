<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function create()
    {
        if (Auth::user()->company) {
            return redirect()->route('company.edit');
        }

        return view('company.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->company) {
            return redirect()->route('company.show')->with('error', 'Anda sudah memiliki profil perusahaan.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $validated['user_id'] = Auth::id();

        Company::create($validated);

        return redirect()->route('company.show')->with('success', 'Profil perusahaan berhasil dibuat.');
    }

    public function show()
    {
        $company = Auth::user()->company;

        abort_unless($company, 404);

        $company->load(['jobs' => fn ($q) => $q->latest()]);

        return view('company.show', compact('company'));
    }

    public function edit()
    {
        $company = Auth::user()->company;

        abort_unless($company, 404);

        return view('company.edit', compact('company'));
    }

    public function update(Request $request)
    {
        $company = Auth::user()->company;

        abort_unless($company, 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $company->update($validated);

        return redirect()->route('company.show');
    }
}
