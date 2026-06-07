<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNgendevVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ngendev_videos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('ngendev_video_categories')->onDelete('cascade');
            $table->string('video_thumbnail')->nullable();
            $table->string('video_path')->nullable();
            $table->text('ai_prompt')->nullable();
            $table->string('ai_model')->default('Ngendev Video');
            $table->integer('sort_order')->default(0);
            $table->integer('no_of_video')->default(1);
            $table->tinyInteger('name_change')->default(0);
            $table->text('image_hint')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ngendev_videos');
    }
}
