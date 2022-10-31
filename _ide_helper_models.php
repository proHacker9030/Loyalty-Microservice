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
 * App\Models\AccessToken.
 *
 * @property int                             $id
 * @property string                          $name
 * @property string                          $token
 * @property string|null                     $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class AccessToken extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Orders
 *
 * @property int $id
 * @property int $status_id
 * @property float $amount
 * @property float $discount_amount
 * @property float|null $bonuses
 * @property string|null $promocode
 * @property int|null $user_id
 * @property string|null $error_text
 * @property int|null $project_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Orders newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Orders newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Orders query()
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereBonuses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereErrorText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders wherePromocode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereUserId($value)
 * @mixin \Eloquent
 * @property int $order_id
 * @property int|null $users_id
 * @property string|null $loyalty_operation_id
 * @property string|null $lenta_host
 * @property string|null $lenta_agent
 * @property-read \App\Models\Project|null $project
 * @property-read \App\Models\Users|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereLentaAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereLentaHost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereLoyaltyOperationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereUsersId($value)
 */
	class Orders extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Project.
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @mixin \Eloquent
 * @property int                             $id
 * @property string                          $name
 * @property string                          $token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \App\Models\ProjectConfig|null  $config
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUpdatedAt($value)
 */
	class Project extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProjectConfig.
 *
 * @property int                             $id
 * @property int                             $project_id
 * @property string                          $loyalty_system
 * @property string                          $host
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereHost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereLoyaltySystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $loyalty_key Идентификатор программы лояльности
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereLoyaltyKey($value)
 * @property string|null $lenta_host
 * @property string|null $lenta_agent
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereLentaAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereLentaHost($value)
 * @property string $agent
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereAgent($value)
 */
	class ProjectConfig extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User.
 *
 * @property \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property int|null                                                                                                  $notifications_count
 * @property \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[]                           $tokens
 * @property int|null                                                                                                  $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @mixin \Eloquent
 * @property int                             $id
 * @property string                          $name
 * @property string                          $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string                          $password
 * @property string|null                     $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Users
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $email
 * @property string|null $first
 * @property string|null $second
 * @property string|null $middle
 * @property string|null $phone
 * @property string|null $loyalty_uid
 * @property string|null $card_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Users newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Users newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Users query()
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereCardNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereFirst($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereLoyaltyUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereMiddle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereSecond($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Users whereUserId($value)
 */
	class Users extends \Eloquent {}
}

