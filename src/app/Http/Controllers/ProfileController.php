<?php

namespace App\Http\Controllers;


use App\Http\Requests\ProfileRequest;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Review;
use App\Models\TradeMessage;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $userId = auth()->id();

        $unreadCount = TradeMessage::whereHas('purchase', function ($query) use ($userId) {
            $query->where('status', 'trading')
                ->where(function ($q) use ($userId) {
                    $q->where('user_id', $userId)
                        ->orWhereHas('item', function ($qq) use ($userId) {
                            $qq->where('user_id', $userId);
                        });
                });
        })
        ->where('user_id', '!=', $userId)
        ->where('is_read', false)
        ->count();

        $reviewData = Review::where('reviewed_id', $userId)
            ->selectRaw('COALESCE(AVG(rating), 0) as average, COUNT(*) as count')
            ->first();

        $averageRating = (float) $reviewData->average;
        $reviewCount = (int) $reviewData->count;
        $roundedRating = round($averageRating);

        //出品一覧
        if ($request->query('page') === 'sell') {

            $items = Item::with('images')
                ->where('user_id', auth()->id())
                ->latest()
                ->get();

                return view('profile.sell', compact('items', 'averageRating', 'reviewCount','unreadCount', 'roundedRating'));
        }

        //購入した商品
        if ($request->query('page') === 'buy') {

            $purchases = Purchase::with('item.images')
                ->where('user_id', auth()->id())
                ->latest()
                ->get();

            return view('profile.buy', compact('purchases', 'averageRating', 'reviewCount','unreadCount', 'roundedRating'));
        }

        // 取引中の商品
        if ($request->query('page') === 'trade') {
            $userId = auth()->id();

            $trades = Purchase::with(['item.images', 'messages'])
                ->where(function ($query) use ($userId) {
                    $query->where('status', 'trading')
                        ->orWhere(function($q) use ($userId) {
                            $q->where('status', 'completed')
                            ->whereHas('item', function ($itemQuery) use ($userId) {
                                $itemQuery->where('user_id', $userId);
                            })
                            ->whereDoesntHave('reviews', function ($reviewQuery) use ($userId) {
                                $reviewQuery->where('reviewer_id', $userId);
                            });
                        });
                })
                ->where(function ($query) use ($userId) {
                    $query->where('user_id', $userId)
                        ->orWhereHas('item', function ($q) use ($userId) {
                            $q->where('user_id', $userId);
                        });
                })
                ->withCount([
                    'messages as unread_count' => function ($query) use ($userId) {
                        $query->where('user_id', '!=', $userId)
                            ->where('is_read', false);
                    }
                ])
                ->withMax('messages', 'created_at')
                ->orderByDesc('messages_max_created_at')
                ->get();
            return view('profile.trade', compact('trades', 'averageRating', 'reviewCount','unreadCount', 'roundedRating'));
        }

        return view('profile.index', compact('averageRating', 'reviewCount','unreadCount', 'roundedRating'));
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

    // プロフィール編集
    public function update(ProfileRequest $request)
    {
        /** @var \App\Models\User $user */
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

            return redirect()->route('profile.index');
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
