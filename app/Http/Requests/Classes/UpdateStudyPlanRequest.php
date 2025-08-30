<?php

namespace App\Http\Requests\Classes;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudyPlanRequest extends FormRequest
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
            'lectures' => 'required|array',
            'lectures.*.id' => 'required|integer|exists:lectures,id',
            'lectures.*.order' => 'required|integer|min:0',
            'lectures.*.completed' => 'required|boolean',
        ];
    }
}
