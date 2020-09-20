<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupCreateRequest extends FormRequest
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
            'name' => 'unique:groups,name|required|min:3|max:100|regex:/^[A-Za-zА-Яа-яёЁ0-9\- ,\.:]+$/u',
            'short_name' => 'unique:groups,short_name|min:2|max:10|regex:/^[A-Za-zА-Яа-яёЁ0-9\- ,\.:]+$/u',
            'leader_id' => 'unique:groups,leader_id|required|exists:users,id'
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Подразделение с таким названием (полностью) существует',
            'name.required' => 'Название подразделения (полностью) должно содержать не менее 3 символов',
            'name.min' => 'Название подразделения (полностью) должно содержать не менее 3 символов',
            'name.max' => 'Название подразделения (полностью) должно содержать не более 100 символов',
            'name.regex' => 'Для названия подразделения (полностью) доступны только символы кириллицы, латиницы, “.”, “,”, “:”, “ “, “-”, 0-9',
            'short_name.unique' => 'Подразделение с таким названием (сокращенно) существует',
            'short_name.min' => 'Название подразделения (сокращенно) должно содержать не менее 2 символов',
            'short_name.max' => 'Название подразделения (сокращенно) должно содержать не более 10 символов',
            'short_name.regex' => 'Для названия подразделения (сокращенно) доступны только символы кириллицы, латиницы, “.”, “,”, “:”, “ “, “-”, 0-9',
            'leader_id.required' => 'Руководитель для подразделения не выбран',
            'leader_id.exists' => 'Такого пользователя не существует',
            'leader_id.unique' => 'Пользователь уже состоит в другом подразделении',
        ];
    }
}
