<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    public function profile()
    {
        return $this->belongsTo(Profile::class,'user_id', 'user_id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    protected $fillable = ['product_id', 'user_id', 'comment'];

}
