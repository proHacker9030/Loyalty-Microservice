<?php

declare(strict_types=1);

namespace App\Services\Systems\Manzana;

use App\Enum\RequestMethod;
use App\Enum\RequestMode;
use App\Exceptions\InvalidValueException;
use App\Exceptions\LoyaltySystemException;
use App\Helpers\StringHelper;
use App\Services\AbstractLoyaltyRequest;
use App\Services\Request\Http;

class ManzanaRequest extends AbstractLoyaltyRequest
{
    private string $inputXml;

    protected function prepareParams(array &$params): void
    {
        if (empty($this->user->cardNumber)) {
            throw new InvalidValueException('Card number is required for Manzana');
        }

        $xml = new \SimpleXMLElement('<?xml version="1.0"?><data></data>');
        StringHelper::arrayToXml($params, $xml);
        $this->inputXml = $xml->asXML();
    }

    protected function getSpecificParams(): array
    {
        return ['inagentkey' => $this->agentKey, 'incardnumber' => $this->user->cardNumber];
    }

    public function execute(string $function, array $params = []): mixed
    {
        if (RequestMode::MODE_HTTP->value === $this->getRequestMode()) {
            $params = array_merge($this->getSpecificParams(), $params);
            $this->prepareParams($params);

            $this->http->setDataType(Http::XML_DATA_TYPE);
            $this->http->setResponseFormat(Http::RAW_RESPONSE);

            \Log::info('Пытаемся произвести запрос в лояльность (' . $function . ')', [self::CONTEXT, $function, $params]);

            $result = $this->http->execute($function, $this->getRequestMethod(), $this->inputXml);

            \Log::info('Ответ от лояльности (' . $function . '): ' . print_r($result, true), [self::CONTEXT, $function, $params]);
        } else {
            return parent::execute($function, $params);
        }

        $xml = new \SimpleXMLElement($result);
        if (0 !== (int) $xml->result) {
            \Log::error($xml->message, ['Manzana', $function, $params]);
            throw new LoyaltySystemException((string) $xml->message);
        }

        return $xml;
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
