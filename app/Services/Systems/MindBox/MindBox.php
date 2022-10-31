<?php

declare(strict_types=1);

namespace App\Services\Systems\MindBox;

use App\Services\AbstractLoyalty;
use App\Services\Contracts\LoyaltyTypes\BonusesInterface;
use App\Services\Contracts\LoyaltyTypes\PromoInterface;
use GuzzleHttp\Exception\GuzzleException;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Response\OrderItem;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class MindBox extends AbstractLoyalty implements BonusesInterface, PromoInterface
{
    public const NEW_STATUS = 'New';
    public const PAY_STATUS = 'Paid';
    public const CANCEL_STATUS = 'Canceled';
    public const RETURN_STATUS = 'Returned';
    public const USE_STATUS = 'Used';

    public function getAvailableBonusByOrderId(int $orderId, float $orderAmount = null): float
    {
        $res = $this->callCalculateCart($orderId, $orderAmount);

        $xml = $res['doc_items'];
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><root>' . $xml . '</root>');
        $bonuses = 0;
        foreach ($xml->document->item as $item) {
            $bonuses += (float) $item->attributes()->bonus_discounted_price;
        }

        return $bonuses;
    }

    public function spendBonuses(int $orderId, float $bonuses): array
    {
        $res = $this->callCalculateCart($orderId, $bonuses);

        return $this->getItems($res['doc_items']);
    }

    public function unSpendBonuses(int $orderId, float $bonuses): bool
    {
        $this->callCalculateCart($orderId, 0);

        return true;
    }

    /**
     * @throws GuzzleException
     */
    public function setFiscalCheck(int $orderId, float $bonuses, string $promocode = null): int|string
    {
        $params = ['orderid' => $orderId, 'promocode' => $promocode, 'bonus' => $bonuses];
        $user = $this->request->getUser();
        if (!empty($user->id)) {
            $params = array_merge(['customerid' => $user->id], $params);
        }
        $res = $this->request->execute('BeginTransaction', $params);

        return $res['transaction_id'];
    }

    /**
     * @throws GuzzleException
     */
    public function cancelFiscalCheck(int $orderId, float $bonuses, string|int $loyaltySystemOperationId = null): bool
    {
        $params = ['transactionid' => $loyaltySystemOperationId, 'orderid' => $orderId];
        $this->request->execute('RollbackTransaction', $params);
        $this->updateOrderStatus($orderId, self::CANCEL_STATUS);

        return true;
    }

    /**
     * @throws GuzzleException
     */
    public function confirmOrder(int $orderId, float $bonuses, string|int $loyaltySystemOperationId = null): bool|int|string
    {
        $params = ['transactionid' => $loyaltySystemOperationId, 'orderid' => $orderId];
        $this->request->execute('CommitTransaction', $params);
        $this->updateOrderStatus($orderId, self::PAY_STATUS);

        return true;
    }

    /**
     * @throws GuzzleException
     */
    public function refundCart(int $orderId, int $cartUid): bool
    {
        $params = ['status' => self::RETURN_STATUS, 'orderid' => $orderId, 'itemid' => $cartUid];
        $this->request->execute('UpdateItemStatus', $params);

        return true;
    }

    /**
     * @throws GuzzleException
     */
    public function refund(int $orderId, array $cartIds): bool
    {
        $this->updateOrderStatus($orderId, self::RETURN_STATUS);

        return true;
    }

    public function getAvailableBonuses(): float
    {
        throw new MethodNotAllowedException(['getAvailableBonusByOrderId']);
    }

    /**
     * @throws GuzzleException|\Exception
     */
    public function applyCode(?string $code, int $orderId): array
    {
        if ('' === $code) {
            $code = null;
        }

        $res = $this->callCalculateCart($orderId, null, $code);

        return $this->getItems($res['doc_items']);
    }

    public function applyCartCode(string $code, int $orderId, int $cartId): void
    {
        throw new MethodNotAllowedException(['applyCode']);
    }

    /**
     * @return OrderItem[]
     *
     * @throws GuzzleException
     */
    public function cancelCode(string $code, int $orderId): array
    {
        return $this->applyCode('', $orderId);
    }

    public function cancelCartCode(string $code, int $orderId, int $cartId): void
    {
        throw new MethodNotAllowedException(['cancelCode']);
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    private function getItems(string $xml)
    {
        $array = [];
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><root>' . $xml . '</root>');
        foreach ($xml->document->item as $item) {
            $index = (int) $item->attributes()->item_id;
            $price = (float) $item->attributes()->price;
            $discountedPrice = (float) $item->attributes()->price - (float) $item->attributes()->discounted_price;
            $type = (string) $item->attributes()->discount_type;
            $bonuses = (float) $item->attributes()->bonus_discounted_price;
            $promo_discounted_price = (float) $item->attributes()->promo_discounted_price;
            $array[] = new OrderItem($index, $type, $price, $discountedPrice, $bonuses, $promo_discounted_price);
        }

        return $array;
    }

    public function reSpendBonuses(int $orderId, float $bonuses, int $cartsCount): array
    {
        throw new MethodNotAllowedException([]);
    }

    private function callCalculateCart(int $orderId, ?float $bonuses, string $promocode = null): array
    {
        $params = ['orderid' => $orderId, 'promocode' => $promocode, 'bonus' => $bonuses];
        $user = $this->request->getUser();
        if (!empty($user->id)) {
            $params = array_merge(['customerid' => $user->id], $params);
        }

        return $this->request->execute('CalculateCart', $params);
    }

    private function updateOrderStatus(int $orderId, string $statusId): void
    {
        $params = ['status' => $statusId, 'orderid' => $orderId];
        $this->request->execute('UpdateOrderStatus', $params);
    }

    protected function getUserIdentifierType(): ?string
    {
        return null;
    }
}
