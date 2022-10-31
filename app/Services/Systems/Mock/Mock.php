<?php

declare(strict_types=1);

namespace App\Services\Systems\Mock;

use App\Enum\LoyaltyUserIdentifier;
use App\Services\AbstractLoyalty;
use App\Services\Contracts\LoyaltyTypes\BonusesInterface;
use App\Services\Contracts\LoyaltyTypes\PromoInterface;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Response\OrderItem;

class Mock extends AbstractLoyalty implements BonusesInterface, PromoInterface
{
    public function getAvailableBonusByOrderId(int $orderId, float $orderAmount = null): float
    {
        return 500.0;
    }

    public function getAvailableBonuses(): float
    {
        return 250.0;
    }

    public function spendBonuses(int $orderId, float $bonuses): array
    {
        $price = 5000;

        return [
            new OrderItem(123, 'bonuses', $price, $bonuses, $bonuses),
        ];
    }

    public function reSpendBonuses(int $orderId, float $bonuses, int $cartsCount): array
    {
        $price = 5000;

        return [
            new OrderItem(123, 'bonuses', $price, $price - $bonuses, $bonuses),
        ];
    }

    public function unSpendBonuses(int $orderId, float $bonuses): bool
    {
        // TODO: Implement unSpendBonuses() method.
    }

    public function setFiscalCheck(int $orderId, float $bonuses, string $promocode = null): int|string
    {
        return $orderId;
    }

    public function cancelFiscalCheck(int $orderId, float $bonuses, string|int $loyaltySystemOperationId = null): bool
    {
        return true;
    }

    public function confirmOrder(int $orderId, float $bonuses, string|int $loyaltySystemOperationId = null): bool|int|string
    {
        return true;
    }

    public function refundCart(int $orderId, int $cartUid): bool
    {
        return true;
    }

    public function refund(int $orderId, array $cartIds): bool
    {
        return true;
    }

    public function applyCode(?string $code, int $orderId): array
    {
        $price = 5000;

        return [
            new OrderItem(123, 'promo', $price, 250, 0, 250),
        ];
    }

    public function applyCartCode(string $code, int $orderId, int $cartId): void
    {
        // TODO: Implement applyCartCode() method.
    }

    public function cancelCode(string $code, int $orderId): array
    {
        $price = 5000;

        return [
            new OrderItem(123, 'promo', $price, 0, 0, 0),
        ];
    }

    public function cancelCartCode(string $code, int $orderId, int $cartId): void
    {
        // TODO: Implement cancelCartCode() method.
    }

    protected function getUserIdentifierType(): ?string
    {
        return LoyaltyUserIdentifier::CARD->value;
    }
}
