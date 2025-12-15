<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Address;
use App\Models\Category;
use App\Models\ItemImage;
use App\Models\ItemCategory;
use App\Models\Like;
use App\Models\Mylist;
use App\Models\Purcharses;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'brand_name',
        'description',
        'price',
        'status',
        'condition',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'item_category');
    }

    public function images()
    {
        return $this->hasMany(ItemImage::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function isLikeBy($user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->likes()
        ->where('user_id', $user->id)
        ->exists();
    }

    public function isLikedBy($user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
