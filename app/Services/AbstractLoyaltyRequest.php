<?php

declare(strict_types=1);

namespace App\Services;

use App\Enum\RequestMode;
use App\Exceptions\InvalidValueException;
use App\Services\Request\Http;
use App\Services\Request\Soap;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Request\User;

abstract class AbstractLoyaltyRequest
{
    protected string $host;
    protected string $agentKey;

    protected User $user;

    protected Http $http;
    protected Soap $soap;

    public const CONTEXT = 'Loyalty';

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function __construct(
        string $host,
        string $agentKey,
    ) {
        $this->host = $host;
        $this->agentKey = str_replace('-', '', $agentKey);
        $this->soap = new Soap($this->host, self::CONTEXT);
        $this->http = new Http($this->host, self::CONTEXT);
    }

    abstract protected function prepareParams(array &$params): void;

    abstract protected function getSpecificParams(): array;

    abstract protected function getRequestMode(): string;

    abstract protected function getRequestMethod(): ?string;

    public function execute(string $function, array $params = []): mixed
    {
        $params = array_merge($this->getSpecificParams(), $params);
        $this->prepareParams($params);

        \Log::info('Пытаемся произвести запрос в лояльность (' . $function . ')', [self::CONTEXT, $function, $params]);

        if (RequestMode::MODE_SOAP->value === $this->getRequestMode()) {
            $res = $this->soap->execute($function, $params);
        } elseif (RequestMode::MODE_HTTP->value === $this->getRequestMode()) {
            $res = $this->http->execute($function, $this->getRequestMethod(), $params);
        } else {
            throw new InvalidValueException('Unsupported request type');
        }

        \Log::info('Ответ от лояльности (' . $function . '): ' . print_r($res, true), [self::CONTEXT, $function, $params]);

        return $res;
    }

    public function setRequestTimeout(int $seconds): void
    {
        $this->http->setRequestTimeout($seconds);
    }
}
