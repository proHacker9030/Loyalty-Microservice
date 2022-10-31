<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\OrderCarts;
use App\Models\Orders;
use App\Models\Project;
use App\Models\Users;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Request\Cart;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Request\RequestData;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Response\OrderItem;

class OrderRepository
{
    /**
     * @param OrderItem[] $orderItems
     */
    public function createOrUpdate(
        RequestData $dto,
        int $status_id,
        array $orderItems = [],
        string $errorText = null
    ): void {
        $projectId = $this->getProjectId($dto->projectToken);
        $updateArray = [
            'order_id' => $dto->order->id,
            'status_id' => $status_id,
            'bonuses' => $dto->bonusesAmount,
            'error_text' => $errorText,
            'project_id' => $projectId,
            'loyalty_operation_id' => $dto->loyaltySystemOperationId,
            'lenta_host' => $dto->lentaHost,
            'lenta_agent' => $dto->lentaAgent,
        ];
        $order = Orders::firstOrNew(['order_id' => $dto->order->id, 'project_id' => $projectId]);

        if (!empty($orderItems)) {
            $discountAmount = $defaultAmount = 0;
            foreach ($orderItems as $item) {
                $discountAmount += $item->discountedPrice;
                $defaultAmount += $item->price;
            }
            $updateArray = array_merge([
                'discount_amount' => $discountAmount,
                'amount' => $defaultAmount - $discountAmount,
                'default_amount' => $defaultAmount
            ], $updateArray);
        }
        if (!empty($dto->promocode)) {
            $updateArray = array_merge(['promocode' => $dto->promocode], $updateArray);
        }
        if (empty($orderItems) && is_null($order->amount)) {
            $updateArray = array_merge(['amount' => $dto->order->amount], $updateArray);
            if (is_null($order->default_amount)) {
                $updateArray = array_merge(['default_amount' => $dto->order->amount], $updateArray);
            }
        }

        $user = $this->createOrUpdateUser($order, $dto);
        if (!is_null($user)) {
            $updateArray = array_merge(['user_id' => $user->id], $updateArray);
        }

        $order->fill($updateArray)->save();
        $this->updateCarts($order, $dto->order->carts ?? []);
    }

    public function updateStatus(int $orderId, int $statusId, ?string $projectToken): void
    {
        $projectId = $this->getProjectId($projectToken);

        Orders::where('order_id', $orderId)
            ->where('project_id', $projectId)
            ->update(['status_id' => $statusId, 'error_text' => null]);
    }

    public function updateError(int $orderId, string $errorText, ?string $projectToken): void
    {
        $projectId = $this->getProjectId($projectToken);

        Orders::where('order_id', $orderId)
            ->where('project_id', $projectId)
            ->update(['error_text' => $errorText]);
    }

    private function createOrUpdateUser(Orders $order, RequestData $dto): Users|null
    {
        if (empty($dto->user->id) && empty($dto->user->email) && empty($dto->user->loyaltyUid)) {
            return null; // Запрос пришел от неавторизованного юзера, сохранять тут нечего
        }

        $userArray = [
            'user_id' => $dto->user->id,
            'email' => $dto->user->email,
            'first' => $dto->user->first,
            'second' => $dto->user->second,
            'middle' => $dto->user->middle,
            'phone' => $dto->user->phone,
            'loyalty_uid' => $dto->user->loyaltyUid,
            'card_number' => $dto->user->cardNumber,
        ];
        if (is_null($order->user_id)) {
            $user = Users::firstOrNew(
                ['user_id' => $dto->user->id, 'email' => $dto->user->email]
            );
        } else {
            $user = $order->user;
        }
        $user->fill($userArray);
        $user->save($userArray);

        return $user;
    }

    public function getStatus(int $orderId, ?string $projectToken): int|null
    {
        $projectId = $this->getProjectId($projectToken);

        $order = Orders::where('order_id', $orderId)
            ->where('project_id', $projectId)
            ->first();

        return $order?->status_id;
    }

    public function updateTransactionId(int $orderId, int|string $transactionId, ?string $projectToken): void
    {
        $projectId = $this->getProjectId($projectToken);

        Orders::where('order_id', $orderId)
            ->where('project_id', $projectId)
            ->update(['loyalty_operation_id' => $transactionId]);
    }

    public function getTransactionId(int $orderId, ?string $projectToken): int|string|null
    {
        $projectId = $this->getProjectId($projectToken);

        $order = Orders::where('order_id', $orderId)
            ->where('project_id', $projectId)
            ->first();

        return $order->loyalty_operation_id;
    }

    private function getProjectId(?string $projectToken): int|null
    {
        if (is_null($projectToken)) {
            $projectId = null;
        } else {
            $project = Project::where('token', $projectToken)->first();
            $projectId = !is_null($project) ? $project->id : null;
        }

        return $projectId;
    }

    public function get(int $orderId, ?string $projectToken): Orders|null
    {
        $projectId = $this->getProjectId($projectToken);

        return Orders::where('order_id', $orderId)
            ->where('project_id', $projectId)
            ->first();
    }

    private function updateCarts(Orders $order, array $carts): void
    {
        /** @var Cart $cart */
        foreach ($carts as $cart) {
            $orderCart = OrderCarts::firstOrNew(['uid' => $cart->id]);
            $orderCart->uid = $cart->id;
            $orderCart->orders_id = $order->id;
            $orderCart->price = $cart->price;
            $orderCart->save();
        }
    }
}
