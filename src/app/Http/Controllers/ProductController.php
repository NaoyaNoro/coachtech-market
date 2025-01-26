<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\MyList;
use App\Models\Comment;





class ProductController extends Controller
{
    public function search(Request $request)
    {
        $search_name = $request->name;

        // 検索結果を取得
        $search_results = Product::where('name', 'LIKE', "%{$search_name}%")->get();

        // 検索条件をセッションに保存
        session(['search_name' => $search_name, 'search_results' => $search_results]);

        return redirect('/'); // トップページで検索結果を表示
    }

    public function index(Request $request)
    {
        $page = $request->query('page', null);

        // 検索条件をセッションから取得
        $search_name = session('search_name', null);
        $search_results = session('search_results', collect());


        if ($page === 'mylist') {
            if (auth()->check()) {
                // マイリスト取得 (検索条件に一致する商品で絞り込む)
                /*
                $mylistProducts = MyList::where('user_id', auth()->id())
                ->with('product')
                ->get()
                ->pluck('product'); // マイリストの商品コレクション

                $searchResults = Product::where('name', 'LIKE', "%{$search_name}%")->get(); // 検索結果の商品コレクション

                // マイリストに登録されている商品と検索結果を連結（共通する商品を抽出）
                $products = $mylistProducts->intersect($searchResults);
                */

                $products = Product::whereHas('mylistBy', function ($query) {
                    $query->where('user_id', auth()->id());
                })
                    ->when($search_name, function ($query, $search_name) {
                        $query->where('name', 'LIKE', "%{$search_name}%");
                    })
                    ->get();

            } else {
                $products = collect();
            }
        } elseif ($search_name) {
            // 検索結果を表示
            $products = $search_results;
        } else {
            // 通常のおすすめ商品を表示
            $products = Product::select('id','name', 'image')->get();
        }
        return view('index', compact('products', 'page', 'search_name'));
    }

    public function detail(Request $request)
    {
        $product=Product::with(['categories','status'])->find($request->item_id);
        $comments=Comment::where('product_id', $request->item_id)->with(['profile','users'])->get();
        $mylists=MyList::where('product_id', $request->item_id)->get();

        // 現在のユーザーがお気に入り登録済みかを判定
        $isFavorited = MyList::where('user_id', auth()->id())
        ->where('product_id', $product->id)
        ->exists();

        $isCommented=Comment::where('user_id', auth()->id())
        ->where('product_id', $product->id)
        ->exists();

        return view('detail',compact('product','comments','mylists','isFavorited', 'isCommented'));
    }

    
    public function comment(Request $request)
    {
        $request->validate([
            'comment' => 'required|max:255',
        ], [
            'comment.required' => 'コメントを入力してください',
            'comment.max' => '255文字以内で入力してください',
        ]);
        $user_id=auth()->id();
        $comment =[
            'user_id'=>$user_id,
            'product_id'=>$request->product_id,
            'comment'=>$request->comment,
        ];
        Comment::create($comment);
        return redirect('/item/' . $comment['product_id']);
    }


    
    public function good(Request $request)
    {
        $user_id = auth()->id();
        $favorite =[
            'user_id'=>$user_id,
            'product_id'=>$request->product_id
        ];
        $checked=MyList::where('user_id',$user_id)->where('product_id',$request->product_id)->first();

        if($checked){
            $checked->delete();
        }else{
            MyList::create($favorite);
        }
        return redirect('/item/' . $favorite['product_id']);
    }
}
