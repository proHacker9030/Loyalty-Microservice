<?php

declare(strict_types=1);

namespace App\Traits;

use App\Enum\LoyaltySystems;
use App\Exceptions\InvalidValueException;

trait HasSuchLoyaltyTrait
{
    protected function attemptLoyaltySystemType(string $loyaltySystem): void
    {
        if (!in_array($loyaltySystem, LoyaltySystems::getConsts())) {
            throw new InvalidValueException("Loyalty system $loyaltySystem does not exist");
        }
    }
}
