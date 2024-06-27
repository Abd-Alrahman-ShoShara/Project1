<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    use HasFactory;
    protected $fillable=[
        'quastion',
        'answer',
    ];
    protected $hidden=[
        'created_at',
        'updated_at',
    ];
}
