<?php

namespace App\Http\Requests\Solution;

use Illuminate\Foundation\Http\FormRequest;

class SolutionChangeTeamRequest extends FormRequest
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
            'team' => [
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
            'team.min' => 'Поле “Команда” должно содержать не менее 6 символов',
            'team.max' => 'Поле “Команда” должно содержать не более 350 символов',
            'team.regex' => 'Для поля “Команда” доступны только символы кириллицы, латиницы, “.”, “,”, “:”, “ “, “-”, 0-9.',
        ];
    }
}
