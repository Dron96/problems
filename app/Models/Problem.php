<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Problem
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Problem newModelQuery()
 * @method static Builder|Problem newQuery()
 * @method static Builder|Problem query()
 * @method static Builder|Problem whereCreatedAt($value)
 * @method static Builder|Problem whereId($value)
 * @method static Builder|Problem whereName($value)
 * @method static Builder|Problem whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static \Illuminate\Database\Query\Builder|Problem onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|Problem withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Problem withoutTrashed()
 * @property Carbon|null $deleted_at
 * @method static Builder|Problem whereDeletedAt($value)
 */
class Problem extends Model
{
    use SoftDeletes;

    protected $fillable
        = [
            'name',
        ];

    public function solutions()
    {
        return $this->hasMany(Solution::class, 'problem_id', 'id');
    }

    protected static function booted()
    {
        static::deleting(function ($problem) {
            foreach ($problem->solutions as $solution)
            {
                $solution->delete();
            }
        });
    }
}
