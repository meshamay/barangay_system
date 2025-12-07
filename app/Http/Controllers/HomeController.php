<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\BarangayOfficial;
use App\Models\Faq;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $announcements = Announcement::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $officials = BarangayOfficial::where('is_active', true)
            ->orderBy('order')
            ->get();

        $faqs = Faq::where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('home', compact('announcements', 'officials', 'faqs'));
    }
}
