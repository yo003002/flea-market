<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
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
            'address' => ['required'],
            'postal_code' => ['required', 'regex:/^[A-Za-z0-9\-]{8}$/'],
        ];
    }

    public function messages()
    {
        return [
            'address.required' => '住所を入力してください',
            'postal_code.required' => '郵便番号を入力してください',
            'postal_code.regex' => '郵便番号はハイフンありの８文字で入力してください',
        ];
    }
}
