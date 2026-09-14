<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $completionPercentage = $user->profileCompletionPercentage();
        $isComplete = $user->isProfileComplete();

        return view('profile.edit', compact('user', 'completionPercentage', 'isComplete'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'gender' => ['required', 'in:Laki-laki,Perempuan'],
            'birth_date' => ['required', 'date', 'before:today'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'education' => ['required', 'string', 'max:100'],
            'school' => ['required', 'string', 'max:255'],
            'skills' => ['required', 'string', 'max:1000'],
            'experience' => ['nullable', 'string', 'max:2000'],
            'bio' => ['required', 'string', 'max:2000'],
        ]);

        $user->update($validated);

        return redirect()->route('profile.edit')->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
