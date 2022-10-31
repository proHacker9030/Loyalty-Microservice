<?php

declare(strict_types=1);

namespace App\Services\Request;

use App\Exceptions\HttpRequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Http
{
    private string $dataType;
    private string $responseFormat;
    private array $headers = [];
    /** @var int timeout in seconds */
    private int $requestTimeout;

    public const XML_DATA_TYPE = 'xml';
    public const JSON_DATA_TYPE = 'json';

    public const JSON_RESPONSE = 'json_response';
    public const RAW_RESPONSE = 'raw_response';

    public const REQUEST_TIMEOUT_DEFAULT = 15;

    private string $host;

    public function __construct(string $host, private string $context)
    {
        $this->host = rtrim($host, '/');
        $this->dataType = self::JSON_DATA_TYPE;
        $this->responseFormat = self::JSON_RESPONSE;
        $this->requestTimeout = self::REQUEST_TIMEOUT_DEFAULT;
    }

    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    public function setDataType(string $dataType): void
    {
        $this->dataType = $dataType;
    }

    public function setResponseFormat(string $responseFormat): void
    {
        $this->responseFormat = $responseFormat;
    }

    public function setRequestTimeout(int $requestTimeout): void
    {
        $this->requestTimeout = $requestTimeout;
    }

    public function execute(
        string $url,
        string $requestMethod,
        array|string $params = []
    ) {
        $headers = [];
        if (self::XML_DATA_TYPE === $this->dataType) {
            $data = ['body' => $params];
            $headers['Content-Type'] = 'text/xml; charset=UTF8';
        } elseif (self::JSON_DATA_TYPE == $this->dataType) {
            $data = ['json' => $params];
        } else {
            $data = ['body' => $params];
        }
        $client = new Client(['headers' => array_merge($this->headers, $headers)]);
        $data['timeout'] = $this->requestTimeout;

        try {
            $response = $client->request(
                $requestMethod,
                $this->host . '/' . $url,
                $data
            );

            if (self::RAW_RESPONSE == $this->responseFormat) {
                return $response->getBody()->getContents();
            }

            return json_decode((string) $response->getBody(), true);
        } catch (GuzzleException $exception) {
            \Log::error($exception->getMessage(), [
                'httpGuzzle', $this->context, $this->host . $url, $params,
            ]);
            throw new HttpRequestException($exception->getMessage());
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage(), [
                'httpExecute', $this->context, $this->host . $url, $params,
            ]);
            throw new HttpRequestException($exception->getMessage());
        }
    }
}
