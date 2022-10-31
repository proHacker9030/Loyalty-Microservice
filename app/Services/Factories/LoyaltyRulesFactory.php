<?php

declare(strict_types=1);

namespace App\Services\Factories;

use App\Services\Config\ConfigLoader;
use App\Services\Contracts\LoyaltyRulesInterface;
use Illuminate\Contracts\Container\BindingResolutionException;

class LoyaltyRulesFactory
{
    /**
     * @throws BindingResolutionException
     */
    public function factory(string $loyaltySystem): LoyaltyRulesInterface
    {
        $instanceClass = sprintf(
            '%s\%s\%s',
            ConfigLoader::getLoyaltySystemsNamespace(),
            ucfirst($loyaltySystem),
            ucfirst($loyaltySystem) . 'Rules'
        );

        return app()->make($instanceClass);
    }
}
