<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleUser extends Model
{
    protected $fillable = [
        'email',
        'google_id',
        'user_id',
        'avatar',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    use HasFactory;
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    
}
