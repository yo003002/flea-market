<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Address;
use App\Models\Category;
use App\Models\ItemImage;
use App\Models\ItemCategory;
use App\Models\Item;
use App\Models\Mylist;
use App\Models\Purcharses;

class Like extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'item_id'];
}

