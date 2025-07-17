<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('generals', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle');
            $table->text('meta_description');
            $table->string('logo');
            $table->string('donation_url');

            $table->string('about_photo');
            $table->string('about_name');
            $table->string('about_description');

            $table->text('welcome_speech');
            $table->text('vision_mission');
            $table->text('goals');

            $table->string('address');
            $table->string('phone');
            $table->string('hotline');
            $table->string('email');
            $table->text('maps_url');

            $table->string('social_instagram');
            $table->string('social_youtube');
            $table->string('social_facebook');
            $table->string('social_twiter');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generals');
    }
};
