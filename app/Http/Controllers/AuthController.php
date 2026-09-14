<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
            'role' => ['required', 'in:job_seeker,employer'],
            'company_name' => ['required_if:role,employer', 'nullable', 'string', 'max:255'],
            'company_location' => ['nullable', 'string', 'max:255'],
            'company_website' => ['nullable', 'url', 'max:255'],
            'company_phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        if ($validated['role'] === 'employer' && ! empty($validated['company_name'])) {
            Company::create([
                'user_id' => $user->id,
                'name' => $validated['company_name'],
                'location' => $validated['company_location'] ?? null,
                'website' => $validated['company_website'] ?? null,
                'phone' => $validated['company_phone'] ?? null,
            ]);
        }

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan masuk ke akun Anda.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'role' => ['nullable', 'string', 'in:job_seeker,employer'],
        ]);

        if (! Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors(['email' => 'Email atau password salah'])->onlyInput('email', 'role');
        }

        $user = Auth::user();

        $selectedRole = $request->input('role');
        if ($selectedRole) {
            if ($user->role !== $selectedRole) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()
                    ->withErrors(['role' => 'Role login tidak sesuai dengan akun Anda.'])
                    ->onlyInput('email', 'role');
            }
        }

        $request->session()->regenerate();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'employer') {
            return redirect()->route('employer.dashboard');
        }

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
