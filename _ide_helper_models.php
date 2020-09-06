<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
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
	class Problem extends \Eloquent {}
}

namespace App\Models{
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
 */
	class Solution extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @mixin Eloquent
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @property-read Collection|Client[] $clients
 * @property-read int|null $clients_count
 * @property-read Collection|Token[] $tokens
 * @property-read int|null $tokens_count
 */
	class User extends \Eloquent {}
}

