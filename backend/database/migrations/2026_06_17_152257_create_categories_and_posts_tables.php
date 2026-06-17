<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to create categories and posts tables.
     */
    public function up(): void
    {
        // 1. Create categories table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique(); // URL friendly version of the name
            $table->text('description')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete(); // Supports subcategories
            $table->timestamps();
        });

        // 2. Create posts table
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique(); // URL friendly version of the title
            $table->longText('content');
            $table->string('excerpt', 500)->nullable(); // Short summary of the post
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft'); // Post state
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Author of the post (FK linked to users table)
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete(); // Category of the post (FK)
            $table->string('featured_image')->nullable(); // Image path or Cloudinary URL
            $table->timestamp('published_at')->nullable(); // Publication date time
            $table->timestamps();
            $table->softDeletes(); // Enabled soft deletion (virtual trash bin)

            // Indexes for speed queries
            $table->index(['status', 'published_at']);
        });
    }

    /**
     * Reverse the migrations (Drop tables in reverse order).
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
        Schema::dropIfExists('categories');
    }
};
