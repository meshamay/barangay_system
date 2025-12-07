<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    /**
     * Display a listing of admin staff.
     */
    public function index()
    {
        $staff = User::whereIn('role', ['admin', 'super_admin'])
            ->orderBy('role', 'desc')
            ->orderBy('name')
            ->paginate(20);

        $stats = [
            'total_staff' => User::whereIn('role', ['admin', 'super_admin'])->count(),
            'super_admins' => User::where('role', 'super_admin')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'active_staff' => User::whereIn('role', ['admin', 'super_admin'])
                ->where('is_approved', true)
                ->count(),
        ];

        return view('superadmin.staff.index', compact('staff', 'stats'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        return view('superadmin.staff.create');
    }

    /**
     * Store a newly created staff member.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'super_admin'])],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'is_approved' => true, // Auto-approve staff created by super admin
        ]);

        return redirect()
            ->route('superadmin.staff.index')
            ->with('success', 'Staff member created successfully');
    }

    /**
     * Display the specified staff member.
     */
    public function show($id)
    {
        $staff = User::whereIn('role', ['admin', 'super_admin'])
            ->findOrFail($id);

        // Get activity stats if staff is admin
        if ($staff->role === 'admin') {
            $stats = [
                'documents_processed' => \App\Models\DocumentRequest::where('processed_by', $id)->count(),
                'complaints_handled' => \App\Models\Complaint::where('assigned_to', $id)->count(),
                'recent_activities' => \App\Models\DocumentRequest::where('processed_by', $id)
                    ->latest()
                    ->limit(5)
                    ->get(),
            ];
        } else {
            $stats = null;
        }

        return view('superadmin.staff.show', compact('staff', 'stats'));
    }

    /**
     * Show the form for editing the specified staff member.
     */
    public function edit($id)
    {
        $staff = User::whereIn('role', ['admin', 'super_admin'])
            ->findOrFail($id);

        return view('superadmin.staff.edit', compact('staff'));
    }

    /**
     * Update the specified staff member.
     */
    public function update(Request $request, $id)
    {
        $staff = User::whereIn('role', ['admin', 'super_admin'])
            ->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'super_admin'])],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'is_approved' => 'boolean',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'is_approved' => $request->boolean('is_approved', true),
        ];

        // Only update password if provided
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $staff->update($updateData);

        return redirect()
            ->route('superadmin.staff.index')
            ->with('success', 'Staff member updated successfully');
    }

    /**
     * Remove the specified staff member.
     */
    public function destroy($id)
    {
        $staff = User::whereIn('role', ['admin', 'super_admin'])
            ->findOrFail($id);

        // Prevent deleting yourself
        if ($staff->id === auth()->id()) {
            return redirect()
                ->route('superadmin.staff.index')
                ->with('error', 'You cannot delete your own account');
        }

        $staff->delete();

        return redirect()
            ->route('superadmin.staff.index')
            ->with('success', 'Staff member deleted successfully');
    }
}
