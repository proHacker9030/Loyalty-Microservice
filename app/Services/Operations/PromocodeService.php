<?php

declare(strict_types=1);

namespace App\Services\Operations;

use App\Enum\OrderStatuses;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Response\OrderItem;

class PromocodeService extends AbstractLoyaltyService
{
    /**
     * @return OrderItem[]
     *
     * @throws \Exception
     */
    public function applyCode()
    {
        $isSentToLoyalty = false;
        try {
            $data = $this->loyalty->applyCode($this->dto->promocode, $this->dto->order->id);
            $isSentToLoyalty = true;
            $this->loyalty->lentaService->afterSpendingBonusesOrPromocode(
                $this->dto->order->has_loyalty,
                $this->dto->order->id,
                $this->loyalty->getUserIdentifier(),
                $data
            );
            $this->orderRepository->createOrUpdate($this->dto, OrderStatuses::CALCULATED, $data);
        } catch (\Exception $exception) {
            if ($isSentToLoyalty) {
                $this->loyalty->cancelCode($this->dto->promocode, $this->dto->order->id);
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

    /**
     * @return OrderItem[]
     */
    public function cancelCode()
    {
        $data = $this->loyalty->cancelCode($this->dto->promocode, $this->dto->order->id);
        $this->loyalty->lentaService->afterSpendingBonusesOrPromocode(
            $this->dto->order->has_loyalty,
            $this->dto->order->id,
            $this->loyalty->getUserIdentifier(),
            $data
        );
        $this->orderRepository->createOrUpdate($this->dto, OrderStatuses::CANCELED, $data);

        return $data;
    }
}
