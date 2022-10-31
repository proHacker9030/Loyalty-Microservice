<?php

declare(strict_types=1);

namespace App\Services\Systems\MindBox;

use App\Enum\RequestMethod;
use App\Enum\RequestMode;
use App\Exceptions\LoyaltySystemException;
use App\Services\AbstractLoyaltyRequest;

class MindBoxRequest extends AbstractLoyaltyRequest
{
    protected function prepareParams(array &$params): void
    {
        $params['orderid'] = (string) $params['orderid'];
        if (isset($params['bonus'])) {
            $params['bonus'] = (string) $params['bonus'];
        }
        if (isset($params['customerid'])) {
            $params['customerid'] = (string) $params['customerid'];
        }
        if (isset($params['transactionid'])) {
            $params['transactionid'] = (string) $params['transactionid'];
        }
        if (isset($params['itemid'])) {
            $params['itemid'] = (string) $params['itemid'];
        }
    }

    protected function getSpecificParams(): array
    {
        return ['agentkey' => $this->agentKey, 'UUID' => $this->user->loyaltyUid];
    }

    protected function getRequestMode(): string
    {
        return RequestMode::MODE_HTTP->value;
    }

    protected function getRequestMethod(): ?string
    {
        return RequestMethod::POST->value;
    }

    public function execute(string $function, array $params = []): mixed
    {
        $response = parent::execute($function, $params);
        if (!isset($response['result']) || 0 != $response['result']) {
            \Log::error($response['message'], ['mindBox', $function, $params]);
            throw new LoyaltySystemException($response['message'], 422);
        }

        return $response;
    }
}
