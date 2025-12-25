<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
    // protected function prepareForValidation()
    // {
    //     $this->merge([
    //         'price' => preg_replace('/[^\d]/', '', $this->price),
    //     ]);
    // }

    protected function prepareForValidation()
    {
        $this->merge([
        'price' => preg_replace('/[￥,]/u', '', $this->price),
    ]);
    }

    public function rules()
    {
        return [
            //
            'title' => ['required'],
            'description' => ['required', 'max:255'],
            'images' => ['required'],
            'images.*' => ['mimes:jpeg,png'],
            'category_ids' => ['required', 'array', 'min:1'],
            'category_ids.*' => ['exists:categories,id'],
            'condition' => ['required'],
            'price' => ['required', 'regex:/^\d+$/', 'min:0'],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '商品名を入力してください',
            'description.required' => '商品の説明を入力してください',
            'description.max' => '商品の説明は２５５字文字以内で入力してください',
            'images.required' => '商品の画像をアップロードしてください',
            'images.*.mimes' => '商品の画像は.jpegもしくは.pngにしてください',
            'category_ids.required' => '商品のカテゴリーを選択してください',
            'category_ids.min' => '商品のカテゴリーを選択してください',
            'condition.required' => '商品の状態を選択してください',
            'price.required' => '商品の金額を入力してください',
            'price.regex' => '商品の金額は半角数値で入力してください',
            'price.min' => '商品の金額は０円以上を設定してください',
        ];
    }
}
