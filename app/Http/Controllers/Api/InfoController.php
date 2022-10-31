<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AbstractLoyalty;

class InfoController extends Controller
{
    public function __construct(private AbstractLoyalty $loyalty)
    {
    }

    public function getUserIdentifierType()
    {
        return json_encode(['data' => $this->loyalty->userIdentifierType]);
    }
}
