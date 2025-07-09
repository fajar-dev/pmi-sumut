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
        Schema::create('sub_menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->foreign('menu_id')
                ->references('id')
                ->on('menus')
                ->onDelete('cascade') 
                ->onUpdate('cascade'); 
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('sort')->default(1);
            $table->string('url');
            $table->boolean('is_blank')->default(false);
            $table->timestamps();

            $table->index('name');
            $table->index('sort');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_menus');
    }
};
