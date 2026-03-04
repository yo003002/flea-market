<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        Review::create([
            'purchase_id' => $request->purchase_id,
            'reviewer_id' => auth()->id(),
            'reviewed_id' => $request->reviewed_id,
            'rating' => $request->rating,
        ]);

        return redirect()->route('profile.index');
    }
}
