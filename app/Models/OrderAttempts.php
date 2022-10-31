<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderAttempts.
 *
 * @property int                             $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int                             $attempts_count
 * @property int                             $status_id_from
 * @property int                             $status_id_to
 *
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAttempts newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAttempts newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAttempts query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAttempts whereAttemptsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAttempts whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAttempts whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAttempts whereStatusIdFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAttempts whereStatusIdTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAttempts whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 *
 * @property int $orders_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAttempts whereOrdersId($value)
 */
class OrderAttempts extends Model
{
    use HasFactory;

    protected $guarded = [];
}
