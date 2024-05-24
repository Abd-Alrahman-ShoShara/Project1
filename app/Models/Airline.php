<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airline extends Model
{
    protected $fillable =['name','image'];
    
    protected $hidden=[
        'created_at',
        'updated_at',
    ];
    use HasFactory;
    public function ticket(){
        return $this->hasMany(Ticket::class);
    }

}
