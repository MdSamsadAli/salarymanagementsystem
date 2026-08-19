<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
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
        $news = $this->route('news'); // for update (route model binding)

        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'required',
                'string',
            ],
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],
            'image' => [
                $news ? 'nullable' : 'required', // required on create, optional on update
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048', // 2MB
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'News title is required.',
            'title.max'            => 'Title may not be greater than 255 characters.',
            'description.required' => 'Description is required.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists'   => 'Selected category does not exist.',
            'image.required'       => 'Please upload an image.',
            'image.image'          => 'The file must be an image.',
            'image.mimes'          => 'Only jpg, jpeg, png, webp images are allowed.',
            'image.max'            => 'Image size must not exceed 2MB.',
        ];
    }
}
