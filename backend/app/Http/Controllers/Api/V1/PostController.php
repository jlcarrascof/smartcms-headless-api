<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Posts", description: "Endpoints for managing blog posts")]
class PostController extends Controller
{
    /**
     * PostController Constructor injecting PostService dependency.
     */
    public function __construct(private PostService $postService) {}

    #[OA\Get(
        path: "/api/v1/posts",
        summary: "Get list of posts with filters and pagination",
        operationId: "getPostsList",
        tags: ["Posts"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "status", in: "query", description: "Filter by status (draft, published, archived)", required: false, schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "category_id", in: "query", description: "Filter by category ID", required: false, schema: new OA\Schema(type: "integer")),
            new OA\Parameter(name: "search", in: "query", description: "Search term inside title or content", required: false, schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "per_page", in: "query", description: "Items per page", required: false, schema: new OA\Schema(type: "integer", default: 15))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success paginated posts collection payload",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "data", type: "array", items: new OA\Items(ref: "#/components/schemas/Post")),
                        new OA\Property(property: "meta", type: "object")
                    ]
                )
            )
        ]
    )]
    public function index(Request $request): AnonymousResourceCollection
    {
        $posts = $this->postService->getPaginated($request->all());
        return PostResource::collection($posts);
    }

    #[OA\Get(
        path: "/api/v1/posts/{id}",
        summary: "Get detailed post by its ID",
        operationId: "getPostById",
        tags: ["Posts"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", description: "The ID of the post", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success detailed post payload"
            ),
            new OA\Response(
                response: 404,
                description: "Post not found"
            )
        ]
    )]
    public function show(int $id): PostResource
    {
        $post = $this->postService->findOrFail($id);

        // Define route name context for post detail so resource includes content field
        request()->route()->setUserResolver(fn() => auth()->user());
        return new PostResource($post);
    }

    #[OA\Post(
        path: "/api/v1/posts",
        summary: "Create a new post",
        operationId: "createPost",
        tags: ["Posts"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["title", "content", "status"],
                properties: [
                    new OA\Property(property: "title", type: "string", example: "Laravel 11: New Features"),
                    new OA\Property(property: "content", type: "string", example: "Here is the content details..."),
                    new OA\Property(property: "excerpt", type: "string", example: "Short summary..."),
                    new OA\Property(property: "status", type: "string", example: "draft"),
                    new OA\Property(property: "category_id", type: "integer", example: 1)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Post created successfully"
            )
        ]
    )]
    public function store(StorePostRequest $request): JsonResponse
    {
        $post = $this->postService->create($request->validated());
        return (new PostResource($post))
            ->response()
            ->setStatusCode(201);
    }

    #[OA\Put(
        path: "/api/v1/posts/{id}",
        summary: "Update an existing post",
        operationId: "updatePost",
        tags: ["Posts"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", description: "The ID of the post to update", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "title", type: "string", example: "Updated Laravel Features"),
                    new OA\Property(property: "content", type: "string", example: "Updated content..."),
                    new OA\Property(property: "status", type: "string", example: "published"),
                    new OA\Property(property: "category_id", type: "integer", example: 1)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Post updated successfully"
            ),
            new OA\Response(
                response: 404,
                description: "Post not found"
            )
        ]
    )]
    public function update(UpdatePostRequest $request, int $id): PostResource
    {
        $post = $this->postService->update($id, $request->validated());
        return new PostResource($post);
    }

    #[OA\Delete(
        path: "/api/v1/posts/{id}",
        summary: "Delete a post",
        operationId: "deletePost",
        tags: ["Posts"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", description: "The ID of the post to delete", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: "Post deleted successfully"
            ),
            new OA\Response(
                response: 404,
                description: "Post not found"
            )
        ]
    )]
    public function destroy(int $id): JsonResponse
    {
        $this->postService->delete($id);
        return response()->json(null, 204);
    }
}
