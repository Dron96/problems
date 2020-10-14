<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    protected $fillable =
        [
            'name',
            'short_name',
            'leader_id'
        ];

    public function users()
    {
        return $this->hasMany(User::class, 'group_id', 'id');
    }

    public function problems()
    {
        return $this->belongsToMany(Problem::class);
    }

    public function leader()
    {
        return $this->hasOne(User::class, 'id', 'leader_id');
    }
}
