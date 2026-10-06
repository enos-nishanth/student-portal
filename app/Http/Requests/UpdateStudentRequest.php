<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z\s]+$/'
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($this->student->user_id),
            ],

            'phone' => [
                'required',
                'digits:10',
                Rule::unique('users', 'phone')
                    ->ignore($this->student->user_id),
            ],

            'dob' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(15)->toDateString(),
                'after_or_equal:' . now()->subYears(45)->toDateString(),
            ],

            'gender' => [
                'required',
                'in:male,female,other'
            ],

            'address' => [
                'required',
                'string',
                'max:500'
            ],

            'city' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Za-z\s]+$/'
            ],

            'pincode' => [
                'required',
                'digits:6'
            ],

            'qualification' => [
                'required',
                'in:10th,12th,diploma,ug,pg,phd'
            ],

            'college' => [
                'required',
                'string',
                'max:255'
            ],

            'graduation_year' => [
                'required',
                'integer',
                'digits:4',
                'max:' . now()->year,
            ],

            'skills' => [
                'nullable',
                'string',
                'max:1000'
            ],

            'profile_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],

            'resume' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx',
                'max:5120'
            ],
        ];
    }
}