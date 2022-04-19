<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class MediaKeyWord extends Authenticatable
{
    protected $table = 'media_x_keyword';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'keyword',
        'fpath',
        'fname',
        'fdisk',
        'vname',
        'mime',
        'fext',
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
