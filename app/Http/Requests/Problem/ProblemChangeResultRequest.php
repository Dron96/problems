<?php

namespace App\Http\Requests\Problem;

use Illuminate\Foundation\Http\FormRequest;

class ProblemChangeResultRequest extends FormRequest
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
            'result' => 'nullable|min:6|max:350|regex:/^[A-Za-zА-Яа-яёЁ0-9\- ,\.:\n]+$/u'
        ];
    }

    public function messages()
    {
        return [
            'result.min' => 'Поле “Результат” должно содержать не менее 6 символов',
            'result.max' => 'Поле “Результат” должно содержать не более 350 символов',
            'result.regex' => 'Для поля “Результат” доступны только символы кириллицы, латиницы, “.”, “,”, “:”, “ “, “-”, 0-9.',
        ];
    }
}
