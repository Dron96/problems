<?php

namespace App\Http\Requests;

use App\Models\Solution;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class SolutionCreateRequest extends FormRequest
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
     * @return array[]|JsonResponse|string[]
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'min:6',
                'max:100',
                'regex:/^[A-Za-zА-Яа-яёЁ0-9\- ,\.:]+$/u',
                Rule::unique('solutions', 'name')
                    ->where('problem_id', request()->problem->id)
                    ->whereNull('deleted_at'),
                ]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Описание решения должно содержать не менее 6 символов',
            'name.min' => 'Описание решения должно содержать не менее 6 символов',
            'name.max' => 'Описание решения должно содержать не более 100 символов',
            'name.regex' => 'Для описания решения доступны только символы кириллицы, латиницы, “.”, “,”, “:”, “ “, “-”, 0-9.',
            'name.unique' => 'Такое решение уже существует'
        ];
    }
}
