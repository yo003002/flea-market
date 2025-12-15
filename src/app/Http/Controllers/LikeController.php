<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Address;
use App\Models\Category;
use App\Models\ItemImage;
use App\Models\ItemCategory;
use App\Models\Like;
use App\Models\Mylist;
use App\Models\Purcharses;

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
            return back()->with('status', 'unlliked');
        }

        //いいね追加
        $item->likes()->create([
            'user_id' => $user->id,
            'is_favorite' => true,
        ]);

        return back()->with('status', 'liked');
    }

}
