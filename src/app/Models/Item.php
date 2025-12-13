<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
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
}
