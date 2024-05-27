<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NormalUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'phone',
       'user_id',
        'verification_code',
        'is_verified',
        'password',
        'role',
    ];
    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
    ];
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
