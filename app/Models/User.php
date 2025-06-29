<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
        'email',
        'password',
        'google_id',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
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


    public function profile(){
        return $this->hasOne('App\Models\Profile');
    }


    public function note(){
        return $this->hasMany('App\Models\Note');
    }


    public function getGenderTypeAttribute()
    {
        if ($this->profile && $this->profile->gender === null) {
            return 'Not selected';
        } elseif ($this->profile && $this->profile->gender == 1) {
            return 'Male';
        } elseif ($this->profile && $this->profile->gender == 0) {
            return 'Female';
        }
        return 'Not selected';
    }

}
