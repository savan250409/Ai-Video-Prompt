<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAiVideoNgdSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('ai_video_ngd_settings', function (Blueprint $table) {
            $table->id();
            $table->string('model')->nullable();
            $table->tinyInteger('couple_active')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ai_video_ngd_settings');
    }
}
