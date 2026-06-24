<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use App\Services\CloudinaryService;
use Illuminate\Http\UploadedFile;

class PostService
{
    /**
     * Inject CloudinaryService
     */
    public function __construct(private CloudinaryService $cloudinaryService) {}

    /**
     * Fetch paginated posts applying search, category, status filters, and sorting.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getPaginated(array $filters = []): LengthAwarePaginator
    {
        $query = Post::with(['author', 'category'])
            // Filter by status if provided (e.g. published)
            ->when(isset($filters['status']), function ($q) use ($filters) {
                return $q->where('status', $filters['status']);
            })
            // Filter by category if provided
            ->when(isset($filters['category_id']), function ($q) use ($filters) {
                return $q->byCategory((int) $filters['category_id']);
            })
            // Filter by search query (looks inside title or content)
            ->when(isset($filters['search']), function ($q) use ($filters) {
                $search = $filters['search'];
                return $q->where(function ($subQ) use ($search) {
                    $subQ->where('title', 'LIKE', "%{$search}%")
                         ->orWhere('content', 'LIKE', "%{$search}%");
                });
            })
            // Default sorting by created_at descending
            ->orderBy($filters['sort'] ?? 'created_at', $filters['order'] ?? 'desc');

        return $query->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Find a post by its ID or throw a 404 Exception.
     *
     * @param int $id
     * @return Post
     */
    public function findOrFail(int $id): Post
    {
        // Cache detailed post lookup for speed optimization
        return Cache::remember("post_{$id}", 3600, function () use ($id) {
            return Post::with(['author', 'category'])->findOrFail($id);
        });
    }

    /**
     * Create a new post in the database.
     *
     * @param array $data
     * @return Post
     */
    public function create(array $data): Post
    {
        // Inject current authenticated user ID as the author
        $data['user_id'] = auth()->id();

        // If status is published and no publish date provided, set it to now
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        // Handle featured image upload via Cloudinary
        if (isset($data['featured_image']) && $data['featured_image'] instanceof UploadedFile) {
            $data['featured_image'] = $this->cloudinaryService->uploadImage($data['featured_image'], 'smartcms/posts');
        }

        return Post::create($data);
    }

    /**
     * Update an existing post.
     *
     * @param int $id
     * @param array $data
     * @return Post
     */
    public function update(int $id, array $data): Post
    {
        $post = Post::findOrFail($id);

        // Handle featured image upload via Cloudinary
        if (isset($data['featured_image']) && $data['featured_image'] instanceof UploadedFile) {
            // Delete old image if exists
            if ($post->featured_image) {
                $this->cloudinaryService->deleteImage($post->featured_image);
            }
            $data['featured_image'] = $this->cloudinaryService->uploadImage($data['featured_image'], 'smartcms/posts');
        } elseif (array_key_exists('featured_image', $data) && $data['featured_image'] === null) {
            // User wants to remove image
            if ($post->featured_image) {
                $this->cloudinaryService->deleteImage($post->featured_image);
            }
        }

        $post->update($data);
        // Clear the specific cached post after updating its values
        Cache::forget("post_{$id}");

        return $post->fresh(['author', 'category']);
    }

    /**
     * Soft delete a post.
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $post = Post::findOrFail($id);
        $post->delete();

        // Clear cached post after deletion
        Cache::forget("post_{$id}");
    }
}
