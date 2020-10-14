<?php

namespace App\Http\Requests\Problem;

use Illuminate\Foundation\Http\FormRequest;

class ProblemChangeDescriptionRequest extends FormRequest
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
            'description' => 'nullable|min:6|max:350|regex:/^[A-Za-zА-Яа-яёЁ0-9\- _!?""(),\.:\n]+$/u'
        ];
    }

    public function messages()
    {
        return [
            'description.min' => 'Описание проблемы должно быть не менее 6 символов',
            'description.max' => 'Описание проблемы должно быть не более 350 символов',
            'description.regex' => 'Для описания доступны только символы кириллицы, латиницы, “.”, “,”, “:”, “ “, “-”, 0-9, “_”, “!”, “?”, “(“, “)”, кавычки, перенос строки',
        ];
    }
}
