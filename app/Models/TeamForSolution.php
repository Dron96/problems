<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class TeamForSolution extends Model
{
    public $timestamps = false;

    protected $fillable
        = [
            'user_id',
            'solution_id',
        ];

    public function solution()
    {
        return $this->belongsTo(Solution::class, 'solution_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
