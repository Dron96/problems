<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Login extends FormRequest
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
            'email' => 'min:3|max:255|email|required',
            'password' => 'min:8|max:20|required|regex:/^[0-9A-Za-zА-ЯЦцУуШшЩщФфЫыРрЭэЧчТтЬьЮюЪъХхЁа-яё]*$/u',
        ];
    }

    public function messages()
    {
        return [
            'email.min' => 'В поле "Электронная почта" должно быть не менее 3 символов',
            'email.max' => 'Поле "Электронная почта" содержит более 255 символов',
            'email.required' => 'В поле "Электронная почта" должно быть не менее 3 символов',
            'email.email' => 'Почта некорректна. Для адреса электронной почты доступны только символы латиницы, 0-9, @, "_", "-", "."',

            'password.max' => 'Поле "Пароль" содержит более 20 символов',
            'password.min' => 'В поле "Пароль" должно быть не менее 8 символов',
            'password.required' => 'В поле "Пароль" должно быть не менее 8 символов',
            'password.regex' => 'Для пароля доступны только символы кириллицы, латиницы, 0-9.',
        ];
    }
}
