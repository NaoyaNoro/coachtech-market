<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Profile;
use App\Http\Requests\ChangeAddressRequest;



class PurchaseController extends Controller
{
    public function purchase(Request $request)
    {
        $product = Product::find($request->item_id);
        $user_id = auth()->id();
        $address=Profile::where('user_id', $user_id)->first();
        return view('purchase', compact('product','address'));
    }

    public function address(Request $request)
    {
        $profile=Profile::where('user_id',$request->user_id)->first();
        $product_id=$request->product_id;
        return view('change_address',compact('profile','product_id'));
    }

    public function change_address(ChangeAddressRequest $request)
    {
        $form=$request->only(['post_code','address','building']);
        $user_id = auth()->id();
        Profile::where('user_id', $user_id)->update($form);
        session(['product_id' => $request->product_id]);
        return redirect('/purchase/' . $request->product_id);
    }

    public function redirect_change_address()
    {
        $user_id = auth()->id();
        $profile = Profile::where('user_id', $user_id)->first();
        $product_id = session('product_id', null);
        return view('change_address', compact('profile','product_id'));
    }
}
