<?php

declare(strict_types=1);

namespace App\Services\Contracts;

interface LoyaltyRulesInterface
{
    public function isNeedRetryNullLoyalty(int $cartsQuantity): bool;

    public function isNeedCancelLoyalty(float $bonuses, int|string|null $loyaltySystemOperationId): bool;
}
