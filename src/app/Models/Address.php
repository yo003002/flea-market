<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\ItemCategory;
use App\Models\Like;
use App\Models\Mylist;
use App\Models\Purcharses;
use App\Models\Comment;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'postal_code',
        'address',
        'name',
        'building',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
