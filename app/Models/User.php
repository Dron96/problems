<?php

namespace App\Models;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\Client;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\Token;

/**
 * App\User
 *
 * @mixin Eloquent
 *
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $father_name
 * @property string $email
 * @property string $password
 * @property boolean $is_admin
 * @property int|null $group_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Client[] $clients
 * @property-read int|null $clients_count
 * @property-read Collection|Token[] $tokens
 * @property-read int|null $tokens_count
 * @property-read Group|null $group
 * @property-read Group $leaderGroup
 * @property-read Collection|Like[] $likes
 * @property-read int|null $likes_count
 * @property-read Collection|Solution[] $solutions
 * @property-read int|null $solutions_count
 * @property-read Collection|TeamForSolution[] $teamForSolution
 * @property-read int|null $team_for_solution_count
 *
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereFatherName($value)
 * @method static Builder|User whereSurname($value)
 *
 * @method static Builder|User whereGroupId($value)
 * @method static Builder|User whereIsAdmin($value)
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'surname', 'father_name', 'group_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Получение подразделения, в котором состоит пользователь
     *
     * @return BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    /**
     * Получение лайков, которые поставил пользователь
     *
     * @return HasMany
     */
    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id', 'id');
    }

    /**
     * Получение решений проблем, за которые ответственнен пользователь
     *
     * @return HasMany
     */
    public function solutions()
    {
        return $this->hasMany(Solution::class, 'executor_id', 'id');
    }

    /**
     * Получение id ользователя, который является начальником подразделения,
     * в котором состоит текущий пользователь
     *
     * @return BelongsTo
     */
    public function leaderGroup()
    {
        return $this->belongsTo(Group::class, 'id', 'leader_id');
    }

    /**
     * Получение команд, в которых состоит пользователь
     *
     * @return HasMany
     */
    public function teamForSolution()
    {
        return $this->hasMany(TeamForSolution::class, 'user_id', 'id');
    }
}
