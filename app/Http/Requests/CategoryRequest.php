<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $category = $this->route('category');

        return [
            'category' => [
                'required',
                'string',
                'max:255',
            ],

            'parent_id' => [
                'nullable',
                'integer',
                'exists:categories,id',
                function ($attribute, $value, $fail) use ($category) {
                    if (!$value || !$category) {
                        return; // create or no parent selected → OK
                    }

                    // Cannot be its own parent
                    if ((int) $value === (int) $category->id) {
                        $fail('A category cannot be its own parent.');
                        return;
                    }

                    // Cannot become a child of one of its own descendants
                    if (in_array((int) $value, $category->descendantIds())) {
                        $fail('A category cannot become a child of its own subcategory.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'category.required' => 'Category name is required.',
            'category.max'      => 'Category name may not be greater than 255 characters.',
            'parent_id.exists'  => 'Selected parent category does not exist.',
        ];
    }
}
