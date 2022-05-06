<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Share extends Authenticatable
{
    protected $table = 'shares';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'record',
        'user',
        'to',
        'mail',
        'status',
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

    public function people(){
        return $this->belongsTo(People::class, 'to');
    }
    public function record(){
        return $this->belongsTo(People::class);
    }
}
