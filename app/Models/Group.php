<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Group
 *
 * @property int $id
 * @property string $name
 * @property int $leader_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted_at
 *
 * @property-read User|null $leader
 * @property-read Collection|Problem[] $problems
 * @property-read int|null $problems_count
 * @property-read Collection|User[] $users
 * @property-read int|null $users_count
 *
 * @method static Builder|Group newModelQuery()
 * @method static Builder|Group newQuery()
 * @method static \Illuminate\Database\Query\Builder|Group onlyTrashed()
 * @method static Builder|Group query()
 * @method static Builder|Group whereCreatedAt($value)
 * @method static Builder|Group whereDeletedAt($value)
 * @method static Builder|Group whereId($value)
 * @method static Builder|Group whereLeaderId($value)
 * @method static Builder|Group whereName($value)
 * @method static Builder|Group whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Group withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Group withoutTrashed()
 *
 * @mixin Eloquent
 */
class Group extends Model
{
    use SoftDeletes;

    protected $fillable =
        [
            'name',
            'leader_id'
        ];

    /**
     * Получение пользователей, состоящих в подразделении
     *
     * @return HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class, 'group_id', 'id');
    }

    /**
     * Получение проблем, которые были отправлены в подразделение
     *
     * @return BelongsToMany
     */
    public function problems()
    {
        return $this->belongsToMany(Problem::class);
    }

    /**
     * Получение пользователя, который является начальником подразделения
     *
     * @return HasOne
     */
    public function leader()
    {
        return $this->hasOne(User::class, 'id', 'leader_id');
    }
}
