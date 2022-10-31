<?php

declare(strict_types=1);

namespace App\Services\Config;

use App\Models\Project;

class ConfigLoader
{
    public string $loyaltySystem;
    public string $host;
    public string $loyaltyKey;
    public ?string $lentaAgent;
    public ?string $lentaHost;

    public function load(?string $projectToken): void
    {
        if (empty($projectToken)) {
            $this->loadFromEnv();

            return;
        }
        $project = Project::where('token', $projectToken)->with('config')->first();
        if (!is_null($project) && !is_null($dbConfig = $project->config)) {
            $this->loyaltySystem = $dbConfig->loyalty_system;
            $this->host = $dbConfig->host;
            $this->loyaltyKey = $dbConfig->loyalty_key;
            $this->lentaHost = $dbConfig->lenta_host;
            $this->lentaAgent = $dbConfig->lenta_agent;
        } else {
            $this->loadFromEnv();
        }
    }

    public static function getLoyaltySystemsNamespace(): string
    {
        return 'App\Services\Systems';
    }

    protected function loadFromEnv(): void
    {
        $this->loyaltySystem = env('LOYALTY_SYSTEM_NAME');
        $this->host = env('LOYALTY_SYSTEM_HOST');
        $this->loyaltyKey = env('LOYALTY_SYSTEM_KEY');
    }
}
