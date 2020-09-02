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
 */
class Problem extends Model
{
    use SoftDeletes;

    protected $fillable
        = [
            'name',
        ];
}
