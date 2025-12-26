<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Address;
use App\Models\Category;
use App\Models\ItemImage;
use App\Models\ItemCategory;
use App\Models\Like;
use App\Models\Mylist;
use App\Models\Purchase;


class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $tab = $request->tab;

        //ログイン→オススメは自分以外の商品表示
        //未ログイン→全商品表示
        $recommendedQuery = Item::with('images', 'latestComment.user')
            ->when(auth()->check(), function ($query) {
                $query->where('user_id', '!=', auth()->id());
            });

        //検索があれば追加
        if (!empty($keyword)) {
            $recommendedQuery->where('title', 'like', '%' . $keyword. '%');
        }

        $recommendedItems = $recommendedQuery
            ->latest()
            ->get();

        //マイリスト（いいねした商品）
        $mylistItems = collect();

        if ($tab === 'mylist' && auth()->check()) {
            $mylistQuery = auth()->user()
                ->likedItems()
                ->wherePivot('is_favorite', true)
                ->with('images');

            if (!empty($keyword)) {
                $mylistQuery->where('title', 'like', '%' . $keyword . '%');
            }
        
            $mylistItems = $mylistQuery->get();
        }

        return view('items.index', compact('recommendedItems', 'mylistItems', 'keyword'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExhibitionRequest $request)
    {

        
        $price = preg_replace('/[^\d]/', '', $request->price);


        //商品情報の保存
        $item = new Item();
        $item->user_id = auth()->id();
        $item->title = $request->title;
        $item->brand_name = $request->brand_name ?? null;
        $item->description = $request->description;
        $item->condition = $request->condition;
        $item->price = $request->price;
        $item->status = 'selling';
        $item->save();



        
        if (!empty($request->category_ids)) {
            $item->categories()->attach($request->category_ids);
        }

        //複数画像の保存
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                
                $path = $image->store('item_images', 'public');

                ItemImage::create([
                    'item_id' => $item->id,
                    'image_path' => $path,
                ]);
            }

        }

        return redirect('/mypage');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($item_id)
    {
        //
        $item = Item::with([
            'images',
            'categories',
            'likes',
            'comments',
            'latestComment.user'
        ])->findOrFail($item_id);

        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
