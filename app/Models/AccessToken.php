<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AccessToken.
 *
 * @property int                             $id
 * @property string                          $name
 * @property string                          $token
 * @property string|null                     $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class AccessToken extends Model
{
    use HasFactory;

    protected $table = 'access_tokens';

    protected $guarded = [];

    public static function generateHash(string $token)
    {
        return hash('sha256', $token);
    }
}
