<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Favorite extends Authenticatable
{
    protected $table = 'favorites';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'record',
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

    public function record(){
        return $this->belongsTo(Record::class);
    }

}
