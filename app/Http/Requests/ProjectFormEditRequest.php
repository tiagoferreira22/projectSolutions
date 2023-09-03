<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectFormEditRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'client_name' => [
                'required',
                'max:255',
                'min:3'
            ],
            'title' => [
                'required',
                'max:255',
                'min:3'
            ],
            'description' => [
                'required',
                'min:3'

            ],
            'observation' => [],
            'project_link' => [],
            'pdf_file' => [
                'nullable',
                'mimes:pdf',
                'max:2048'
            ],
            'photo_file' => [
                'nullable',
                'array',
                'max:6', //quantas imgs podem ser enviadas
            ],
        ];
        return $rules;
    }
}
