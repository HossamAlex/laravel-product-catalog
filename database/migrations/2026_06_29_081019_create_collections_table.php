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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('status')->default(true);

            $table->enum('type', [
                'manual',
                'latest',
                'most_viewed',
                'offers',
                'brand',
                'category',
            ])->default('manual');

            $table->unsignedInteger('limit')->default(12);

            $table->foreignId('brand_id')->nullable();
            $table->foreignId('category_id')->nullable();

            $table->boolean('show_on_home')->default(true);


            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
