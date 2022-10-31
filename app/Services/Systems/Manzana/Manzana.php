<?php

declare(strict_types=1);

namespace App\Services\Systems\Manzana;

use App\Enum\LoyaltyUserIdentifier;
use App\Exceptions\LoyaltySystemException;
use App\Services\AbstractLoyalty;
use App\Services\Contracts\LoyaltyTypes\BonusesInterface;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Response\OrderItem;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class Manzana extends AbstractLoyalty implements BonusesInterface
{
    public const BUY_WITHOUT_BB = 1;
    public const BUY_WITH_BB = 2;
    public const SOFT_CHECK = 'SoftCheckGetByExtAgent';
    public const FISCAL_CHECK = 'FiscalCheckGetByAgent';
    public const CANCEL_CHECK = 'FiscalCheckCancelByAgent';

    public function setFiscalCheck(int $orderId, float $bonuses, string $promocode = null): int|string
    {
        return 1;
    }

    public function cancelFiscalCheck(int $orderId, float $bonuses, string|int $loyaltySystemOperationId = null): bool
    {
        return true;
    }

    public function confirmOrder(int $orderId, float $bonuses, string|int $loyaltySystemOperationId = null): bool|int|string
    {
        $params = [
            'inorderid' => $orderId,
            'intype' => $bonuses > 0 ? self::BUY_WITH_BB : self::BUY_WITHOUT_BB,
            'inpayedbybonus' => $bonuses,
        ];
        $res = $this->request->execute(self::FISCAL_CHECK, $params);

        return (string) $res->transaction_ID;
    }

    public function refundCart(int $orderId, int $cartUid): bool
    {
        return true;
    }

    public function refund(int $orderId, array $cartIds): bool
    {
        return true;
    }

    public function getAvailableBonusByOrderId(int $orderId, float $orderAmount = null): float
    {
        $params = ['inorderid' => $orderId, 'intype' => self::BUY_WITH_BB];
        try {
            $res = $this->request->execute(self::SOFT_CHECK, $params);
        } catch (LoyaltySystemException) {
            return 0;
        }

        return (float) $res->doc_available;
    }

    public function getAvailableBonuses(): float
    {
        throw new MethodNotAllowedException(['getAvailableBonusByOrderId']);
    }

    public function spendBonuses(int $orderId, float $bonuses): array
    {
        $params = [
            'inorderid' => $orderId,
            'intype' => $bonuses > 0 ? self::BUY_WITH_BB : self::BUY_WITHOUT_BB,
            'inpayedbybonus' => $bonuses,
        ];
        $res = $this->request->execute(self::SOFT_CHECK, $params);

        return $this->getItems($res);
    }

    public function reSpendBonuses(int $orderId, float $bonuses, int $cartsCount): array
    {
        return $this->spendBonuses($orderId, $bonuses);
    }

    public function unSpendBonuses(int $orderId, float $bonuses): bool
    {
        $this->spendBonuses($orderId, 0);

        return true;
    }

    private function getItems(\SimpleXMLElement $xml)
    {
        $items = new \SimpleXMLElement((string) $xml->doc_items);
        $array = [];
        foreach ($items->item as $item) {
            $index = (int) $item->attributes()->item_id;
            $price = (float) $item->attributes()->price;
            $available = (float) $item->attributes()->available;
            $type = 'bonuses';
            $array[] = new OrderItem($index, $type, $price, $available, $available);
        }

        return $array;
    }

    protected function getUserIdentifierType(): ?string
    {
        return LoyaltyUserIdentifier::CARD->value;
    }
}
