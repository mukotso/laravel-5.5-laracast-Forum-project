<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    public function getRouteKeyName ()
    {
        return 'name';
    }
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function threads(){
        return $this->hasMany(Thread::class)->latest();
    }
    public function activity(){
        return $this->hasMany(Activity::class);
    }
}
