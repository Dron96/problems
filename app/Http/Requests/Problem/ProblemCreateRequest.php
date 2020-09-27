<?php

namespace App\Http\Requests\Problem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProblemCreateRequest extends FormRequest
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
            'name' =>
                [
                    'required',
                    'min:6',
                    'max:150',
                    'regex:/^[A-Za-zА-Яа-яёЁ0-9\- ,\.:]+$/u',
                    Rule::unique('problems','name')->whereNull('deleted_at'),
                ],
            'description' => 'min:6|max:350|regex:/^[A-Za-zА-Яа-яёЁ0-9\- ,\.:]+$/u',
            'possible_solution' => 'min:6|max:250|regex:/^[A-Za-zА-Яа-яёЁ0-9\- ,\.:]+$/u',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'name.max' => 'Название проблемы должно быть не более 150 символов',
            'name.min' => 'Название проблемы должно быть не менее 6 символов',
            'name.required' => 'Название проблемы должно быть не менее 6 символов',
            'name.unique' => 'Проблема с таким названием уже существует',
            'name.regex' => 'Для названия доступны только символы кириллицы, латиницы, “.”, “,”, “:”, “ “, “-”, 0-9',

            'description.max' => 'Описание проблемы должно быть не более 350 символов',
            'description.min' => 'Описание проблемы должно быть не менее 6 символов',
            'description.regex' => 'Для поля “Описание решения” доступны только символы кириллицы, латиницы, “.”, “,”, “:”, “ “, “-”, 0-9”',

            'possible_solution.max' => 'Возможное решение должно быть не более 250 символов',
            'possible_solution.min' => 'Возможное решение должно быть не менее 6 символов',
            'possible_solution.regex' => 'Для поля “Возможное решение” доступны только символы кириллицы, латиницы, “.”, “,”, “:”, “ “, “-”, 0-9',
        ];
    }

}
