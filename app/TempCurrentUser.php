<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempCurrentUser extends Model
{
    protected $fillable = [
        'id', 'user_id', 'submission_id',
    ];
}
