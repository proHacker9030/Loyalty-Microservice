<?php

declare(strict_types=1);

namespace App\Services\Request;

use App\Exceptions\SoapConnectFailedException;
use App\Exceptions\SoapExecuteFailedException;
use SoapClient;

class Soap
{
    public function __construct(protected string $host, protected string $context)
    {
    }

    public function execute(string $function, array $params = []): mixed
    {
        try {
            $soap_client = new SoapClient($this->host, ['cache_wsdl' => WSDL_CACHE_NONE]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage(), ['soap', $this->context . ':' . $function, $params]);
            throw new SoapConnectFailedException($e->getMessage());
        }
        try {
            return $this->executeFunction($soap_client, $function, $params);
        } catch (\Exception $e) {
            \Log::error($e->getMessage(), ['soap', $this->context . ':' . $function, $params]);
            throw new SoapExecuteFailedException($e->getMessage());
        }
    }

    protected function executeFunction(SoapClient $soapClient, string $function, array $params): mixed
    {
        return call_user_func_array([$soapClient, $function], $params);
    }
}
