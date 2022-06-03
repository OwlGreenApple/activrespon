<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	
		/*
		*
		* if is_admin
		* 0 -> user activrespon
		* 1 -> admin user activrespon
		* 2 -> admin woowa
		*
		* is_started -> start / stop sending message
		* speed -> speed of sending message
		* 0 -> slow
		* 1 -> normal
		* 2 -> fast
		*
    * status :
    * 0 -> inactiv user
    * 1 -> activ user
    * 2 -> reseller user
    * 
    * service :
    * 0 -> not connected
    * 1 -> wablas
    * 2 -> wafonnte
    * 
		*/
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone_number', 'gender', 'password','timezone','code_country'
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
        'email_verified_at' => 'datetime',
    ];

    /*
      phone_number => unique
    */
}
