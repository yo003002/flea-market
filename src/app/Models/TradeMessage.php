<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Purchase;
use App\Models\User;

class TradeMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'user_id',
        'message',
        'image_path'
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
