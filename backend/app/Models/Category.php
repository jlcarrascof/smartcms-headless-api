<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Category",
    title: "Category Model",
    description: "Category model database schema representation",
    required: ["id", "name", "slug"]
)]
class Category extends Model
{
    use HasFactory;

    #[OA\Property(property: "id", type: "integer", readOnly: true, example: 1)]
    protected $id;

    #[OA\Property(property: "name", type: "string", example: "Laravel")]
    protected $name;

    #[OA\Property(property: "slug", type: "string", example: "laravel")]
    protected $slug;

    #[OA\Property(property: "description", type: "string", nullable: true, example: "Tutorials, tips, and best practices for Laravel.")]
    protected $description;

    #[OA\Property(property: "parent_id", type: "integer", nullable: true, example: null)]
    protected $parent_id;

    #[OA\Property(property: "created_at", type: "string", format: "date-time", readOnly: true, example: "2026-06-17T18:34:02Z")]
    protected $created_at;

    #[OA\Property(property: "updated_at", type: "string", format: "date-time", readOnly: true, example: "2026-06-17T18:34:02Z")]
    protected $updated_at;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
    ];

    /**
     * Boot function to auto-generate category slug from name on creation.
     */
    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            $category->slug = $category->slug ?? Str::slug($category->name);
        });
    }

    /**
     * Get the posts associated with this category.
     *
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the parent category for this category.
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the subcategories for this category.
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
