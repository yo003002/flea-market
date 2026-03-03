<?php

namespace App\Http\Controllers;

use App\Http\Requests\TradeRequest;
use App\Models\Purchase;
use App\Models\TradeMessage;
use App\Models\Review;

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

        $hasReviewed = Review::where('purchase_id', $purchase->id)
            ->where('reviewer_id', auth()->id())
            ->exists();

        $shouldShowModal = false;

        if ($purchase->status === 'completed' && !$hasReviewed) {
            $shouldShowModal = true;
        }

        TradeMessage::where('purchase_id', $purchase->id)
            ->where('user_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('trade.show', compact('purchase', 'isSeller', 'otherPurchases', 'otherUser', 'messages', 'shouldShowModal'));
    }

    public function store(TradeRequest $request, Purchase $purchase)
    {
        if (
            $purchase->user_id !== auth()->id() &&
            $purchase->item->user_id !== auth()->id()
        ) {
            abort(403);
        }

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

    // メッセージ削除
    public function destroy(TradeMessage $message)
    {
        if ($message->user_id !== auth()->id()) {
            abort(403);
        }

        $purchase = $message->purchase;

        $message->delete();

        return redirect()->route('trade.show', $purchase);
    }

    // 取引完了
    public function complete(Purchase $purchase)
    {
        if ($purchase->user_id !== auth()->id()) {
            abort(403);
        }

        $purchase->update([
            'status' => 'completed',
        ]);

        return redirect()->route('trade.show', $purchase);
    }
}
