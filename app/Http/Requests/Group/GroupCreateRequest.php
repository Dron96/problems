<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'name' =>
                [
                'required',
                'min:3',
                'max:100',
                'regex:/^[A-Za-zА-Яа-яёЁ0-9\- _!?()"",\.:]+$/u',
                Rule::unique('groups', 'name')->whereNull('deleted_at'),
                ],
            'short_name' =>
                [
                    'min:2',
                    'max:10',
                    'regex:/^[A-Za-zА-Яа-яёЁ0-9\- _!?()"",\.:]+$/u',
                    Rule::unique('groups', 'short_name')->whereNull('deleted_at'),
                ],
            'leader_id' =>
                [
                    'required',
                    'integer',
                    'exists:users,id',
                    Rule::unique('groups', 'leader_id')->whereNull('deleted_at'),
                ]
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Подразделение с таким названием (полностью) существует',
            'name.required' => 'Название подразделения (полностью) должно содержать не менее 3 символов',
            'name.min' => 'Название подразделения (полностью) должно содержать не менее 3 символов',
            'name.max' => 'Название подразделения (полностью) должно содержать не более 100 символов',
            'name.regex' => 'Для названия подразделения (полностью) доступны только символы кириллицы, латиницы, “.”, “,”, “:”, “ “, “-”, 0-9,  “_”, “!”, “?”, “(“, “)”, кавычки.',
            'short_name.unique' => 'Подразделение с таким названием (сокращенно) существует',
            'short_name.min' => 'Название подразделения (сокращенно) должно содержать не менее 2 символов',
            'short_name.max' => 'Название подразделения (сокращенно) должно содержать не более 10 символов',
            'short_name.regex' => 'Для названия подразделения (сокращенно) доступны только символы кириллицы, латиницы, “.”, “,”, “:”, “ “, “-”, 0-9, “_”, “!”, “?”, “(“, “)”, кавычки',
            'leader_id.required' => 'Руководитель для подразделения не выбран',
            'leader_id.exists' => 'Такого пользователя не существует',
            'leader_id.unique' => 'Пользователь уже состоит в другом подразделении',
            'leader_id.integer' => 'ID пользователя долже быть целым числом',
        ];
    }
}
