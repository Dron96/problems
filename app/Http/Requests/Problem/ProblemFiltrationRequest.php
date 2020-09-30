<?php

namespace App\Http\Requests\Problem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProblemFiltrationRequest extends FormRequest
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
                'nullable',
                Rule::in(['Срочная', null])
            ],
            'importance' => [
                'nullable',
                Rule::in(['Важная', null])
            ],
            'deadline' => [
                'nullable',
                Rule::in(['Now', 'Not now', null])
            ],
            'status' => [
                'nullable',
                Rule::in(['На рассмотрении', 'В работе', 'На проверке заказчика', null])
            ],
        ];
    }
}
