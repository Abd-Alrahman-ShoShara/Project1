<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicTripClassification extends Model
{
    use HasFactory;
    protected $fillable=[
        'classification_id',
        'publicTrip_id',
    ];
    public function classification(){
        return $this->belongsTo(Classification::class);
    }
    public function publicTrip(){
        return $this->belongsTo(PublicTrip::class);
    }
}
