<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{

    const EXPIRATION_TIME = 3; //OTP Expiration minutes

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'code', 'used',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['code'];


    /**
     * Set the otp's encrypt code .
     *
     * @param  string  $value
     * @return void
     */
    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = crypt($value, 'app_e_drug');
    }

    /**
     * Get the user that owns the otp.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * True if the otp is not used nor expired
     *
     * @return bool
     */
    public function isValid()
    {
        return !$this->isUsed() && $this->isExpired();
    }

    /**
     * Is the current token used
     *
     * @return bool
     */
    public function isUsed()
    {
        return $this->used;
    }

    /**
     * Is the current token expired
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->created_at->diffInMinutes(Carbon::now()) < static::EXPIRATION_TIME;
    }
}
