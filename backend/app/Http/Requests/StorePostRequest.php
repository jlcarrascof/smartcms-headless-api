<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check(); // Only logged-in users can create posts
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'          => 'required|string|max:255',
            'content'        => 'required|string',
            'excerpt'        => 'nullable|string|max:500',
            'status'         => 'required|in:draft,published,archived',
            'category_id'    => 'nullable|exists:categories,id',
            'slug'           => 'nullable|string|unique:posts,slug',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }
}
