<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'buyer_id', 'seller_id','status'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function values()
    {
        return $this->hasMany(Value::class);
    }
}
