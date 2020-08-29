<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Register extends FormRequest
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
            'name' => 'unique:users|required|min:4|max:20|regex:/^[0-9A-Za-zА-ЯЦцУуШшЩщФфЫыРрЭэЧчТтЬьЮюЪъХхЁа-яё]*$/u',
            'password' => 'min:8|max:20|confirmed|required|regex:/^[0-9A-Za-zА-ЯЦцУуШшЩщФфЫыРрЭэЧчТтЬьЮюЪъХхЁа-яё]*$/u',
            'email' => 'min:3|max:256|email|required|unique:users'
        ];
    }

    public function messages()
    {
        return [
            'name.max' => 'Поле "Имя пользователя" содержит более 20 символов',
            'name.min' => 'В поле "Имя пользователя" должно быть не менее 4 символов',
            'name.required' => 'В поле "Имя пользователя" должно быть не менее 4 символов',
            'name.unique' => 'Такое имя пользователя уже используется',
            'name.regex' => 'Для имени пользователя доступны только символы кириллицы, латиницы, 0-9.',

            'password.max' => 'Поле "Пароль" содержит более 20 символов',
            'password.min' => 'В поле "Пароль" должно быть не менее 8 символов',
            'password.required' => 'В поле "Пароль" должно быть не менее 8 символов',
            'password.confirmed' => 'Введенные пароли не совпадают между собой',
            'password.regex' => 'Для пароля доступны только символы кириллицы, латиницы, 0-9.',

            'email.min' => 'В поле "Электронная почта" должно быть не менее 3 символов',
            'email.max' => 'Поле "Электронная почта" содержит более 256 символов',
            'email.required' => 'В поле "Электронная почта" должно быть не менее 3 символов',
            'email.unique' => 'Такой адрес электронной почты уже используется',
            'email.email' => 'Почта некорректна. Для адреса электронной почты доступны только символы латиницы, 0-9, @, "_", "-", "."',
        ];
    }
}
