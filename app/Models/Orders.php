<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\OrderStatuses;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

/**
 * App\Models\Orders.
 *
 * @property int                             $id
 * @property int                             $order_id
 * @property int                             $status_id
 * @property float                           $amount
 * @property float                           $discount_amount
 * @property float|null                      $bonuses
 * @property string|null                     $promocode
 * @property int|null                        $user_id
 * @property string|null                     $error_text
 * @property string|null                     $loyalty_operation_id
 * @property string|null                     $lenta_host
 * @property string|null                     $lenta_agent
 * @property int|null                        $project_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property float                           $default_amount       Исходная цена (до скидок)
 * @property \App\Models\Project|null        $project
 * @property \App\Models\Users|null          $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Orders newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Orders newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Orders query()
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereBonuses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereDefaultAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereErrorText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereLentaAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereLentaHost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereLoyaltyOperationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders wherePromocode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereUserId($value)
 *
 * @mixin \Eloquent
 *
 * @property int|null                                                          $carts_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\OrderCarts[] $carts
 */
class Orders extends Model
{
    use HasFactory;
    use CrudTrait;
    use Prunable;

    public const PRUNING_STATUSES = [OrderStatuses::CANCELED, OrderStatuses::CALCULATED, OrderStatuses::CALCULATE_FAILED];

    protected $guarded = [];

    public function project()
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }

    public function user()
    {
        return $this->hasOne(Users::class, 'id', 'user_id');
    }

    public function carts()
    {
        return $this->hasMany(OrderCarts::class, 'orders_id', 'id');
    }

    public function prunable()
    {
        return static::whereIn('status_id', self::PRUNING_STATUSES)
            ->where('created_at', '<=', now()->subDays(config('database.orders_keep_days', 14)));
    }

    protected function pruning(): void
    {
        if (is_null($this->user)) {
            return;
        }

        $isUserConnectedToOtherOrders = static::where('id', '!=', $this->id)
            ->where('user_id', $this->user->id)
            ->exists();
        if (!$isUserConnectedToOtherOrders) {
            $this->user->delete();
        }
    }
}
