<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NgendevTestDataSeeder extends Seeder
{
    public function run()
    {
        // ── Categories ─────────────────────────────────────────────
        $categories = [
            ['category_name' => 'Beauty Portrait',  'type' => 'Solo',   'status' => 1, 'sort_order' => 1],
            ['category_name' => 'Couple Dance',      'type' => 'Couple', 'status' => 1, 'sort_order' => 2],
            ['category_name' => 'Nature Landscape',  'type' => 'Solo',   'status' => 1, 'sort_order' => 3],
            ['category_name' => 'Trending',          'type' => 'Solo',   'status' => 1, 'sort_order' => 4],
            ['category_name' => 'Exclusive',         'type' => 'Solo',   'status' => 1, 'sort_order' => 5],
            ['category_name' => 'Fashion Style',     'type' => 'Solo',   'status' => 1, 'sort_order' => 6],
            ['category_name' => 'Urban Street',      'type' => 'Solo',   'status' => 1, 'sort_order' => 7],
            ['category_name' => 'Romantic Couple',   'type' => 'Couple', 'status' => 1, 'sort_order' => 8],
            ['category_name' => 'Artistic Pose',     'type' => 'Solo',   'status' => 1, 'sort_order' => 9],
            ['category_name' => 'Vintage Vibes',     'type' => 'Solo',   'status' => 0, 'sort_order' => 10],
            ['category_name' => 'Dark Aesthetic',    'type' => 'Solo',   'status' => 1, 'sort_order' => 11],
            ['category_name' => 'Golden Hour',       'type' => 'Couple', 'status' => 1, 'sort_order' => 12],
        ];

        foreach ($categories as $cat) {
            DB::table('ngendev_video_categories')->insertOrIgnore([
                'category_name'  => $cat['category_name'],
                'type'           => $cat['type'],
                'status'         => $cat['status'],
                'sort_order'     => $cat['sort_order'],
                'category_image' => json_encode([]),
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        // ── Videos ─────────────────────────────────────────────────
        $prompts = [
            'A stunning portrait with soft natural lighting, bokeh background, ultra realistic 4K',
            'Cinematic wide shot, golden hour lighting, dramatic shadows, photorealistic',
            'Close-up facial details, professional studio lighting, high fashion editorial style',
            'Dynamic movement captured mid-action, motion blur, vivid colors, 8K resolution',
            'Minimalist composition, white background, elegant pose, high contrast lighting',
            'Outdoor sunset scene, warm tones, soft focus, lifestyle photography style',
            'Abstract artistic portrait, double exposure effect, surreal atmosphere',
            'Urban street photography, candid moment, shallow depth of field, moody tones',
            'Vintage film aesthetic, grain texture, muted palette, nostalgic mood',
            'High energy dance pose, dramatic studio lighting, colorful gel lights',
            'Ethereal dreamy atmosphere, fog effect, pastel tones, fantasy style',
            'Bold graphic composition, neon lights, cyberpunk aesthetic, futuristic',
        ];

        $categories_db = DB::table('ngendev_video_categories')->get();

        $i = 0;
        foreach ($categories_db as $cat) {
            // Insert 2 videos per category (gives 24 total for 12 categories)
            for ($v = 1; $v <= 2; $v++) {
                DB::table('ngendev_videos')->insert([
                    'category_id'     => $cat->id,
                    'ai_prompt'       => $prompts[$i % count($prompts)],
                    'ai_model'        => 'Ngendev Video',
                    'no_of_video'     => rand(1, 5),
                    'name_change'     => ($i % 4 === 0) ? 1 : 0,
                    'image_hint'      => ($i % 4 === 0) ? 'portrait' : null,
                    'video_thumbnail' => null,
                    'video_path'      => null,
                    'sort_order'      => $v,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
                $i++;
            }
        }
    }
}
