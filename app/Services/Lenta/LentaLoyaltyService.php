<?php

declare(strict_types=1);

namespace App\Services\Lenta;

use App\Services\Contracts\LentaLoyaltyServiceInterface;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Response\OrderBonuses;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Response\OrderItem;

class LentaLoyaltyService implements LentaLoyaltyServiceInterface
{
    public function __construct(private LentaRequest $request)
    {
    }

    /**
     * @param OrderItem[] $orderItems
     */
    public function afterSpendingBonusesOrPromocode(
        bool $orderHasLoyalty,
        int $orderId,
        ?string $loyaltyUserIdentifier,
        array $orderItems
    ): void {
        if (!$orderHasLoyalty) {
            $this->request->setLoyalty($orderId, $loyaltyUserIdentifier);
        }

        $bonuses = $discount = 0;
        foreach ($orderItems as $orderItem) {
            $this->request->addLoyaltyBonuses($orderId, $orderItem);
            $bonuses += $orderItem->bonuses;
            $discount += $orderItem->discountedPrice;
        }

        if (0 == $bonuses && 0 == $discount) {
            $this->request->clearLoyalty($orderId);
        }
    }

    public function getSumLoyaltyBonuses(int $orderId): OrderBonuses
    {
        $data = $this->request->getSumLoyaltyBonuses($orderId);

        return new OrderBonuses($data['debt'], (float) $data['bonuses']);
    }

    public function afterConfirmOrder(int $orderId): void
    {
        $this->request->confirmLoyalty($orderId);
    }

    public function clearLoyalty(int $orderId): bool
    {
        return $this->request->clearLoyalty($orderId);
    }

    public function setLoyalty(int $orderId, ?string $loyaltyUserIdentifier): void
    {
        $this->request->setLoyalty($orderId, $loyaltyUserIdentifier);
    }
}
