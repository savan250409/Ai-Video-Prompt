<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiVideoNgdSetting extends Model
{
    protected $table = 'ai_video_ngd_settings';

    protected $fillable = ['model', 'couple_active'];

    protected $casts = [
        'couple_active' => 'integer',
    ];
}
