<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Task
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task newQuery()
 * @method static \Illuminate\Database\Query\Builder|Task onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Task query()
 * @method static \Illuminate\Database\Query\Builder|Task withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Task withoutTrashed()
 * @mixin \Eloquent
 */
class Task extends Model
{
    use SoftDeletes;

    protected $fillable
        = [
            'description',
            'solution_id',
            'deadline',
            'executor_id',
            'status',
            'creator_id',
        ];

    public function solution()
    {
        return $this->belongsTo(Solution::class, 'solution_id', 'id');
    }

    public function getProblemId()
    {
        return Solution::where('id', $this->solution_id)->value('problem_id');
    }
}
