<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [

        'publicTrip_id',
        'user_id',

    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function publicTrip(){
        return $this->belongsTo(PublicTrip::class,'publicTrip_id');
    }
}
