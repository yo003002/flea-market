<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Address;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\ItemCategory;
use App\Models\Like;
use App\Models\Mylist;
use App\Models\Purcharses;
use App\Models\Comment;


class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_category');
    }
}
