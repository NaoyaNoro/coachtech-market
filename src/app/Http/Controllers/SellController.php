<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\CategoryProduct;
use App\Models\Sell;
use App\Http\Requests\ExhibitionRequest;

class SellController extends Controller
{

    public function sell(Request $request)
    {
        $categories=Category::all();
        return view('sell',compact('categories'));
    }

    public function put_up(ExhibitionRequest $request)
    {
        if ($request->hasFile('image')) {
            // 保存先のディレクトリを指定
            $destinationPath = storage_path('app/public/img/product');

            // オリジナルのファイル名を取得
            $filename = $request->file('image')->getClientOriginalName();

            // ファイルを保存
            $request->file('image')->move($destinationPath, $filename);
        } else {
            // 元の画像名を保持または空にする（デフォルト）
            $filename = null;
        }
        $product=Product::Create(
            [
                'image' => $filename, // ファイル名のみ保存
                'name' => $request->name,
                'brand' => $request->brand,
                'price' => $request->price,
                'description' => $request->description,
                'status' => $request->status,
                'color'=>$request->color
            ]
        );
        $categories=$request->category;
        foreach($categories as $category){
            CategoryProduct::create([
                'product_id'=> $product->id,
                'category_id'=>$category,
            ]);
        }
        $sell=[
            'product_id'=> $product->id,
            'user_id' => auth()->id()
        ];
        Sell::create($sell);
        return redirect('/');
    }
}
