<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Profile;

class AddressRequest extends FormRequest
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
        $user=auth()->user();
        $profileExist=Profile::where('user_id',$user->id)->exists();
        return [
            'image' => $profileExist ? 'sometimes|nullable|mimes:png,jpeg' : 'required|mimes:png,jpeg',
            'name' => 'required',
            'post_code' =>
            ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address' => 'required',
            'building' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'image.required' => 'プロフィール写真を入力してください',
            'image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
            'name.required' => 'お名前を入力してください',
            'post_code.required' => '郵便番号を入力してください',
            'post_code.regex' => '郵便番号はハイフンを含めた8文字の形式で入力してください',
            'address.required' => '住所を入力してください',
            'building.required' => '建物名を入力してください'
        ];
    }
}
