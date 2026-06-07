<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NgendevVideo extends Model
{
    protected $table = 'ngendev_videos';

    protected $fillable = [
        'category_id',
        'video_thumbnail',
        'video_path',
        'ai_prompt',
        'ai_model',
        'sort_order',
        'no_of_video',
        'name_change',
    ];

    protected $casts = [
        'name_change' => 'integer',
        'sort_order'  => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(NgendevVideoCategory::class, 'category_id');
    }
}
