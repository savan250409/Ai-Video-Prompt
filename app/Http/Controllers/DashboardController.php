<?php

namespace App\Http\Controllers;

use App\Models\NgendevVideoCategory;
use App\Models\NgendevVideo;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $categoryCount = NgendevVideoCategory::count();
        $videoCount    = NgendevVideo::count();

        return view('dashboard', compact('categoryCount', 'videoCount'));
    }
}
