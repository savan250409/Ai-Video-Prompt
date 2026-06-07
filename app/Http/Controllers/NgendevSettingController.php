<?php

namespace App\Http\Controllers;

use App\Models\AiVideoNgdSetting;
use Illuminate\Http\Request;

class NgendevSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $setting = AiVideoNgdSetting::firstOrCreate([], [
            'model' => 'Ngendev Video',
            'couple_active' => 1,
        ]);

        return view('ngendev.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'model' => 'nullable|string|max:255',
        ]);

        $setting = AiVideoNgdSetting::firstOrCreate([], [
            'model' => 'Ngendev Video',
            'couple_active' => 1,
        ]);

        $setting->update([
            'model'         => $request->model,
            'couple_active' => $request->has('couple_active') ? 1 : 0,
        ]);

        return redirect()->route('ngendev.settings.index')
                         ->with('success', 'Settings updated successfully.');
    }

    public function apiList()
    {
        return view('ngendev.api_list.index');
    }
}
