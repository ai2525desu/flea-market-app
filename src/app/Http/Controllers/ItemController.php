<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Item;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    // 商品一覧画面
    public function index(Request $request)
    {
        $tab = $request->query('tab');
        $keyword = $request->query('item_name');

        if ($tab === 'mylist') {
            if (Auth::check()) {
                $user = Auth::user()->load('likes.item.purchase');
                $likedItemIds = $user->likes->pluck('item_id');
                $items = Item::whereIn('id', $likedItemIds)
                    ->ItemNameSearch($keyword)
                    ->get();
            } else {
                $items = collect();
            }
        } else {
            $query = Item::with('user', 'likes', 'purchase');
            if (Auth::check()) {
                $query->where('user_id', '!=', Auth::id());
            }
            $items = $query->ItemNameSearch($keyword)->get();
            $tab = '';
        }
        return view('items.index', compact('items', 'tab'));
    }

    // 商品詳細画面
    public function detail($item_id)
    {
        $user = Auth::user();
        $item = Item::with('categories', 'likes', 'comments')->findOrFail($item_id);
        $condition = Item::CONDITION[$item->condition];
        $hasPurchase = $item->purchase()->exists();
        return view('items.detail', compact('user', 'item', 'condition', 'hasPurchase'));
    }

    // いいね機能
    public function like($item_id)
    {

        $user = Auth::user();
        $like = LIke::where('user_id', $user->id)->where('item_id', $item_id)->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => $user->id,
                'item_id' => $item_id
            ]);
        }

        return back();
    }

    // コメント機能
    public function comment(CommentRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        Comment::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'comment_content' => $request->comment_content
        ]);

        return back();
    }

    // 商品出品画面
    public function showExhibition()
    {
        $user = Auth();
        $categories = Category::all();
        $conditions = Item::CONDITION;
        return view('items.exhibition', compact('user', 'categories', 'conditions'));
    }

    // 出品機能
    public function storeExhibition(ExhibitionRequest $request)
    {
        if ($request->hasFile('item_image')) {
            $imagePath = $request->file('item_image')->store('items', 'public');
        } else {
            $imagePath = null;
        }

        $user = Auth::user();
        $item = Item::create([
            'user_id' => $user->id,
            'item_name' => $request->input('item_name'),
            'item_image' => $imagePath,
            'brand' => $request->input('brand'),
            'price' => $request->input('price'),
            'description' => $request->input('description'),
            'condition' => $request->input('condition')
        ]);

        if ($request->has('categories')) {
            $item->categories()->attach($request->input('categories'));
        }
        return redirect(route('items.exhibition'))->with('message', '商品が出品されました');
    }
}
