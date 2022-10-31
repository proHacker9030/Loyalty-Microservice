<?php

declare(strict_types=1);

namespace App\Services\Operations;

use App\Enum\OrderStatuses;
use App\Exceptions\InvalidValueException;
use App\Helpers\OrderHelper;
use App\Repositories\OrderAttemptsRepository;
use App\Services\AbstractLoyalty;

class PayService extends AbstractLoyaltyService
{
    private OrderAttemptsRepository $orderAttemptsRepository;

    /** Maximum attempts to cancel order in loyalty system. */
    public const MAX_CANCEL_ATTEMPTS = 3;

    public function __construct(?AbstractLoyalty $loyalty, int $orderId = null)
    {
        parent::__construct($loyalty, $orderId);
        $this->orderAttemptsRepository = new OrderAttemptsRepository();
    }

    public function confirmOrder(): bool|int|string
    {
        $currentStatus = $this->orderRepository->getStatus($this->dto->order->id, $this->dto->projectToken);
        if (app()->environment(['testing'])) {
            $this->orderRepository->createOrUpdate($this->dto, OrderStatuses::PREPARED_FOR_PAY);
            $currentStatus = OrderStatuses::PREPARED_FOR_PAY;
        }

        if (!OrderHelper::isNeedToConfirm($currentStatus)) {
            return true;
        }

        if (OrderStatuses::PREPARED_FOR_PAY === $currentStatus) {
            $data = $this->loyalty->confirmOrder(
                $this->dto->order->id,
                $this->dto->bonusesAmount,
                $this->dto->loyaltySystemOperationId
            );
            $this->orderRepository->updateStatus(
                $this->dto->order->id,
                OrderStatuses::CONFIRMED_LOYALTY,
                $this->dto->projectToken
            );
        } else {
            $data = true;
        }

        $this->loyalty->lentaService->afterConfirmOrder($this->dto->order->id);
        $this->orderRepository->updateStatus($this->dto->order->id, OrderStatuses::CONFIRMED, $this->dto->projectToken);

        return $data;
    }

    public function setFiscalCheck(): int|string
    {
        $currentStatus = $this->orderRepository->getStatus($this->dto->order->id, $this->dto->projectToken);
        if (is_null($currentStatus)) {
            $this->orderRepository->createOrUpdate($this->dto, OrderStatuses::CALCULATED);
            $currentStatus = OrderStatuses::CALCULATED;
        }

        if (!OrderHelper::isNeedToSetFiscalCheck($currentStatus)) {
            if ($transactionId = $this->orderRepository->getTransactionId($this->dto->order->id, $this->dto->projectToken)) {
                return $transactionId;
            }
        }

        if (is_null($this->dto->user)) {
            throw new InvalidValueException('Для отправки фискального чека требуются данные пользователя');
        }

        if (OrderStatuses::CALCULATED === $currentStatus) {
            if (!$this->dto->order->has_loyalty) {
                $this->loyalty->lentaService->setLoyalty($this->dto->order->id, $this->loyalty->getUserIdentifier());
            }
            $this->orderRepository->updateStatus(
                $this->dto->order->id,
                OrderStatuses::PREPARED_FOR_PAY_LENTA,
                $this->dto->projectToken
            );
        }

        $data = $this->loyalty->setFiscalCheck($this->dto->order->id, $this->dto->bonusesAmount, $this->dto->promocode);
        $this->orderRepository->updateStatus(
            $this->dto->order->id,
            OrderStatuses::PREPARED_FOR_PAY,
            $this->dto->projectToken
        );
        $this->orderRepository->updateTransactionId($this->dto->order->id, $data, $this->dto->projectToken);

        return $data;
    }

    public function cancelFiscalCheck(): bool
    {
        if (app()->environment(['testing'])) {
            $this->orderRepository->createOrUpdate($this->dto, OrderStatuses::CALCULATED);
        }

        $currentOrder = $this->orderRepository->get($this->dto->order->id, $this->dto->projectToken);
        if (OrderStatuses::CANCELED === $currentOrder->status_id) {
            return true;
        }

        $currentAttempts = $this->orderAttemptsRepository->getAttemptsQuantity($currentOrder->id);
        if ($this->loyalty->rules->isNeedCancelLoyalty($this->dto->bonusesAmount, $this->dto->loyaltySystemOperationId)
            && $currentAttempts <= self::MAX_CANCEL_ATTEMPTS) {
            try {
                $this->loyalty->cancelFiscalCheck(
                    $this->dto->order->id,
                    $this->dto->bonusesAmount,
                    $this->dto->loyaltySystemOperationId
                );
            } catch (\Exception $exception) {
                $this->orderAttemptsRepository->createOrUpdate($currentOrder, OrderStatuses::CANCELED);
                throw $exception;
            }
        }
        $this->orderRepository->updateStatus($this->dto->order->id, OrderStatuses::CANCELED, $this->dto->projectToken);

        return true;
    }

    public function forceCancelFiscalCheck(): void
    {
        $this->orderRepository->updateStatus($this->dto->order->id, OrderStatuses::CANCELED, $this->dto->projectToken);
    }
}
