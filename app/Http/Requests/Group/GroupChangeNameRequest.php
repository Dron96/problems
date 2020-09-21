<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class GroupChangeNameRequest extends FormRequest
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
        ];
    }
}
