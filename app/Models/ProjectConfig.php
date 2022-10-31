<?php

declare(strict_types=1);

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProjectConfig.
 *
 * @property int                             $id
 * @property int                             $project_id
 * @property string                          $loyalty_system
 * @property string                          $host
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereHost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereLoyaltySystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 *
 * @property string $loyalty_key Идентификатор программы лояльности
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereLoyaltyKey($value)
 *
 * @property string|null $lenta_host
 * @property string|null $lenta_agent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereLentaAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereLentaHost($value)
 *
 * @property string $agent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereAgent($value)
 */
class ProjectConfig extends Model
{
    use HasFactory;
    use CrudTrait;

    protected $fillable = [
        'host',
        'agent',
        'loyalty_system',
        'loyalty_key',
        'lenta_agent',
        'lenta_host',
    ];
}
