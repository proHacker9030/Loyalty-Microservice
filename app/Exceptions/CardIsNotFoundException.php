<?php

declare(strict_types=1);

namespace App\Exceptions;

class CardIsNotFoundException extends \Exception
{
    protected $code = 404;
}
