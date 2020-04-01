<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Submission;

class FirstSubSuccessScrap extends Model
{
    protected $fillable = [
        'id','user_id','submission_id'
    ];

    public function submission()
    {
        return $this->belongsTo('App\Submission');
    }
}
