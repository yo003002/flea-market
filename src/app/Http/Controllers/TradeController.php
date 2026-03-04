<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TradeRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\TradeCompletedMail;
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

        $otherPurchases = Purchase::where('id', '!=', $purchase->id)
            ->where(function($query) {
                $query->where('user_id', auth()->id())
                    ->orWhereHas('item', function($q) {
                        $q->where('user_id', auth()->id());
                    });
            })
            ->with('item')
            ->get()
            ->filter(function($other) {
                if ($other->status === 'trading') {
                    return true;
                }

                $isBuyer = $other->user_id === auth()->id();
                $isSeller = $other->item->user_id === auth()->id();

                if ($isBuyer) {
                    return false;
                }

                if ($isSeller) {
                    $hasReviewed = Review::where('purchase_id', $other->id)
                        ->where('reviewer_id', auth()->id())
                        ->exists();
                    return !$hasReviewed;
                }
                return false;
            });

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

    // メッセージ編集
    public function update(TradeRequest $request, TradeMessage $message)
    {
        if ($message->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'required|string|max:400',
        ]);

        $message->update([
            'message' => $request->message,
        ]);

        return response()->json([
            'message' => $message->message
        ]);
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

        $seller = $purchase->item->user;

        Mail::to($seller->email)
            ->send(new TradeCompletedMail($purchase));

        return redirect()->route('trade.show', $purchase->id);
    }
}
