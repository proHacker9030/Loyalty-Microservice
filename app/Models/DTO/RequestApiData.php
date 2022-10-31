<?php

declare(strict_types=1);

namespace App\Models\DTO;

use Illuminate\Http\Request;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Request\Cart;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Request\Order;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Request\RequestData;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Request\User;

class RequestApiData extends RequestData
{
    public function load(Request $request): void
    {
        $this->fillRequiredData(
            $request->input(RequestData::ENV_KEY),
            $this->getUser($request),
            request()->input(RequestData::LENTA_HOST_KEY),
            request()->input(RequestData::LENTA_AGENT_KEY),
        );
        if ($request->input(RequestData::ORDER_KEY, false)) {
            $this->setOrder($this->getOrder($request));
        }
        $projectToken = $request->input(RequestData::PROJECT_TOKEN_KEY, false);
        if ($projectToken) {
            $this->setProjectToken($projectToken);
        }
    }

    public function getOrder(Request $request): Order
    {
        $orderData = $request->input(RequestData::ORDER_KEY);
        $order = new Order(
            (int) $orderData['id'],
            (float) $orderData['amount'],
            (bool) $orderData['has_loyalty']
        );
        if (isset($orderData['carts'])) {
            foreach ($orderData['carts'] as $cart) {
                $cart = new Cart($cart['id'], $cart['price'] ?? null);
                $order->addCart($cart);
            }
        }

        return $order;
    }

    public function getUser(Request $request): User
    {
        $userData = $request->input(RequestData::USER_KEY);

        return new User(
            (int) $userData['id'] ?? 0,
            $userData['first'] ?? '',
            $userData['second'] ?? '',
            $userData['email'] ?? '',
            $userData['phone'] ?? '',
            $userData['cardNumber'] ?? null,
            $userData['loyaltyUid'] ?? null,
            $userData['middle'] ?? null,
        );
    }
}
