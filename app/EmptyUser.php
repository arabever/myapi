<?php
use App\User;
namespace App;

use Illuminate\Database\Eloquent\Model;

class EmptyUser extends Model
{
    protected $fillable = [
        'id', 'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
