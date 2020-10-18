<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Like
 *
 * @property int $id
 * @property int $problem_id
 * @property int $user_id
 *
 * @property-read Problem $problem
 * @property-read User $user
 *
 * @method static Builder|Like newModelQuery()
 * @method static Builder|Like newQuery()
 * @method static Builder|Like query()
 * @method static Builder|Like whereId($value)
 * @method static Builder|Like whereProblemId($value)
 * @method static Builder|Like whereUserId($value)
 *
 * @mixin Eloquent
 */
class Like extends Model
{
    public $timestamps = false;

    protected $fillable =
        [
            'problem_id',
            'user_id',
        ];

    /**
     * Получение проблемы, которой поставлен лайк
     *
     * @return BelongsTo
     */
    public function problem()
    {
        return $this->belongsTo(Problem::class, 'problem_id', 'id');
    }

    /**
     * Получение пользователя, который поставил лайк
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
