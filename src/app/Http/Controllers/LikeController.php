<?php

namespace App\Http\Controllers;

use App\Models\Item;
class LikeController extends Controller
{
    //
    public function store($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        if ($item->isLikeBy($user)) {
            //いいね解除
            $item->likes()->where('user_id', $user->id)->delete();
            return back()->with('status', 'unliked');
        }

        //いいね追加
        $item->likes()->create([
            'user_id' => $user->id,
            'is_favorite' => true,
        ]);

        return back()->with('status', 'liked');
    }

}
