<?php

namespace App\Http\Requests\Lectures;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLectureRequest extends FormRequest
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
        $lectureId = $this->route('lecture');

        return [
            'title' => 'required|string|max:255|unique:lectures,title,' . $lectureId,
            'description' => 'required|string',
        ];
    }
}
