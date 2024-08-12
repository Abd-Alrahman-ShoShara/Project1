<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'points',
        'wallet',
    ];
    public function googleUser(){
        return $this->hasOne(GoogleUser::class,'user_id');
    }
    public function normalUser(){
        return $this->hasOne(NormalUser::class,'user_id');
    }
    public function notification(){
        return $this->hasMany(Notification::class,'user_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function trip(){
        return $this->hasMany(Trip::class);
    }
    public function userPublicTrip(){
        return $this->hasMany(UserPublicTrip::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function favorite()
    {
        return $this->hasMany(Favorite::class,'user_id');
    }


}
