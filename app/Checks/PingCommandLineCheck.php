<?php

declare(strict_types=1);

namespace App\Checks;

use Exception;
use Spatie\Health\Checks\Result;
use Spatie\Health\Exceptions\InvalidCheck;

class PingCommandLineCheck extends \Spatie\Health\Checks\Checks\PingCheck
{
    public function run(): Result
    {
        if (is_null($this->url)) {
            throw InvalidCheck::urlNotSet();
        }

        try {
            exec('ping -c 2 -W 2 ' . $this->url, $output, $status);
            if (0 !== $status) {
                return $this->failedResult();
            }
        } catch (Exception) {
            return $this->failedResult();
        }

        return Result::make()
            ->ok()
            ->shortSummary('reachable');
    }
}
