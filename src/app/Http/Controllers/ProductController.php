<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\MyList;
use App\Models\Comment;
use App\Models\Purchase;
use App\Models\Sell;
use App\Http\Requests\CommentRequest;





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

    /*
    public function index(Request $request)
    {
        $page = $request->query('page', null);

        // 検索条件をセッションから取得
        $search_name = session('search_name', null);
        $search_results = session('search_results', collect());


        if ($page === 'mylist') {
            if (auth()->check()) {
                

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
        $soldOutProductIDs=Purchase::pluck('product_id')->toArray();
        return view('index', compact('products', 'page', 'search_name', 'soldOutProductIDs'));
    }
        */

    public function index(Request $request)
    {
        $page = $request->query('page', null);

        // 検索条件をセッションから取得
        $search_name = session('search_name', null);
        $search_results = session('search_results', collect());

        // 自分が出品した商品のIDを取得
        $user_id = auth()->id();
        $myProductIds = Sell::where('user_id', $user_id)->pluck('product_id')->toArray();

        if ($page === 'mylist') {
            if (auth()->check()) {
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
            // 検索結果を表示（検索結果にも自分が出品した商品を含めない）
            $products = $search_results->whereNotIn('id', $myProductIds);
        } else {
            // 通常のおすすめ商品を表示（自分が出品した商品を除外）
            $products = Product::whereNotIn('id', $myProductIds)
                ->select('id', 'name', 'image')
                ->get();
        }

        // 売り切れた商品のIDを取得
        $soldOutProductIDs = Purchase::pluck('product_id')->toArray();

        return view('index', compact('products', 'page', 'search_name', 'soldOutProductIDs'));
    }

    public function detail(Request $request)
    {
        $product=Product::with(['categories'])->find($request->item_id);
        $comments=Comment::where('product_id', $request->item_id)->with(['profile','users'])->get();
        $mylists=MyList::where('product_id', $request->item_id)->get();

        // 現在のユーザーがお気に入り登録済みかを判定
        $isFavorited = MyList::where('user_id', auth()->id())
        ->where('product_id', $product->id)
        ->exists();

        $isCommented=Comment::where('user_id', auth()->id())
        ->where('product_id', $product->id)
        ->exists();

        $soldOutProductIDs = Purchase::pluck('product_id')->toArray();

        return view('detail',compact('product','comments','mylists','isFavorited', 'isCommented','soldOutProductIDs'));
    }

    public function comment(CommentRequest $request)
    {
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
