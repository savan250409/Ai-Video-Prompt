<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterNgendevVideosNoOfVideoAndImageHint extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE ngendev_videos MODIFY no_of_video VARCHAR(255) NULL");
        DB::statement("ALTER TABLE ngendev_videos DROP COLUMN image_hint");
    }

    public function down()
    {
        DB::statement("ALTER TABLE ngendev_videos ADD COLUMN image_hint TEXT NULL");
        DB::statement("ALTER TABLE ngendev_videos MODIFY no_of_video INT NOT NULL DEFAULT 1");
    }
}
