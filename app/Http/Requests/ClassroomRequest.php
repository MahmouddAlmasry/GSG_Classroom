<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassroomRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'room' => 'nullable|string|max:255',
            'cover_image' => [
                'nullable',
                'image',
                // Rule::dimensions([
                //     'min_width' => 600,
                //     'min_height' => 300,
                // ]),
            ],
        ];
    }

    public function messages()
    {
        return [
            'required'=>"The :attribute field is required.",
            "unique"=>":Attribute already exists",
        ];
    }
}
