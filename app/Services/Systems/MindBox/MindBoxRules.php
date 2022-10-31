<?php

declare(strict_types=1);

namespace App\Services\Systems\MindBox;

use App\Services\Contracts\LoyaltyRulesInterface;

class MindBoxRules implements LoyaltyRulesInterface
{
    public function isNeedRetryNullLoyalty(int $cartsQuantity): bool
    {
        return true;
    }

    public function isNeedCancelLoyalty(float $bonuses, int|string|null $loyaltySystemOperationId): bool
    {
        return !is_null($loyaltySystemOperationId);
    }
}
