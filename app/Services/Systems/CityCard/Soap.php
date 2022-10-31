<?php

declare(strict_types=1);

namespace App\Services\Systems\CityCard;

use SoapClient;

class Soap extends \App\Services\Request\Soap
{
    protected function executeFunction(SoapClient $soapClient, string $function, array $params): mixed
    {
        return $soapClient->$function($params);
    }
}
