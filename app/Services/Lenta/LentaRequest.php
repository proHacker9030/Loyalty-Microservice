<?php

declare(strict_types=1);

namespace App\Services\Lenta;

use App\Exceptions\LentaException;
use App\Services\Request\Soap;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Response\OrderItem;

class LentaRequest
{
    private Soap $soap;

    public function __construct(string $host, private string $agentKey, private string $loyaltyKey)
    {
        $this->soap = new Soap($host, 'Lenta');
    }

    public function setLoyalty(int $orderId, ?string $loyaltyUserIdentifier): void
    {
        $params = ['key' => $this->loyaltyKey, 'order_id' => $orderId, 'card_num' => $loyaltyUserIdentifier];
        $function = 'toSetLoyalty';

        $result = $this->execute($function, $params);

        if (0 != $result) {
            $this->log($result, $function, $params);
            throw new LentaException('Ошибка передачи документа в программу лояльности.');
        }
    }

    public function addLoyaltyBonuses(int $orderId, OrderItem $orderItem): void
    {
        $params = [
            'key' => $this->loyaltyKey,
            'order_id' => $orderId,
            'index' => $orderItem->id,
            'summ' => $orderItem->bonuses,
        ];
        if ($orderItem->discountedPrice && $orderItem->promoDiscountedPrice) {
            $params['discount'] = $orderItem->discountedPrice;
        }
        $function = 'toAddLoyaltyBonuses';

        $result = $this->execute($function, $params);

        if (0 != $result) {
            $this->log($result, $function, $params);
            throw new LentaException('Ошибка регистрации бонусов.');
        }
    }

    public function getSumLoyaltyBonuses(int $orderId): array
    {
        $params = ['key' => $this->loyaltyKey, 'order_id' => $orderId];
        $function = 'getSummLoyaltyBonuses';

        $result = $this->execute($function, $params);

        if (isset($result[0]) && $result[0]->state > 100) {
            $this->log($result, $function, $params);
            throw new LentaException('Ошибка получения суммы.');
        }

        return $result;
    }

    public function confirmLoyalty(int $orderId): void
    {
        $params = ['key' => $this->loyaltyKey, 'order_id' => $orderId];
        $function = 'toConfirmLoyalty';

        $result = $this->execute($function, $params);

        if (0 != $result) {
            $this->log($result, $function, $params);
            throw new LentaException('Ошибка подтверждения передачи документа в программу лояльности.');
        }
    }

    public function clearLoyalty(int $orderId): bool
    {
        $params = ['key' => $this->loyaltyKey, 'order_id' => $orderId];
        $function = 'toClearLoyaltyBonuses';

        $result = $this->execute($function, $params);

        if (0 != $result) {
            $this->log($result, $function, $params);
            throw new LentaException('Ошибка отмены списания баллов.');
        }

        return true;
    }

    private function execute(string $function, array $params = []): mixed
    {
        array_unshift($params, $this->agentKey);

        \Log::info('Пытаемся произвести запрос в БО (' . $function . ')', ['Lenta', $function, $params]);

        $res = $this->soap->execute($function, $params);

        \Log::info('Ответ от БО (' . $function . '): ' . print_r($res, true), ['Lenta', $function, $params]);

        return $res;
    }

    private function log(mixed $result, string $function, array $params): void
    {
        \Log::error('Ошибка БО: ' . print_r($result, true), ['Lenta', $function, $params]);
    }
}
