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
use App\Models\Purchase;
use App\Models\Comment;

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


    //いいね
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function isLikeBy($user)
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

    //コメント
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function latestComment()
    {
        return $this->hasOne(Comment::class)->latestOfMany();
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }
}
