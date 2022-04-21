<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Tag extends Authenticatable
{
    protected $table = 'tags';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'type',
        'name',
        'color',
        'user',
        'chg',
        'new_by',
        'new_ts',
        'upd_by',
        'upd_ts'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [];
}
