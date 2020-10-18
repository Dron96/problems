<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\TeamForSolution
 *
 * @property int $id
 * @property int $user_id
 * @property int $solution_id
 *
 * @property-read Solution $solution
 * @property-read User $user
 *
 * @method static Builder|TeamForSolution newModelQuery()
 * @method static Builder|TeamForSolution newQuery()
 * @method static Builder|TeamForSolution query()
 * @method static Builder|TeamForSolution whereId($value)
 * @method static Builder|TeamForSolution whereSolutionId($value)
 * @method static Builder|TeamForSolution whereUserId($value)
 *
 * @mixin Eloquent
 */
class TeamForSolution extends Model
{
    public $timestamps = false;

    protected $fillable
        = [
            'user_id',
            'solution_id',
        ];

    /**
     * Получение решения, за которое ответственнена данная команда
     *
     * @return BelongsTo
     */
    public function solution()
    {
        return $this->belongsTo(Solution::class, 'solution_id', 'id');
    }

    /**
     * Получение пользователя, который состоит в команде
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
