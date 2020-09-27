<?php

namespace App\Http\Requests\Problem;

use Illuminate\Foundation\Http\FormRequest;

class ProblemChangeExperienceRequest extends FormRequest
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
            'experience' => 'min:6|max:350|regex:/^[A-Za-zА-Яа-яёЁ0-9\- ,\.:]+$/u'
        ];
    }

    public function messages()
    {
        return [
            'experience.min' => 'Поле “Опыт” должно содержать не менее 6 символов',
            'experience.max' => 'Поле “Опыт” должно содержать не более 350 символов',
            'experience.regex' => 'Для поля “Опыт” доступны только символы кириллицы, латиницы, “.”, “,”, “:”, “ “, “-”, 0-9.',
        ];
    }
}
