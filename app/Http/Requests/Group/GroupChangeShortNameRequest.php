<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class GroupChangeShortNameRequest extends FormRequest
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
            'short_name' => 'required|unique:groups,short_name|min:2|max:10|regex:/^[A-Za-zА-Яа-яёЁ0-9\- ,\.:]+$/u',
        ];
    }

    public function messages()
    {
        return [
            'short_name.unique' => 'Подразделение с таким названием (сокращенно) существует',
            'short_name.required' => 'Название подразделения (сокращенно) должно содержать не менее 2 символов',
            'short_name.min' => 'Название подразделения (сокращенно) должно содержать не менее 2 символов',
            'short_name.max' => 'Название подразделения (сокращенно) должно содержать не более 10 символов',
            'short_name.regex' => 'Для названия подразделения (сокращенно) доступны только символы кириллицы, латиницы, “.”, “,”, “:”, “ “, “-”, 0-9',
        ];
    }
}
