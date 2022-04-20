<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Schedule extends Authenticatable
{
    protected $table = 'schedules';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'type',
        'title',
        'color',
        'date',
        'hospital',
        'people',
        'remark',
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
