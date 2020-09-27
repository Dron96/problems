<?php

namespace App\Http\Requests\Solution;

use Illuminate\Foundation\Http\FormRequest;

class SolutionChangePlanRequest extends FormRequest
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
            'plan' => [
                'nullable',
                'min:6',
                'max:350',
                'regex:/^[A-Za-zА-Яа-яёЁ0-9\- ,\.:]+$/u',
            ]
        ];
    }

    public function messages()
    {
        return [
            'plan.min' => 'Поле “План решения” должно содержать не менее 6 символов',
            'plan.max' => 'Поле “План решения” должно содержать не более 350 символов',
            'plan.regex' => 'Для поля “План решения” доступны только символы кириллицы, латиницы, “.”, “,”, “:”, “ “, “-”, 0-9.',
        ];
    }
}
