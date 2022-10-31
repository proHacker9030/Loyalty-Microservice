<?php

declare(strict_types=1);

namespace App\Services\Factories;

use App\Enum\LoyaltySystems;
use App\Services\Contracts\LentaLoyaltyServiceInterface;
use App\Services\Lenta\LentaLoyaltyService;
use App\Services\Lenta\LentaRequest;
use App\Services\Lenta\MockLentaLoyaltyService;
use Illuminate\Contracts\Container\BindingResolutionException;

class LentaServiceFactory
{
    public function __construct()
    {
    }

    /**
     * @throws BindingResolutionException
     */
    public function factory(string $host, string $agent, string $loyaltyKey, string $loyaltySystemName): LentaLoyaltyServiceInterface
    {
        if (LoyaltySystems::MOCK === $loyaltySystemName) {
            return new MockLentaLoyaltyService();
        }

        $request = app()->makeWith(
            LentaRequest::class,
            ['host' => $host, 'agentKey' => $agent, 'loyaltyKey' => $loyaltyKey]
        );

        $instance = app()->makeWith(
            LentaLoyaltyService::class,
            ['request' => $request]
        );

        return $instance;
    }
}
