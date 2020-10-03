<?php

namespace App\Http\Requests\Problem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserProblemsFiltrationRequest extends FormRequest
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
            'urgency' => [
                'present',
                Rule::in(['Срочная', 'Обычная', null])
            ],
            'importance' => [
                'present',
                Rule::in(['Важная', 'Обычная', null])
            ],
            'deadline' => [
                'present',
                Rule::in(['Текущий квартал', 'Остальные', null])
            ],
            'status' => [
                'present',
                Rule::in(['На рассмотрении', 'В работе', 'На проверке заказчика', null])
            ],
        ];
    }
}
