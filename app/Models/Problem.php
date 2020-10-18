<?php

namespace App\Models;

use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Problem
 *
 * @mixin Eloquent
 *
 * @property int $id
 * @property string $name
 * @property int $creator_id
 * @property string|null $description
 * @property string|null $possible_solution
 * @property string $status
 * @property string|null $experience
 * @property string|null $result
 * @property string $urgency
 * @property string $importance
 * @property int $progress
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted_at
 *
 * @property-read Collection|Group[] $groups
 * @property-read int|null $groups_count
 * @property-read Collection|Like[] $likes
 * @property-read int|null $likes_count
 * @property-read Solution|null $solution
 *
 * @method static Builder|Problem newModelQuery()
 * @method static Builder|Problem newQuery()
 * @method static Builder|Problem query()
 * @method static Builder|Problem whereCreatedAt($value)
 * @method static Builder|Problem whereId($value)
 * @method static Builder|Problem whereName($value)
 * @method static Builder|Problem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Problem onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|Problem withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Problem withoutTrashed()
 * @method static Builder|Problem whereDeletedAt($value)
 * @method static Builder|Problem whereCreatorId($value)
 * @method static Builder|Problem whereDescription($value)
 * @method static Builder|Problem whereExperience($value)
 * @method static Builder|Problem whereImportance($value)
 * @method static Builder|Problem wherePossibleSolution($value)
 * @method static Builder|Problem whereProgress($value)
 * @method static Builder|Problem whereResult($value)
 * @method static Builder|Problem whereStatus($value)
 * @method static Builder|Problem whereUrgency($value)
 */
class Problem extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    protected $fillable
        = [
            'name',
            'creator_id',
            'experience',
            'possible_solution',
            'result',
            'progress',
            'description',
            'importance',
            'urgency',
            'status'
        ];

    protected $cascadeDeletes = ['solutions'];

    /**
     * Получение решения, которое относится к проблеме
     *
     * @return HasOne
     */
    public function solution()
    {
        return $this->hasOne(Solution::class, 'problem_id', 'id');
    }

    /**
     * Получение лайков проблемы
     *
     * @return HasMany
     */
    public function likes()
    {
        return $this->hasMany(Like::class, 'problem_id', 'id');
    }

    /**
     * Получение подразделений, в которые отправлена проблема
     *
     * @return BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }
}
