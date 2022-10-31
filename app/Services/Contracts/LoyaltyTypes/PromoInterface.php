<?php

declare(strict_types=1);

namespace App\Services\Contracts\LoyaltyTypes;

use Infomatika\Microservice\Sdk\Loyalty\Dto\Response\OrderItem;

interface PromoInterface
{
    /**
     * @return OrderItem[]
     */
    public function applyCode(?string $code, int $orderId): array;

    public function applyCartCode(string $code, int $orderId, int $cartId);

    /**
     * @return OrderItem[]
     */
    public function cancelCode(string $code, int $orderId): array;

    public function cancelCartCode(string $code, int $orderId, int $cartId);
}
