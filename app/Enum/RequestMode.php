<?php

declare(strict_types=1);

namespace App\Enum;

enum RequestMode: string
{
    case MODE_HTTP = 'modeHttp';
    case MODE_SOAP = 'modeSoap';
}
