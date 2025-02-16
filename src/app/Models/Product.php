<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function mylistBy()
    {
        return $this->belongsToMany(User::class, 'mylists');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class,'category_products');
    }

    public function comments()
    {
        return $this->belongsToMany(User::class, 'comments');
    }

    public function sells()
    {
        return $this->hasOne(Sell::class);
    }

    public function purchases()
    {
        return $this->hasOne(Purchase::class);
    }

    protected $fillable = ['name', 'brand', 'price', 'description', 'image', 'status', 'color'];
}
