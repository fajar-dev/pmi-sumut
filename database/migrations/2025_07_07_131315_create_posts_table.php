<?php

use App\Models\User;
use App\Enum\PostStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); 
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
            $table->unsignedBigInteger('category_id')->nullable(); 
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('set null');
            $table->string('image');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->timestamp('published_at');
            $table->enum('status', PostStatus::status);
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
        Schema::dropIfExists('posts');
    }
};
