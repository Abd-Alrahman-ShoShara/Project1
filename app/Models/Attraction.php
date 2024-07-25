<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attraction extends Model
{
    use HasFactory;
    protected $fillable=[
        'publicTrip_id',
        'image',
        'description',
        'discount',
        'discount_points',
        'type',
    ];

    protected $hidden=[
        'created_at',
        'updated_at',
    ];
    public function publicTrip(){
        return $this->belongsTo(PublicTrip::class,'publicTrip_id');
    }
}
