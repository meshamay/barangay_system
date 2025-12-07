<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangayOfficial;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OfficialController extends Controller
{
    /**
     * Display a listing of barangay officials.
     */
    public function index()
    {
        $officials = BarangayOfficial::orderBy('order')
            ->paginate(15);

        return view('admin.officials.index', compact('officials'));
    }

    /**
     * Show the form for creating a new official.
     */
    public function create()
    {
        return view('admin.officials.create');
    }

    /**
     * Store a newly created official.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'term_start' => 'nullable|date',
            'term_end' => 'nullable|date|after:term_start',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . Str::slug($validated['name']) . '.' . $file->getClientOriginalExtension();
            $photoPath = $file->storeAs('officials', $filename, 'public');
        }

        BarangayOfficial::create([
            'name' => $validated['name'],
            'position' => $validated['position'],
            'photo_path' => $photoPath,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'term_start' => $validated['term_start'] ?? null,
            'term_end' => $validated['term_end'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.officials.index')
            ->with('success', 'Official added successfully');
    }

    /**
     * Display the specified official.
     */
    public function show($id)
    {
        $official = BarangayOfficial::findOrFail($id);
        
        return view('admin.officials.show', compact('official'));
    }

    /**
     * Show the form for editing the specified official.
     */
    public function edit($id)
    {
        $official = BarangayOfficial::findOrFail($id);
        
        return view('admin.officials.edit', compact('official'));
    }

    /**
     * Update the specified official.
     */
    public function update(Request $request, $id)
    {
        $official = BarangayOfficial::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'term_start' => 'nullable|date',
            'term_end' => 'nullable|date|after:term_start',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($official->photo_path) {
                \Storage::disk('public')->delete($official->photo_path);
            }

            $file = $request->file('photo');
            $filename = time() . '_' . Str::slug($validated['name']) . '.' . $file->getClientOriginalExtension();
            $validated['photo_path'] = $file->storeAs('officials', $filename, 'public');
        }

        $official->update([
            'name' => $validated['name'],
            'position' => $validated['position'],
            'photo_path' => $validated['photo_path'] ?? $official->photo_path,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'term_start' => $validated['term_start'] ?? null,
            'term_end' => $validated['term_end'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.officials.index')
            ->with('success', 'Official updated successfully');
    }

    /**
     * Remove the specified official.
     */
    public function destroy($id)
    {
        $official = BarangayOfficial::findOrFail($id);
        
        // Delete photo if exists
        if ($official->photo_path) {
            \Storage::disk('public')->delete($official->photo_path);
        }
        
        $official->delete();

        return redirect()
            ->route('admin.officials.index')
            ->with('success', 'Official deleted successfully');
    }
}
