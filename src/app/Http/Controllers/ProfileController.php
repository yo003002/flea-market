<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;

use App\Http\Requests\ProfileRequest;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\Item;
use App\Models\Category;
use App\Models\ItemImage;
use App\Models\ItemCategory;
use App\Models\Like;
use App\Models\Mylist;
use App\Models\Purchase;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //出品一覧
        if ($request->query('page') === 'sell') {

            $items = Item::with('images')
                ->where('user_id', auth()->id())
                ->latest()
                ->get();

                return view('profile.sell', compact('items'));
        }

        //購入した商品
        if ($request->query('page') === 'buy') {

            $purchases = Purchase::with('item.images')
                ->where('user_id', auth()->id())
                ->latest()
                ->get();

            return view('profile.buy', compact('purchases'));
        }

        return view('profile.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        //
        $user = auth()->user();
        $address = $user->address ?? new \App\Models\Address;

        $mode = $user->is_profile_set ? 'edit' : 'first';

        return view('profile.edit', compact('user', 'address', 'mode'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileRequest $request)
    {


        $user = auth()->user();
    

        $user->name = $request->name;

        //プロフ画像があれば保存
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            //　DBにパスを保存
            $user->profile_image = $path;
        }

        $user->is_profile_set = true;
        $user->save();


        // 住所
        $user->address()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'name' => $user->name,
                'building' => $request->building,
            ]
            );

            return redirect('/mypage');
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
