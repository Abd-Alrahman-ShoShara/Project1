<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    protected $fillable =[
        'name',
        'city_id'
    ];
    public function ticket(){
        return $this->hasMany(Ticket::class);
    }

    public function city(){
        return $this->belongsTo(City::class);
    }

    use HasFactory;
}
