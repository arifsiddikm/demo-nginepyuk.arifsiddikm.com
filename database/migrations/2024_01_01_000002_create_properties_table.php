<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Hotel, Villa, Kosan, Kontrakan, Resort
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('address');
            $table->string('city');
            $table->string('province');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('price_per_night', 12, 2);
            $table->integer('total_rooms');
            $table->integer('max_guests')->default(2);
            $table->string('thumbnail')->nullable();         // local upload path
            $table->string('thumbnail_url')->nullable();     // CDN/external URL fallback
            $table->json('image_urls')->nullable();          // array of CDN image URLs for slider
            $table->json('facilities')->nullable(); // ["WiFi","AC","Pool"]
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->float('rating_avg')->default(0);
            $table->integer('rating_count')->default(0);
            $table->timestamps();
        });

        Schema::create('property_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_images');
        Schema::dropIfExists('properties');
        Schema::dropIfExists('categories');
    }
};
