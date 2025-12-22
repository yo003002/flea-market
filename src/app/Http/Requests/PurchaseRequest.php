<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
            'pay_method' => ['required'],

        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = auth()->user();

            if (!$user->address) {
                $validator->errors()->add('address', '配送先が登録されていません');
            }
        });
    }

    public function messages()
    {
        return [
            'pay_method.required' => '支払い方法を選択してください',
        ];
    }
}
