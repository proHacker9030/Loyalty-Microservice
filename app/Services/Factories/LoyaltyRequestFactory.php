<?php

declare(strict_types=1);

namespace App\Services\Factories;

use App\Services\AbstractLoyaltyRequest;
use App\Services\Config\ConfigLoader;
use Illuminate\Contracts\Container\BindingResolutionException;

class LoyaltyRequestFactory
{
    /**
     * @throws BindingResolutionException
     */
    public function factory(string $loyaltySystem, array $config): AbstractLoyaltyRequest
    {
        $instanceClass = sprintf(
            '%s\%s\%s',
            ConfigLoader::getLoyaltySystemsNamespace(),
            ucfirst($loyaltySystem),
            ucfirst($loyaltySystem) . 'Request'
        );

        return app()->makeWith($instanceClass, $config);
    }
}
