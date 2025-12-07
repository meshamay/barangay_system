<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::where('is_active', true)
            ->where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('announcements.index', compact('announcements'));
    }

    public function show($id)
    {
        $announcement = Announcement::where('is_active', true)
            ->where('is_published', true)
            ->findOrFail($id);

        return view('announcements.show', compact('announcement'));
    }
}
