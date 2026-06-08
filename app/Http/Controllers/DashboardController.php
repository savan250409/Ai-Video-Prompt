<?php

namespace App\Http\Controllers;

use App\Models\NgendevVideoCategory;
use App\Models\NgendevVideo;
use App\Models\AiVideoNgdSetting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $categoryCount        = NgendevVideoCategory::count();
        $videoCount           = NgendevVideo::count();
        $activeCategoryCount  = NgendevVideoCategory::where('status', 1)->count();
        $inactiveCategoryCount= NgendevVideoCategory::where('status', 0)->count();
        $soloCount            = NgendevVideoCategory::where('type', 'Solo')->count();
        $coupleCount          = NgendevVideoCategory::where('type', 'Couple')->count();
        $setting              = AiVideoNgdSetting::first();
        $coupleActive         = $setting ? (bool) $setting->couple_active : true;
        $aiModel              = $setting ? $setting->model : 'N/A';
        $recentCategories     = NgendevVideoCategory::orderBy('id', 'desc')->limit(5)->get();

        return view('dashboard', compact(
            'categoryCount', 'videoCount',
            'activeCategoryCount', 'inactiveCategoryCount',
            'soloCount', 'coupleCount',
            'coupleActive', 'aiModel',
            'recentCategories'
        ));
    }
}
