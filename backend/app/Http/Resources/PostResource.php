<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array payload.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'slug'           => $this->slug,
            'excerpt'        => $this->excerpt,
            // Only include the heavy 'content' column when specifically requesting a single post details view
            'content'        => $this->when($request->routeIs('posts.show'), $this->content),
            'status'         => $this->status,
            'featured_image' => $this->featured_image,
            'published_at'   => $this->published_at?->toIso8601String(),
            'created_at'     => $this->created_at->toIso8601String(),
            'author'         => [
                'id'   => $this->author->id,
                'name' => $this->author->name,
            ],
            // Conditionally include category details if a category is assigned
            'category'       => $this->when($this->category, function () {
                return [
                    'id'   => $this->category->id,
                    'name' => $this->category->name,
                    'slug' => $this->category->slug,
                ];
            }),
        ];
    }
}
