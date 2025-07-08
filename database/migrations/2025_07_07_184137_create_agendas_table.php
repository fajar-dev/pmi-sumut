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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->nullable()              
            ->constrained('users') 
            ->onDelete('set null');
            $table->string('image');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->string('address');
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->string('maps_url')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('slug');
            $table->index('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
