<?php

declare(strict_types=1);

namespace App\Services\Operations;

use App\Enum\OrderStatuses;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Response\OrderItem;

class BonusesService extends AbstractLoyaltyService
{
    public const GET_BONUSES_REQUEST_TIMEOUT = 5;

    public function getAvailableBonuses(): float
    {
        $this->loyalty->request->setRequestTimeout(self::GET_BONUSES_REQUEST_TIMEOUT);
        if (!empty($this->dto->order)) {
            $data = $this->loyalty->getAvailableBonusByOrderId($this->dto->order->id, $this->dto->order->amount);
        } else {
            $data = $this->loyalty->getAvailableBonuses();
        }

        return $data;
    }

    /**
     * @return OrderItem[]
     *
     * @throws \Exception
     */
    public function spendBonuses(): array
    {
        $isSentToLoyalty = false;
        try {
            $data = $this->loyalty->spendBonuses($this->dto->order->id, $this->dto->bonusesAmount);
            $isSentToLoyalty = true;
            $this->loyalty->lentaService->afterSpendingBonusesOrPromocode(
                $this->dto->order->has_loyalty,
                $this->dto->order->id,
                $this->loyalty->getUserIdentifier(),
                $data
            );
            $status = $this->dto->bonusesAmount > 0 ? OrderStatuses::CALCULATED : OrderStatuses::CANCELED;
            $this->orderRepository->createOrUpdate($this->dto, $status, $data);
        } catch (\Exception $exception) {
            if ($isSentToLoyalty) {
                $this->loyalty->unSpendBonuses($this->dto->order->id, $this->dto->bonusesAmount);
            }
            $this->orderRepository->createOrUpdate(
                $this->dto,
                OrderStatuses::CALCULATE_FAILED,
                [],
                $exception->getMessage()
            );
            throw $exception;
        }

        return $data;
    }

    public function respendBonuses(): array|bool
    {
        $data = true;
        if ($this->loyalty->rules->isNeedRetryNullLoyalty(count($this->dto->order->carts))) {
            $data = $this->loyalty->reSpendBonuses(
                $this->dto->order->id,
                $this->dto->bonusesAmount,
                count($this->dto->order->carts)
            );
            $this->loyalty->lentaService->afterSpendingBonusesOrPromocode(
                $this->dto->order->has_loyalty,
                $this->dto->order->id,
                $this->loyalty->getUserIdentifier(),
                $data
            );
            $this->orderRepository->createOrUpdate($this->dto, OrderStatuses::CALCULATED, $data);
        }

        return $data;
    }
}
