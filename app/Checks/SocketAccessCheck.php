<?php

declare(strict_types=1);

namespace App\Checks;

use App\Services\SocketAccessChecker;
use Illuminate\Contracts\Container\BindingResolutionException;
use Spatie\Health\Checks\Result;
use Spatie\Health\Exceptions\InvalidCheck;

class SocketAccessCheck extends \Spatie\Health\Checks\Checks\PingCheck
{
    public function run(): Result
    {
        if (is_null($this->url)) {
            throw InvalidCheck::urlNotSet();
        }

        $checker = $this->getSocketAccessChecker();
        try {
            $result = $checker->check($this->url);
            if (!$result) {
                return $this->failedResult();
            }
        } catch (\Exception) {
            return $this->failedResult();
        }

        return Result::make()
            ->ok()
            ->shortSummary('reachable');
    }

    /**
     * @throws BindingResolutionException
     */
    private function getSocketAccessChecker(): SocketAccessChecker
    {
        return app()->make(SocketAccessChecker::class);
    }

    protected function failedResult(): Result
    {
        return Result::make()
            ->failed()
            ->shortSummary('unreachable')
            ->notificationMessage($this->failureMessage ?? "{$this->getName()} is not available.");
    }
}
