<?php

declare(strict_types=1);

namespace App\Services\Systems\Dls;

use App\Enum\RequestMethod;
use App\Enum\RequestMode;
use App\Exceptions\InvalidValueException;
use App\Exceptions\LoyaltySystemException;
use App\Services\AbstractLoyaltyRequest;

class DlsRequest extends AbstractLoyaltyRequest
{
    protected function prepareParams(array &$params): void
    {
        if (empty($this->user->phone)) {
            throw new InvalidValueException('Phone number is required for DLS');
        }

        $this->user->phone = preg_replace('/[^0-9]/', '', $this->user->phone);
    }

    protected function getSpecificParams(): array
    {
        return [
            'phone' => $this->user->phone,
            'agentkey' => $this->agentKey,
        ];
    }

    protected function getRequestMode(): string
    {
        return RequestMode::MODE_HTTP->value;
    }

    protected function getRequestMethod(): ?string
    {
        return RequestMethod::GET->value;
    }

    public function execute(string $function, array $params = []): mixed
    {
        $response = parent::execute($function, $params);
        if ($response['result']) {
            \Log::error($response['message'], ['dls', $function, $params]);
            throw new LoyaltySystemException($response['message'], 422);
        }

        return $response;
    }
}
