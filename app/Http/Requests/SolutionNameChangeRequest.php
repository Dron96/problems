<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SolutionNameChangeRequest extends FormRequest
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
            'name' => [
                Rule::unique('solutions')
                ->where('problem_id', request()->solution->problem_id)
                ->ignore(request()->solution->id),
                'required',
                'min:6',
                'max:100',
                'regex:/^[A-Za-zА-Яа-яёЁ0-9\- ,\.:]+$/u',
            ]
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Такое решение уже существует',
            'name.required' => 'Описание решения должно содержать не менее 6 символов',
            'name.min' => 'Описание решения должно содержать не менее 6 символов',
            'name.max' => 'Описание решения должно содержать не более 100 символов',
            'name.regex' => 'Для описания решения доступны только символы кириллицы, латиницы, “.”, “,”, “:”, “ “, “-”, 0-9.',
        ];
    }
}
