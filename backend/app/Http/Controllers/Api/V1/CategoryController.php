<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Categories", description: "Endpoints for managing categories")]
class CategoryController extends Controller
{
    #[OA\Get(
        path: "/api/v1/categories",
        summary: "List all categories",
        tags: ["Categories"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of categories",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(ref: "#/components/schemas/Category")
                        )
                    ]
                )
            )
        ]
    )]
    public function index(): AnonymousResourceCollection
    {
        return CategoryResource::collection(Category::orderBy('name')->get());
    }

    #[OA\Post(
        path: "/api/v1/categories",
        summary: "Create a new category",
        tags: ["Categories"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/StoreCategoryRequest")
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Category created",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "data", ref: "#/components/schemas/Category")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validation Error"),
            new OA\Response(response: 403, description: "Access denied")
        ]
    )]
    public function store(StoreCategoryRequest $request): CategoryResource
    {
        $category = Category::create($request->validated());
        return new CategoryResource($category);
    }

    #[OA\Get(
        path: "/api/v1/categories/{id}",
        summary: "Get a single category",
        tags: ["Categories"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "Category ID",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Category details",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "data", ref: "#/components/schemas/Category")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Category not found")
        ]
    )]
    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category);
    }

    #[OA\Put(
        path: "/api/v1/categories/{id}",
        summary: "Update a category",
        tags: ["Categories"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "Category ID",
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/UpdateCategoryRequest")
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Category updated",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "data", ref: "#/components/schemas/Category")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Category not found"),
            new OA\Response(response: 422, description: "Validation Error"),
            new OA\Response(response: 403, description: "Access denied")
        ]
    )]
    public function update(UpdateCategoryRequest $request, Category $category): CategoryResource
    {
        $category->update($request->validated());
        return new CategoryResource($category);
    }

    #[OA\Delete(
        path: "/api/v1/categories/{id}",
        summary: "Delete a category",
        tags: ["Categories"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "Category ID",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 204, description: "Category deleted successfully"),
            new OA\Response(response: 404, description: "Category not found"),
            new OA\Response(response: 403, description: "Access denied")
        ]
    )]
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->noContent();
    }
}
