<?php

namespace App\Http\Controllers;

use App\Models\BarangayOfficial;
use Illuminate\Http\Request;

class OfficialController extends Controller
{
    public function index()
    {
        $officials = BarangayOfficial::where('is_active', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        return view('officials.index', compact('officials'));
    }
}
