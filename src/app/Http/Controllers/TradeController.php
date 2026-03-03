<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\TradeMessage;

class TradeController extends Controller
{
    public function show(Purchase $purchase)
    {
        if (
            $purchase->user_id !== auth()->id() &&
            $purchase->item->user_id !== auth()->id()
        ) {
            abort(403);
        }

        $purchase->load('messages.user', 'item.images', 'item.user', 'user');

        $isSeller = auth()->id() === $purchase->item->user_id;

        $otherPurchases = [];
        if ($isSeller) {
            $otherPurchases = Purchase::where('item_id', '!=', $purchase->item_id)
                ->whereHas('item', function($query) {
                    $query->where('user_id', auth()->id());
                })
                ->with('item')
                ->get();
        }

        $otherUser = auth()->id() === $purchase->user_id ? $purchase->item->user : $purchase->user;

        $messages = $purchase->messages->map(function($message) {
            $message->isMe = $message->user_id === auth()->id();
            return $message;
        });

        return view('trade.show', compact('purchase', 'isSeller', 'otherPurchases', 'otherUser', 'messages'));
    }

    public function store(Request $request, Purchase $purchase)
    {
        if (
            $purchase->user_id !== auth()->id() &&
            $purchase->item->user_id !== auth()->id()
        ) {
            abort(403);
        }

        // todo　リクエスト作成時に消す
        $request->validate([
            'message' => 'required|string|max:1000',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('trade_images', 'public');
        }

        TradeMessage::create([
            'purchase_id' => $purchase->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('trade.show', $purchase);
    }
}
