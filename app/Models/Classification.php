<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
    ];
    protected $hidden=[
        'created_at',
        'updated_at',
    ];

    public function publicTripClassification(){
        return $this->hasMany(PublicTripClassification::class);
    }
}
