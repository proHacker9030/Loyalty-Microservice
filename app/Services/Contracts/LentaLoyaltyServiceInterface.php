<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use Infomatika\Microservice\Sdk\Loyalty\Dto\Response\OrderBonuses;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Response\OrderItem;

interface LentaLoyaltyServiceInterface
{
    /**
     * @param OrderItem[] $orderItems
     */
    public function afterSpendingBonusesOrPromocode(
        bool $orderHasLoyalty,
        int $orderId,
        ?string $loyaltyUserIdentifier,
        array $orderItems
    ): void;

    public function getSumLoyaltyBonuses(int $orderId): OrderBonuses;

    public function afterConfirmOrder(int $orderId): void;

    public function setLoyalty(int $orderId, ?string $loyaltyUserIdentifier): void;

    public function clearLoyalty(int $orderId): bool;
}
