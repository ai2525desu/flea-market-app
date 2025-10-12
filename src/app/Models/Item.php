<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    use HasFactory;

    // itemsテーブルのenum型の定数を定義
    const CONDITION = [
        1 => '良好',
        2 => '目立った傷や汚れなし',
        3 => 'やや傷や汚れあり',
        4 => '状態が悪い'
    ];

    protected $fillable = [
        'user_id',
        'item_name',
        'item_image',
        'brand',
        'price',
        'description',
        'condition',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item', 'item_id', 'category_id')->withTimestamps();
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likedByCurrentUser()
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopeItemNameSearch($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where('item_name', 'like', "%{$keyword}%");
        }
        return $query;
    }
}
