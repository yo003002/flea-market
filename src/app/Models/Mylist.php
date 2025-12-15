<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Address;
use App\Models\Category;
use App\Models\ItemImage;
use App\Models\ItemCategory;
use App\Models\Like;
use App\Models\Item;
use App\Models\Purcharses;

class Mylist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
    ];
}
