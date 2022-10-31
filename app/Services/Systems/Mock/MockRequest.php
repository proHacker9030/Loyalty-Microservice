<?php

declare(strict_types=1);

namespace App\Services\Systems\Mock;

use App\Enum\RequestMethod;
use App\Enum\RequestMode;
use App\Services\AbstractLoyaltyRequest;

class MockRequest extends AbstractLoyaltyRequest
{
    public function execute(string $function, array $params = []): mixed
    {
        return true;
    }

    protected function prepareParams(array &$params): void
    {
        // TODO: Implement prepareParams() method.
    }

    protected function getSpecificParams(): array
    {
        return [];
    }

    protected function getRequestMode(): string
    {
        return RequestMode::MODE_HTTP->value;
    }

    protected function getRequestMethod(): ?string
    {
        return RequestMethod::POST->value;
    }
}
