<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'id','sub', 'name', 'lang','verdict','code','link','user_id'
    ];
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
