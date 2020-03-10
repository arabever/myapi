<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'id', 'name', 'lang','verdict','code','link'
    ];
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
