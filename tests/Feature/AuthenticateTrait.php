<?php

namespace Tests\Feature;

trait AuthenticateTrait
{
    protected function getAuthHeader(string $token = '123')
    {
        return ['Authorization' => 'Bearer ' . $token];
    }
}
