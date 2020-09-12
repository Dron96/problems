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
            'name' => 'required|min:1|max:25|regex:/^[А-ЯЦцУуШшЩщФфЫыРрЭэЧчТтЬьЮюЪъХхЁа-яё-]*$/u',
            'password' => 'min:8|max:20|confirmed|required|regex:/^[0-9A-Za-zА-ЯЦцУуШшЩщФфЫыРрЭэЧчТтЬьЮюЪъХхЁа-яё]*$/u',
            'email' => 'min:3|max:255|email|required|unique:users',
            'father_name' => 'nullable|max:25|regex:/^[А-ЯЦцУуШшЩщФфЫыРрЭэЧчТтЬьЮюЪъХхЁа-яё-]*$/u',
            'surname' => 'min:1|required|max:25|regex:/^[А-ЯЦцУуШшЩщФфЫыРрЭэЧчТтЬьЮюЪъХхЁа-яё-]*$/u',
        ];
    }

    public function messages()
    {
        return [
            'name.max' => 'Поле "Имя" содержит более 25 символов.',
            'name.min' => 'Поля "Имя" должно содержать не менее 1 символа.',
            'name.required' => 'Поля "Имя" должно содержать не менее 1 символа.',
            'name.regex' => 'Для имени доступны только символы кириллицы, "-".',

            'surname.max' => 'Поле "Фамилия" содержит более 25 символов.',
            'surname.min' => 'Поля "Фамилия" должно содержать не менее 1 символа.',
            'surname.required' => 'Поля "Фамилия" должно содержать не менее 1 символа.',
            'surname.regex' => 'Для фамилии доступны только символы кириллицы, "-".',

            'father_name.max' => 'Поле "Отчество" содержит более 25 символов.',
            'father_name.regex' => 'Для отчества доступны только символы кириллицы, "-".',

            'password.max' => 'Поле "Пароль" содержит более 20 символов',
            'password.min' => 'В поле "Пароль" должно быть не менее 8 символов',
            'password.required' => 'В поле "Пароль" должно быть не менее 8 символов',
            'password.confirmed' => 'Введенные пароли не совпадают между собой',
            'password.regex' => 'Для пароля доступны только символы кириллицы, латиницы, 0-9.',

            'email.min' => 'В поле "Электронная почта" должно быть не менее 3 символов',
            'email.max' => 'Поле "Электронная почта" содержит более 255 символов',
            'email.required' => 'В поле "Электронная почта" должно быть не менее 3 символов',
            'email.unique' => 'Такой адрес электронной почты уже используется',
            'email.email' => 'Почта некорректна. Для адреса электронной почты доступны только символы латиницы, 0-9, @, "_", "-", "."',
        ];
    }
}
