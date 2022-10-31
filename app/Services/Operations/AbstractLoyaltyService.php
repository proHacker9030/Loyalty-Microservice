<?php

declare(strict_types=1);

namespace App\Services\Operations;

use App\Models\Orders;
use App\Repositories\OrderRepository;
use App\Services\AbstractLoyalty;
use App\Services\Factories\LoyaltyFactory;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Request\Cart;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Request\Order;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Request\RequestData;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Request\User;

abstract class AbstractLoyaltyService
{
    protected AbstractLoyalty $loyalty;
    protected RequestData $dto;
    protected OrderRepository $orderRepository;

    public function setDto(RequestData $dto): void
    {
        $this->dto = $dto;
    }

    public function __construct(?AbstractLoyalty $loyalty, int $orderId = null)
    {
        if (!is_null($orderId)) {
            $this->dto = $this->buildDtoByOrder($orderId);
        }

        if (!is_null($loyalty)) {
            $this->loyalty = $loyalty;
        } else {
            /** @var LoyaltyFactory $loyaltyFactory */
            $loyaltyFactory = app()->make(LoyaltyFactory::class);
            $loyalty = $loyaltyFactory->factory(
                $this->dto->lentaHost,
                $this->dto->lentaAgent,
                $this->dto->projectToken
            );
            $this->loyalty = $loyalty;
            if (!is_null($this->dto->user)) {
                $this->loyalty->request->setUser($this->dto->user);
            }
        }

        $this->orderRepository = new OrderRepository();
    }

    protected function buildDtoByOrder(int $orderId): RequestData
    {
        $dto = new RequestData();
        $order = Orders::findOrFail($orderId);
        $user = $order->user;
        $project = $order->project;

        $dtoOrder = new Order($order->order_id, $order->amount, false);
        $dto->setOrder($dtoOrder);

        if (!is_null($user)) {
            $dtoUser = new User(
                $user->id ?? 0,
                $user->first ?? '',
                $user->second ?? '',
                $user->email ?? '',
                $user->phone,
                $user->card_number,
                $user->loyalty_uid,
                $user->middle
            );
            $dto->setUser($dtoUser);
        }

        $dto->setLentaParams($order->lenta_host, $order->lenta_agent);
        $dto->setLoyaltySystemOperationId($order->loyalty_operation_id);
        $dto->fillBonusesData($order->bonuses ?? 0);
        $dto->fillPromocodeData($order->promocode);

        if (!is_null($project)) {
            $dto->setProjectToken($project->token);
            if (!is_null($project->config->lenta_host)) {
                $dto->setLentaParams(
                    $project->config->lenta_host,
                    $project->config->lenta_agent ?? $order->lenta_agent
                );
            }
        }

        $dto->order->carts = [];
        foreach ($order->carts as $cart) {
            $dto->order->carts[] = new Cart($cart->uid, $cart->price);
        }

        return $dto;
    }
}
