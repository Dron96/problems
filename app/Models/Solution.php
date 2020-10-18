<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Eloquent;
use Illuminate\Support\Facades\Validator;

/**
 * App\Models\Solution
 *
 * @mixin Eloquent
 *
 * @property int $id
 * @property string $name
 * @property int $user_id
 * @property int $problem_id
 * @property bool $in_work
 * @property string|null $status
 * @property int $creator_id
 * @property string|null $deadline
 * @property int|null $executor_id
 * @property string|null $plan
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted_at
 *
 * @property-read Collection|Task[] $tasks
 * @property-read int|null $tasks_count
 * @property-read User|null $executor
 * @property-read Problem $problem
 * @property-read Collection|TeamForSolution[] $teamForSolution
 * @property-read int|null $team_for_solution_count
 *
 * @method static Builder|Solution newModelQuery()
 * @method static Builder|Solution newQuery()
 * @method static \Illuminate\Database\Query\Builder|Solution onlyTrashed()
 * @method static Builder|Solution query()
 * @method static \Illuminate\Database\Query\Builder|Solution withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Solution withoutTrashed()
 * @method static Builder|Solution whereCreatedAt($value)
 * @method static Builder|Solution whereDeletedAt($value)
 * @method static Builder|Solution whereId($value)
 * @method static Builder|Solution whereInWork($value)
 * @method static Builder|Solution whereName($value)
 * @method static Builder|Solution whereProblemId($value)
 * @method static Builder|Solution whereStatus($value)
 * @method static Builder|Solution whereUpdatedAt($value)
 * @method static Builder|Solution whereUserId($value)
 * @method static Builder|Solution whereCreatorId($value)
 * @method static Builder|Solution whereDeadline($value)
 * @method static Builder|Solution whereExecutorId($value)
 * @method static Builder|Solution wherePlan($value)
 */
class Solution extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'in_work',
        'status',
        'creator_id',
        'problem_id',
        'deadline',
        'executor_id'
    ];

    /**
     * Валидатор для проверки иммется ли проблема, к которой относится решение
     *
     * @param $id
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
     */
    public function hasProblem($id)
    {
        $rules = [
            'problem_id' => 'exists:problems,id,deleted_at,NULL',
        ];
        $messages = [
            'problem_id.exists' => 'Такой проблемы не существует',
        ];

        return Validator::make(Solution::find($id)->toArray(), $rules, $messages);
    }

    /**
     * Получение задач, которые относятся к решению
     *
     * @return HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'solution_id', 'id');
    }

    /**
     * Получение проблемы, к которой относится решение
     *
     * @return BelongsTo
     */
    public function problem()
    {
        return $this->belongsTo(Problem::class, 'problem_id', 'id');
    }

    /**
     * Получение ответственного за решение
     *
     * @return BelongsTo
     */
    public function executor()
    {
        return $this->belongsTo(User::class, 'executor_id', 'id');
    }

    /**
     * Получение команды, которая участвует в реализации решения
     *
     * @return HasMany
     */
    public function teamForSolution()
    {
        return $this->hasMany(TeamForSolution::class, 'solution_id', 'id');
    }

    protected static function booted()
    {
        static::deleting(function ($solution) {
            $solution->tasks()->delete();
        });
    }
}
