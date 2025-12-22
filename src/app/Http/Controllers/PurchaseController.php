<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;

use App\Models\Address;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\ItemCategory;
use App\Models\Like;
use App\Models\Mylist;
use App\Models\Purchase;

class PurchaseController extends Controller
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

    public function confirm($item_id)
    {
        $item = Item::with('images')->findOrFail($item_id);

        if ($item->user_id === Auth::id()) {
            abort(403, '自分の商品は購入できません');
        }

        if ($item->status === 'sold') {
            abort(404)
;        }

        $address = Address::where('user_id', Auth::id())->first();

        return view('purchase.confirm', compact('item', 'address'));
    }

    public function address()
    {

       
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


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //決済
    public function checkout(PurchaseRequest $request, $item_id)
    {
        //
        $item = Item::findOrFail($item_id);

        $request->validate([
            'pay_method' => 'required|string',
        ]);

        session([
            'pay_method' => $request->pay_method
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->title,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success', $item->id),
        ]);
            return redirect($session->url);
    }


    //
    public function success($item_id)
    {
        $user = Auth::user();
        $item = Item::where('status', 'selling')->findOrFail($item_id);

        if ($item->user_id === $user->id) {
            abort(403);
        }

        //住所を所得
        $address = $user->address;

        if (!$address) {
            abort(400, '住所がありません');
        }

        DB::transaction(function () use ($user, $item_id, $address) {

            $item = Item::where('id', $item_id)
                ->where('status', 'selling')
                ->lockForUpdate()
                ->firstOrFail();

            if ($item->user_id === $user->id) {
                abort(403);
            }

            if ($item->purchase()->exists()) {
                abort(409);
            }
            
            Purchase::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'address_id' => $address->id,
                'pay_method' => session('pay_method'),
            ]);

            $item->update([
                'status' => 'sold',
            ]);
        });

        return redirect('/');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editAddress($item_id)
    {
        //
        $address = Address::where('user_id', Auth::id())->first();

        return view('purchase.address', compact('address', 'item_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAddress(AddressRequest $request, $item_id)
    {


        $address = Address::where('user_id', Auth::id())->firstOrFail();

        $address->update([
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        return redirect()->route('purchase.confirm', $item_id);
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
