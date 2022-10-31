<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderCarts.
 *
 * @property int                             $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int                             $orders_id
 * @property int                             $uid
 * @property float                           $price
 *
 * @method static \Illuminate\Database\Eloquent\Builder|OrderCarts newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderCarts newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderCarts query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderCarts whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderCarts whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderCarts whereOrdersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderCarts wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderCarts whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderCarts whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class OrderCarts extends Model
{
    use HasFactory;

    protected $guarded = [];
}
