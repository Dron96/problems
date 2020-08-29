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
            'name' => 'exists:users,name|required|min:4|max:20|regex:/^[0-9A-Za-zА-ЯЦцУуШшЩщФфЫыРрЭэЧчТтЬьЮюЪъХхЁа-яё]*$/u',
            'password' => 'min:8|max:20|required|regex:/^[0-9A-Za-zА-ЯЦцУуШшЩщФфЫыРрЭэЧчТтЬьЮюЪъХхЁа-яё]*$/u',
        ];
    }

    public function messages()
    {
        return [
            'name.max' => 'Поле "Имя пользователя" содержит более 20 символов',
            'name.min' => 'В поле "Имя пользователя" должно быть не менее 4 символов',
            'name.required' => 'В поле "Имя пользователя" должно быть не менее 4 символов',
            'name.exists' => 'Имя пользователя или пароль неправильные',
            'name.regex' => 'Для имени пользователя доступны только символы кириллицы, латиницы, 0-9.',

            'password.max' => 'Поле "Пароль" содержит более 20 символов',
            'password.min' => 'В поле "Пароль" должно быть не менее 8 символов',
            'password.required' => 'В поле "Пароль" должно быть не менее 8 символов',
            'password.regex' => 'Для пароля доступны только символы кириллицы, латиницы, 0-9.',
        ];
    }
}
