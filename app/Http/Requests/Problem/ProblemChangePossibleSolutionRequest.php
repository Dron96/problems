<?php

namespace App\Http\Requests\Problem;

use Illuminate\Foundation\Http\FormRequest;

class ProblemChangePossibleSolutionRequest extends FormRequest
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
            'possible_solution' => 'nullable|min:6|max:250|regex:/^[A-Za-zА-Яа-яёЁ0-9\- ,\.:]+$/u'
        ];
    }

    public function messages()
    {
        return [
            'possible_solution.min' => 'Возможное решение должно быть не менее 6 символов',
            'possible_solution.max' => 'Возможное решение должно быть не более 250 символов',
            'possible_solution.regex' => 'Для возможного решения доступны только символы кириллицы, латиницы, “.”, “,”, “:”, “ “, “-”, 0-9.',
        ];
    }
}
