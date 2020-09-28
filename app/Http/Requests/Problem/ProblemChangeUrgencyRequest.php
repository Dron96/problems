<?php

namespace App\Http\Requests\Problem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProblemChangeUrgencyRequest extends FormRequest
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
            'urgency' =>
                [
                    Rule::in(['Срочная', 'Обычная'])
                ]
        ];
    }

    public function messages()
    {
        return [
            'urgency.in' => 'Срочность может быть только обычной или срочной.',
        ];
    }
}
