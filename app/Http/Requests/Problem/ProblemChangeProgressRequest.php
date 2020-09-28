<?php

namespace App\Http\Requests\Problem;

use Illuminate\Foundation\Http\FormRequest;

class ProblemChangeProgressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'progress' => 'integer|between:0,100'
        ];
    }

    public function messages()
    {
        return [
            'progress.integer' => 'Прогресс решения может быть только целым числом',
            'progress.between' => 'Прогресс решения задается в диапазоне от 0 до 100',
        ];
    }
}
