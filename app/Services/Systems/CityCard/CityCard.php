<?php

declare(strict_types=1);

namespace App\Services\Systems\CityCard;

use App\Enum\LoyaltyUserIdentifier;
use App\Exceptions\CardIsNotFoundException;
use App\Services\AbstractLoyalty;
use App\Services\Contracts\LoyaltyTypes\BonusesInterface;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Response\OrderItem;
use SimpleXMLElement;

class CityCard extends AbstractLoyalty implements BonusesInterface
{
    /**
     * @throws CardIsNotFoundException
     */
    public function getAvailableBonusByOrderId(int $orderId, float $orderAmount = null): float
    {
        if (!$this->isActiveCard()) {
            throw new CardIsNotFoundException('Card is not valid');
        }
        $params = ['orderID' => $orderId];
        $res = $this->request->execute('GetCardDiscountAmountByAgent', $params);

        return (float) $res->Available;
    }

    /**
     * @throws \Exception
     */
    public function spendBonuses(int $orderId, float $bonuses): array
    {
        $params = ['payedByBonus' => $bonuses, 'orderID' => $orderId];
        $res = $this->request->execute('SubstractBonusByAgent', $params);

        return $this->getItems($res->doc_items);
    }

    /**
     * @return OrderItem[]|[]
     *
     * @throws CardIsNotFoundException
     * @throws \Exception
     */
    public function reSpendBonuses(int $orderId, float $bonuses, int $cartsCount): array
    {
        // В ситикард нельзя пересчитать бонусы, если в корзине пусто. (Ебанутая система)
        $available_bonuses = $this->getAvailableBonusByOrderId($orderId);
        if ($available_bonuses < $bonuses) {
            $bonuses = $available_bonuses;
        }

        return $this->spendBonuses($orderId, $bonuses);
    }

    public function unSpendBonuses(int $orderId, float $bonuses): bool
    {
        $params = ['payedByBonus' => $bonuses, 'orderID' => $orderId];
        $this->request->execute('CancelSubstractBonusByAgent', $params);

        return true;
    }

    public function setFiscalCheck(int $order_id, float $bonuses, string|null $promocode = null): int|string
    {
        return 1;
    }

    public function cancelFiscalCheck(int $orderId, float $bonuses, string|int $loyaltySystemOperationId = null): bool
    {
        $this->unSpendBonuses($orderId, $bonuses);

        return true;
    }

    public function confirmOrder(int $orderId, float $bonuses, string|int $loyaltySystemOperationId = null): bool|int|string
    {
        $params = ['payedByBonus' => $bonuses, 'orderID' => $orderId];
        $this->request->execute('AccrualByAgent', $params);

        return true;
    }

    /**
     * @throws CardIsNotFoundException
     */
    public function getAvailableBonuses(): float
    {
        if (!$this->isActiveCard()) {
            throw new CardIsNotFoundException('Card is not valid');
        }
        $res = $this->request->execute('GetCardBalanceByAgent');

        return (float) $res->Balance;
    }

    /**
     * @throws \Exception
     */
    private function getItems(string $xml): array
    {
        $items = new SimpleXMLElement($xml);
        $array = [];
        foreach ($items as $item) {
            $index = (int) $item->attributes()->item_id;
            $price = (float) $item->attributes()->price;
            $available = (float) $item->attributes()->available;
            $type = (string) $item->attributes()->discount_type;
            $array[] = new OrderItem($index, $type, $price, $available, $available);
        }

        return $array;
    }

    private function getInfoByValidCard(): object
    {
        return $this->request->execute('IsCardValidByAgent');
    }

    private function isActiveCard(): bool
    {
        $this->getInfoByValidCard();

        return true;
    }

    public function refundCart(int $orderId, int $cartUid): bool
    {
        $params = ['orderID' => $orderId, 'itemIndex' => $cartUid];
        $this->request->execute('BackByAgent', $params);

        return true;
    }

    public function refund(int $orderId, array $cartIds): bool
    {
        foreach ($cartIds as $id) {
            $this->refundCart($orderId, $id);
        }

        return true;
    }

    protected function getUserIdentifierType(): ?string
    {
        return LoyaltyUserIdentifier::CARD->value;
    }
}
