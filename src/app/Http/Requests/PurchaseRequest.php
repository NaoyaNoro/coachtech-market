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
     * @return array
     */
    public function rules()
    {
        return [
            'purchase__method'=> 'required|in:コンビニ払い,カード支払い'
        ];
    }

    public function messages()
    {
        return[
            'purchase__method.required'=>'支払い方法を選択してください',
            'purchase__method.in'=>'無効な支払い方法です'
        ];
    }
}
