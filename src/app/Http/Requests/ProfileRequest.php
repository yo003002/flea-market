<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'name' => ['required', 'max:20'],
            'profile_image' => ['mimes:jpeg,png'],
            'postal_code' => ['required', 'regex:/^[A-Za-z0-9\-]{8}$/'],
            'address' => ['required'],

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'お名前を入力してください',
            'name.max' => 'お名前は２０文字以内で入力してください',
            'profile_image.mimes' => 'プロフィール画像は.jpegもしくは.pngにしてください',
            'postal_code.required' => '郵便番号を入力してください',
            'postal_code.regex' => '郵便番号はハイフンありの８文字で入力してください',
            'address.required' => '住所を入力してください',
        ];
    }
}
