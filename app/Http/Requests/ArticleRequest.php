<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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
            'product_name' => 'required | max:255',
            'price' => 'required | max:255',
            'stock' => 'required | max:255',
            'comment' => 'max:10000',
        ];
    }

    public function attributes()
    {
        return [
            'product_name' => '商品名',
            'price' => '価格',
            'stock' => '在庫数',
            'comment' => 'コメント',
        ];
    }

    public function messages() {
        return [
            'product_name.required' => ':attributeは必須項目です。',
            'price.required' => ':attributeは必須項目です。',
            'price.max' => ':attributeは:max字以内で入力してください',
            'stock.required' => ':attributeは必須項目です。',
            'stock.max' => ':attributeは:max字以内で入力してください',
            'comment.max' => ':attributeは:max字以内で入力してください。',
           

        ];
    }
}
