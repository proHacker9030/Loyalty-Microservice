<?php

declare(strict_types=1);

namespace App\Services\Operations;

use App\Enum\OrderStatuses;

class RefundService extends AbstractLoyaltyService
{
    public function refund(): bool
    {
        $data = $this->loyalty->refund($this->dto->order->id, array_column($this->dto->order->carts, 'id'));
        $this->orderRepository->updateStatus($this->dto->order->id, OrderStatuses::REFUNDED, $this->dto->projectToken);

        return $data;
    }

    public function refundCart(): bool
    {
        $cartId = $this->dto->order->carts[0]->id;
        $data = $this->loyalty->refundCart($this->dto->order->id, $cartId);
        $this->orderRepository->updateStatus(
            $this->dto->order->id,
            OrderStatuses::PARTIAL_REFUNDED,
            $this->dto->projectToken
        );

        return $data;
    }
}
