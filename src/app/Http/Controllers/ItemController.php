<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemImage;


class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //

        if ($request->query('tab') === 'mylist') {

            if (!auth()->check()) {
                return redirect()->login();
            }

            $items = Item::with('images')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

            return view('items.mylist', compact('items'));
        }
        
        $items = Item::with('images')->latest()->get();
        return view('items.index', compact('items'));
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
    public function store(Request $request)
    {

        $request->merge([
            'price' => preg_replace('/[^\d]/', '', $request->price),
        ]);



        //後で消す
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'brand_name' => 'nullable|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer|min:0',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'condition' => 'required|string',
        ]);


        //商品情報の保存
        $item = new Item();
        $item->user_id = auth()->id();
        $item->title = $validated['title'];
        $item->brand_name = $validated['brand_name'] ?? null;
        $item->description = $validated['description'];
        $item->condition = $validated['condition'];
        $item->price = $validated['price'];
        $item->status = 'selling';
        $item->save();


        // //カテゴリ（複数）保存
        // if ($request->has('category_ids')) {
        //     $item->categories()->attach($request->category_ids);
        // }
        
        if (!empty($validated['category_ids'])) {
            $item->categories()->attach($validated['category_ids']);
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
    public function show()
    {
        //
        return view('items.show');
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
