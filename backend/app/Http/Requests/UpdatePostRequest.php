<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check(); // Only logged-in users can update posts
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Get the current post ID from the route path to ignore its own slug during unique checks
        $postId = $this->route('id');

        return [
            'title'       => 'sometimes|required|string|max:255',
            'content'     => 'sometimes|required|string',
            'excerpt'     => 'nullable|string|max:500',
            'status'      => 'sometimes|required|in:draft,published,archived',
            'category_id' => 'nullable|exists:categories,id',
            'slug'        => 'nullable|string|unique:posts,slug,' . $postId,
        ];
    }
}
