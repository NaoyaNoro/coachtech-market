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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'description' => 'required|max:255',
            'image' => 'required|mimes:jpeg,png',
            'brand' => 'required',
            'category'=>'required',
            'status'=>'required',
            'price'=>'required|numeric|min:0,'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'description.required' => '商品の説明を入力してください',
            'description.max' => '255文字以内で入力してください',
            'image.required'=>'商品画像を選択してください',
            'image.mimes'=>'ファイルの拡張子は.jpegもしくは.pngにしてください',
            'brand.required' => 'ブランド名を入力してください',
            'category.required'=>'カテゴリーを選択してください',
            'status.required'=>'商品の状態を選択してください',
            'price.required'=>'商品の価格を入力してください',
            'price.numeric' => '数字を入力してください',
            'price.min' => '0円以上を入力してください',
        ];
    }
}
