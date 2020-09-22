<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    public $timestamps = false;

    protected $fillable =
        [
            'problem_id',
            'user_id',
        ];

    public function problem()
    {
        return $this->belongsTo(Problem::class, 'problem_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
