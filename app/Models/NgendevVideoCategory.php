<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NgendevVideoCategory extends Model
{
    protected $table = 'ngendev_video_categories';

    protected $fillable = [
        'category_name',
        'category_image',
        'type',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'category_image' => 'array',
        'status'         => 'integer',
        'sort_order'     => 'integer',
    ];

    public function videos()
    {
        return $this->hasMany(NgendevVideo::class, 'category_id');
    }

    public function getFirstImageAttribute()
    {
        $images = $this->category_image;
        return (is_array($images) && count($images) > 0) ? $images[0] : null;
    }
}
