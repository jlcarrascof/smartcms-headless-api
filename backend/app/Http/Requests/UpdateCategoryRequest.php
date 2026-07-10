<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateCategoryRequest",
 *     @OA\Property(property="name", type="string", example="Technology Updated", description="The name of the category"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Updated description", description="Optional description")
 * )
 */
class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255|unique:categories,name,' . $this->route('category')->id,
            'description' => 'nullable|string',
        ];
    }
}
