<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id', 'first_name', 'last_name', 'email', 'mobile',  'gender', 'dob', 'password', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'mobile_verified_at' => 'datetime',
    ];

    /**
     * Get the user's Mobile number with 91.
     *
     * @param  string  $value
     * @return string
     */
    public function getMobileAttribute($value)
    {
        return 91 . $value;
    }

    /* 
     * check user is verified
    */
    public function isVerified()
    {
        return $this->mobile_verified_at;
    }

    /*
     * Get the otps for the user.
    */
    public function otps()
    {
        return $this->hasMany(Otp::class);
    }

    /*
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Find out if user has a specific role
     * for middleware
     * @return boolean
     */
    public function hasRole($check)
    {
        if ($this->role->slug == $check)
            return true;
        else
            return false;
    }

    /**
     * Route notifications for the Textlocal channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForTextlocal($notification)
    {
        return $this->mobile;
    }
}
