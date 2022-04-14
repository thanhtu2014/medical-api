<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class People extends Authenticatable
{
    protected $table = 'peoples';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'type',
        'org',
        'name',
        'user',
        'post',
        'pref',
        'pref_code',
        'address',
        'xaddress',
        'note',
        'phone',
        'mail',
        'chg',
        'new_by',
        'upd_by',
        'upd_ts',
        'google_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [];

}
