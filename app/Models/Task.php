<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Task
 *

 * @mixin Eloquent
 *
 * @property int $id
 * @property string $description
 * @property int $creator_id
 * @property int $solution_id
 * @property string|null $status
 * @property string|null $deadline
 * @property int|null $executor_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted_at
 *
 * @property-read Solution $solution
 *
 * @method static Builder|Task newModelQuery()
 * @method static Builder|Task newQuery()
 * @method static \Illuminate\Database\Query\Builder|Task onlyTrashed()
 * @method static Builder|Task query()
 * @method static \Illuminate\Database\Query\Builder|Task withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Task withoutTrashed()
 * @method static Builder|Task whereCreatedAt($value)
 * @method static Builder|Task whereCreatorId($value)
 * @method static Builder|Task whereDeadline($value)
 * @method static Builder|Task whereDeletedAt($value)
 * @method static Builder|Task whereDescription($value)
 * @method static Builder|Task whereExecutorId($value)
 * @method static Builder|Task whereId($value)
 * @method static Builder|Task whereSolutionId($value)
 * @method static Builder|Task whereStatus($value)
 * @method static Builder|Task whereUpdatedAt($value)
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

    /**
     * Получение решения, к которому относится задача
     *
     * @return BelongsTo
     */
    public function solution()
    {
        return $this->belongsTo(Solution::class, 'solution_id', 'id');
    }

    /**
     * Получение id проблемы, к которой относится задача
     *
     * @return int
     */
    public function getProblemId()
    {
        return $this->solution->problem_id;
    }
}
