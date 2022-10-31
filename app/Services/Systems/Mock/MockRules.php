<?php

declare(strict_types=1);

namespace App\Services\Systems\Mock;

use App\Services\Contracts\LoyaltyRulesInterface;

class MockRules implements LoyaltyRulesInterface
{
    public function isNeedRetryNullLoyalty(int $cartsQuantity): bool
    {
        return true;
    }

    public function isNeedRetryLoyalty(float $bonuses): bool
    {
        return true;
    }

    public function isNeedCancelLoyalty(float $bonuses, int|string|null $loyaltySystemOperationId): bool
    {
        return true;
    }
}
