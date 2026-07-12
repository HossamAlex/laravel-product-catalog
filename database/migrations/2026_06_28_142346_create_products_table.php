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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->nullable()->unique();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            $table->foreignId('brand_id')
                ->nullable()
                ->constrained('brands')
                ->nullOnDelete();

            $table->decimal('price', 10, 3)->nullable();
            $table->decimal('special_price', 10, 3)->nullable();

            $table->unsignedInteger('sort')->default(0);
            $table->boolean('status')->default(true);
            $table->boolean('is_featured')->default(false);

            $table->boolean('is_trending')->default(false);

            $table->boolean('is_recommended')->default(false);

            $table->timestamp('published_at')->nullable();
            $table->integer('views_count')->default(0);
            $table->integer('stock')->default(0);

            $table->timestamps();

            $table->index(['status', 'sort']);
            $table->index('brand_id');



            

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
