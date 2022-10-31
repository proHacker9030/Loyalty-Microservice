<?php

declare(strict_types=1);

namespace App\Services;

use App\Enum\LoyaltyUserIdentifier;
use App\Services\Contracts\LentaLoyaltyServiceInterface;
use App\Services\Contracts\LoyaltyRulesInterface;

abstract class AbstractLoyalty
{
    public function __construct(
        public AbstractLoyaltyRequest $request,
        public LoyaltyRulesInterface $rules,
        public LentaLoyaltyServiceInterface $lentaService
    ) {
    }

    abstract protected function getUserIdentifierType(): ?string;

    abstract public function setFiscalCheck(
        int $orderId,
        float $bonuses,
        string $promocode = null
    ): int|string; // Регистрация продажи в лояльности перед покупкой

    abstract public function cancelFiscalCheck(
        int $orderId,
        float $bonuses,
        string|int $loyaltySystemOperationId = null
    ): bool; // Отменить фискальный чек

    abstract public function confirmOrder(
        int $orderId,
        float $bonuses,
        string|int $loyaltySystemOperationId = null
    ): bool|int|string; // Начислить баллы

    abstract public function refundCart(int $orderId, int $cartUid): bool; // Вернуть билет

    abstract public function refund(int $orderId, array $cartIds): bool; // Вернуть заказ

    public function getUserIdentifier(): ?string
    {
        return match ($this->getUserIdentifierType()) {
            LoyaltyUserIdentifier::PHONE->value => $this->request->getUser()->phone,
            LoyaltyUserIdentifier::CARD->value => $this->request->getUser()->cardNumber,
            LoyaltyUserIdentifier::UUID->value => $this->request->getUser()->loyaltyUid,
            default => null,
        };
    }
}
