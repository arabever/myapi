<?php

namespace App;
use App\User;
use App\FirstSubSuccessScrap;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'id','sub', 'name', 'lang','verdict','code','link','user_id','created_at'
    ];
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function firstSub() // first submission in success scrap
    {
        return $this->hasOne('App\FirstSubSuccessScrap', 'user_id');
    }
}
