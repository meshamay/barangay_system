<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            if (!$user) {
                return back()->withErrors([
                    'email' => 'Authentication failed.',
                ]);
            }

            // Refresh user to ensure all attributes are loaded
            $user->refresh();

            // Check if account is approved
            if ($user->account_status !== 'approved') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is pending approval.',
                ]);
            }

            // Redirect based on role
            return match ($user->role) {
                'super_admin' => redirect()->intended('/superadmin/staff'),
                'admin' => redirect()->intended('/admin/dashboard'),
                'resident' => redirect()->intended('/resident/dashboard'),
                default => redirect()->intended('/'),
            };
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'contact_number' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date',
            'place_of_birth' => 'nullable|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'address' => 'required|string|max:500',
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'contact_number' => $validated['contact_number'] ?? null,
            'date_of_birth' => $validated['date_of_birth'],
            'place_of_birth' => $validated['place_of_birth'] ?? null,
            'gender' => $validated['gender'],
            'address' => $validated['address'],
            'role' => 'resident',
            'account_status' => 'pending',
            'is_active' => true,
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please wait for admin approval.');
    }
}
