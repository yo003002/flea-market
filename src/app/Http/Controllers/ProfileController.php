<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function edit()
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
    public function update(Request $request)
    {
        //
        //リクエストでバリデーションを作成したらここのバリデーションは削除
        $request->validate([
            'name' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image',
        ]);

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
