<?php

declare(strict_types=1);

namespace App\Services\Contracts\LoyaltyTypes;

use Infomatika\Microservice\Sdk\Loyalty\Dto\Response\OrderItem;

interface BonusesInterface
{
    public function getAvailableBonusByOrderId(
        int $orderId,
        float $orderAmount = null
    ): float;  // Получить доступное кол-во бонусов на заказ

    public function getAvailableBonuses(): float; // Получить доступное кол-во бонусов

    /**
     * @return OrderItem[]
     */
    public function spendBonuses(int $orderId, float $bonuses): array; // Списать баллы

    /**
     * @return OrderItem[]
     */
    public function reSpendBonuses(int $orderId, float $bonuses, int $cartsCount): array; // Пересчитать баллы

    public function unSpendBonuses(int $orderId, float $bonuses): bool; // Отменить списание баллов
}
