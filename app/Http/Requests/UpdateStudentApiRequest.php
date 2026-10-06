<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentApiRequest extends FormRequest
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
        return [
            'name' => [
                'sometimes',
                'string',
                'max:255',
                'regex:/^[A-Za-z\s]+$/'
            ],

            'email' => [
                'sometimes',
                'email',
                'max:255',
            ],

            'phone' => [
                'sometimes',
                'digits:10',
            ],

            'dob' => [
                'sometimes',
                'date',
                'before_or_equal:' . now()->subYears(15)->toDateString(),
                'after_or_equal:' . now()->subYears(45)->toDateString(),
            ],

            'gender' => [
                'sometimes',
                'in:male,female,other'
            ],

            'address' => [
                'sometimes',
                'string',
                'min:5',
                'max:500'
            ],

            'city' => [
                'sometimes',
                'string',
                'min:2',
                'max:100',
                'regex:/^[A-Za-z\s]+$/'
            ],

            'pincode' => [
                'sometimes',
                'digits:6'
            ],

            'qualification' => [
                'sometimes',
                'in:10th,12th,diploma,ug,pg,phd'
            ],

            'college' => [
                'sometimes',
                'string',
                'min:3',
                'max:255'
            ],

            'graduation_year' => [
                'sometimes',
                'integer',
                'digits:4',
                'max:' . now()->year,
            ],

            'skills' => [
                'sometimes',
                'nullable',
                'string',
                'max:1000'
            ],

            'profile_image' => [
                'sometimes',
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],

            'resume' => [
                'sometimes',
                'nullable',
                'file',
                'mimes:pdf,doc,docx',
                'max:5120'
            ],
        ];
    }
}
