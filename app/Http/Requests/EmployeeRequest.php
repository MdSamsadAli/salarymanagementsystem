<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
        $employee = $this->route('employee'); // for update (route model binding)

        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'address' => [
                'nullable',
                'string',
                'max:255',
            ],
            'designation' => [
                'nullable',
                'string',
                'max:255',
            ],
            'date_of_joining' => [
                'required',
                'date',
                'before_or_equal:today', // optional: cannot be future date
            ],

            // Common extra fields (uncomment / add if you have them)
            // 'email' => [
            //     'required',
            //     'email',
            //     'max:255',
            //     Rule::unique('employees', 'email')->ignore($employee?->id),
            // ],
            // 'phone' => [
            //     'nullable',
            //     'string',
            //     'max:20',
            // ],
            // 'salary' => [
            //     'nullable',
            //     'numeric',
            //     'min:0',
            // ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'             => 'Employee name is required.',
            'name.max'                  => 'Name may not be greater than 255 characters.',
            'date_of_joining.required'  => 'Date of joining is required.',
            'date_of_joining.date'      => 'Please enter a valid date.',
            'date_of_joining.before_or_equal' => 'Date of joining cannot be in the future.',
        ];
    }
}
