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
        return $this->belongsToMany(Category::class,'category_product');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function comments()
    {
        return $this->belongsToMany(User::class, 'comments');
    }
}
