<?php

declare(strict_types=1);

namespace App\Services\Systems\Dls;

use App\Enum\LoyaltyUserIdentifier;
use App\Services\AbstractLoyalty;
use App\Services\Contracts\LoyaltyTypes\BonusesInterface;
use GuzzleHttp\Exception\GuzzleException;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Response\OrderItem;

class Dls extends AbstractLoyalty implements BonusesInterface
{
    public function getAvailableBonusByOrderId(int $orderId, float $orderAmount = null): float
    {
        $params = ['bybonus' => (string) $orderAmount, 'orderid' => (string) $orderId];
        $user = $this->request->getUser();
        if (!empty($user->email)) {
            $params = array_merge([
                'firstName' => $user->first, 'lastName' => $user->second,
                'patronymic' => $user->middle, 'email' => $user->email,
            ], $params);
        }
        $res = $this->request->execute('GetBonusAmountByAgent', $params);

        return (float) $res['available'];
    }

    /**
     * @throws \Exception
     */
    public function spendBonuses(int $orderId, float $bonuses): array
    {
        $params = ['bybonus' => (string) $bonuses, 'orderid' => (string) $orderId];
        $res = $this->request->execute('GetBonusAmountByAgent', $params);

        return $this->getItems($res['doc_items']);
    }

    /**
     * @throws GuzzleException
     */
    public function unSpendBonuses(int $orderId, float $bonuses): bool
    {
        $this->spendBonuses($orderId, 0);
    }

    /**
     * @throws GuzzleException
     */
    public function setFiscalCheck(int $orderId, float $bonuses, string $promocode = null): int|string
    {
        $params = ['bybonus' => (string) $bonuses, 'orderid' => (string) $orderId];
        $res = $this->request->execute('SaleRegisterByAgent', $params);

        return $res['DLS_OPERATION_ID'];
    }

    /**
     * @throws GuzzleException
     */
    public function cancelFiscalCheck(int $orderId, float $bonuses, string|int $loyaltySystemOperationId = null): bool
    {
        $params = ['dlsoperationid' => (string) $loyaltySystemOperationId, 'orderid' => (string) $orderId];
        $this->request->execute('SaleCancelByAgent', $params);

        return true;
    }

    public function confirmOrder(int $orderId, float $bonuses, string|int $loyaltySystemOperationId = null): bool|int|string
    {
        return true;
    }

    public function refundCart(int $orderId, int $cartUid): bool
    {
        return true;
    }

    public function refund(int $orderId, array $cartIds): bool
    {
        return true;
    }

    /**
     * @throws GuzzleException
     */
    public function getAvailableBonuses(): float
    {
        $user = $this->request->getUser();
        $params = [
            'firstName' => $user->first, 'lastName' => $user->second,
            'patronymic' => $user->middle, 'email' => $user->email,
        ];
        $res = $this->request->execute('GetBalanceByAgent', $params);

        return (float) $res['balance'];
    }

    /**
     * @throws \Exception
     */
    private function getItems($json): array
    {
        $array = [];
        $json = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><root>' . $json . '</root>');
        foreach ($json as $item) {
            $index = (int) $item->attributes()->item_id;
            $price = (float) $item->attributes()->price;
            $available = (float) $item->attributes()->available;
            $type = (string) $item->attributes()->discount_type;
            $array[] = new OrderItem($index, $type, $price, $available, $available);
        }

        return $array;
    }

    /**
     * @return OrderItem[]
     *
     * @throws GuzzleException
     */
    public function reSpendBonuses(int $orderId, float $bonuses, int $cartsCount): array
    {
        if (0 == $cartsCount) {
            $bonuses = 0;
        }

        return $this->spendBonuses($orderId, $bonuses);
    }

    protected function getUserIdentifierType(): ?string
    {
        return LoyaltyUserIdentifier::PHONE->value;
    }
}
