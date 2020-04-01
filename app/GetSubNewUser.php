<?php

namespace App;
use App\User;

use Illuminate\Database\Eloquent\Model;

class GetSubNewUser extends Model
{
    protected $fillable = [
        'id','user_id'
    ];

    public function user()
    {
    return $this->belongsTo('App\User');
    }
}
