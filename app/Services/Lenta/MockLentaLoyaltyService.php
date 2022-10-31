<?php

declare(strict_types=1);

namespace App\Services\Lenta;

use App\Services\Contracts\LentaLoyaltyServiceInterface;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Response\OrderBonuses;

class MockLentaLoyaltyService implements LentaLoyaltyServiceInterface
{
    public function afterSpendingBonusesOrPromocode(bool $orderHasLoyalty, int $orderId, ?string $loyaltyUserIdentifier, array $orderItems): void
    {
        return;
    }

    public function getSumLoyaltyBonuses(int $orderId): OrderBonuses
    {
        return new OrderBonuses(5000, 250);
    }

    public function afterConfirmOrder(int $orderId): void
    {
        return;
    }

    public function clearLoyalty(int $orderId): bool
    {
        return true;
    }

    public function setLoyalty(int $orderId, ?string $loyaltyUserIdentifier): void
    {
        return;
    }
}
