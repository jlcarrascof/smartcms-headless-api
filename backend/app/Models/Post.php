<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Post",
    title: "Post Model",
    description: "Post model database schema representation",
    required: ["id", "title", "slug", "content", "status", "user_id"],
    properties: [
        new OA\Property(property: "id", type: "integer", readOnly: true, example: 1),
        new OA\Property(property: "title", type: "string", example: "Getting Started with Laravel 11"),
        new OA\Property(property: "slug", type: "string", example: "getting-started-laravel-11"),
        new OA\Property(property: "content", type: "string", example: "Laravel 11 introduces a streamlined application structure..."),
        new OA\Property(property: "excerpt", type: "string", nullable: true, example: "A practical introduction to Laravel 11."),
        new OA\Property(property: "status", type: "string", enum: ["draft", "published", "archived"], example: "published"),
        new OA\Property(property: "featured_image", type: "string", nullable: true, example: "https://res.cloudinary.com/demo/image/upload/sample.jpg"),
        new OA\Property(property: "user_id", type: "integer", example: 1),
        new OA\Property(property: "category_id", type: "integer", nullable: true, example: 2),
        new OA\Property(property: "published_at", type: "string", format: "date-time", nullable: true, example: "2026-06-17T18:34:02Z"),
        new OA\Property(property: "created_at", type: "string", format: "date-time", readOnly: true, example: "2026-06-17T18:34:02Z"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time", readOnly: true, example: "2026-06-17T18:34:02Z")
    ]
)]
class Post extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'status',
        'user_id',
        'category_id',
        'featured_image',
        'published_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Boot function to auto-generate post slug from title on creation.
     */
    protected static function booted(): void
    {
        static::creating(function (Post $post) {
            $post->slug = $post->slug ?? Str::slug($post->title);
        });
    }

    /**
     * Get the author (User) that created the post.
     *
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the category that this post belongs to.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope query to only include published posts.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    /**
     * Scope query to filter posts by a specific category.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $categoryId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
}
