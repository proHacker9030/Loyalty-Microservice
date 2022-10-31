<?php

declare(strict_types=1);

namespace App\Services\Systems\CityCard;

use App\Enum\RequestMode;
use App\Exceptions\InvalidValueException;
use App\Exceptions\LoyaltySystemException;
use App\Services\AbstractLoyaltyRequest;

class CityCardRequest extends AbstractLoyaltyRequest
{
    public function __construct(string $host, string $agentKey)
    {
        parent::__construct($host, $agentKey);
        $this->soap = new Soap($host, $agentKey);
    }

    public function prepareParams(array &$params): void
    {
        if (empty($this->user->cardNumber)) {
            throw new InvalidValueException('Card number is required for CityCard');
        }

        return;
    }

    public function getSpecificParams(): array
    {
        return ['cardNumber' => $this->user->cardNumber, 'agentKey' => $this->agentKey];
    }

    public function execute(string $function, array $params = []): mixed
    {
        $object = parent::execute($function, $params);
        $response = $object->{$function . 'Result'};
        if ($response->Result) {
            \Log::error($response->Message, ['cityCard', $function, $params]);
            throw new LoyaltySystemException($response->Message, 422);
        }

        return $response;
    }

    protected function getRequestMode(): string
    {
        return RequestMode::MODE_SOAP->value;
    }

    protected function getRequestMethod(): ?string
    {
        return null;
    }
}
