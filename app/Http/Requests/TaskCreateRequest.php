<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskCreateRequest extends FormRequest
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
            'description' => 'required|min:6|max:150|regex:/^[A-Za-zА-Яа-яёЁ0-9\- ,\.:]+$/u',
            'deadline' => 'date|after_or_equal:'.date('Y-m-d'),
            'executor_id' => 'exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'description.required' => 'Описание задачи должно содержать не менее 6 символов',
            'description.min' => 'Описание задачи должно содержать не менее 6 символов',
            'description.max' => 'Описание задачи должно содержать не более 150 символов',
            'description.regex' => 'Для описания решения доступны только символы кириллицы, латиницы, “.”, “,”, “:”, “ “, “-”, 0-9.',
            'executor_id.exists' => 'Такого исполнителя не существует',
            'deadline.date' => 'Формат срока исполнения не верен',
            'deadline.after_or_equal' => 'Срок исполнения не может быть раньше текущей даты',
        ];
    }
}
