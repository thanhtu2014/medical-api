<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Record extends Authenticatable
{
    protected $table = 'records';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'type',
        'begin',
        'end',
        'title',
        'hospital',
        'people',
        'user',
        'folder',
        'media',
        'visible',
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
