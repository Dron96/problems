<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Eloquent;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

/**
 * App\Models\Solution
 *
 * @method static Builder|Solution newModelQuery()
 * @method static Builder|Solution newQuery()
 * @method static \Illuminate\Database\Query\Builder|Solution onlyTrashed()
 * @method static Builder|Solution query()
 * @method static \Illuminate\Database\Query\Builder|Solution withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Solution withoutTrashed()
 * @mixin Eloquent
 * @property int $id
 * @property string $name
 * @property int $user_id
 * @property int $problem_id
 * @property bool $in_work
 * @property string|null $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static Builder|Solution whereCreatedAt($value)
 * @method static Builder|Solution whereDeletedAt($value)
 * @method static Builder|Solution whereId($value)
 * @method static Builder|Solution whereInWork($value)
 * @method static Builder|Solution whereName($value)
 * @method static Builder|Solution whereProblemId($value)
 * @method static Builder|Solution whereStatus($value)
 * @method static Builder|Solution whereUpdatedAt($value)
 * @method static Builder|Solution whereUserId($value)
 * @property int $creator_id
 * @property string|null $deadline
 * @property int|null $executor_id
 * @method static Builder|Solution whereCreatorId($value)
 * @method static Builder|Solution whereDeadline($value)
 * @method static Builder|Solution whereExecutorId($value)
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
     * @param $id
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
     */
    public function hasSolutionProblem($id)
    {
        $rules = [
            'problem_id' => 'exists:problems,id,deleted_at,NULL',
            'id' => 'exists:solutions,id,deleted_at,NULL',
        ];
        $messages = [
            'problem_id.exists' => 'Такой проблемы не существует',
            'id.exists' => 'Такого решения не существует',
        ];

        return Validator::make(Solution::find($id)->toArray(), $rules, $messages);
    }
}
