<?php

namespace App\Providers;

use App\Checks\SocketAccessCheck;
use App\Models\Project;
use App\Services\Config\ConfigLoader;
use Illuminate\Support\ServiceProvider;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\RedisCheck;
use Spatie\Health\Checks\Checks\ScheduleCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Facades\Health;

class HealthCheckProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(ConfigLoader $loader)
    {
        $loader->load(null);
        $checks = [
            UsedDiskSpaceCheck::new()
                ->warnWhenUsedSpaceIsAbovePercentage(98)
                ->failWhenUsedSpaceIsAbovePercentage(99),
            DatabaseCheck::new(),
            RedisCheck::new(),
            EnvironmentCheck::new(),
            CacheCheck::new(),
        ];

        if (!empty($loader->host) && filter_var($loader->host, FILTER_VALIDATE_URL)) {
            $name = sprintf('Loyalty system "%s"', ucfirst($loader->loyaltySystem));
            $checks = array_merge($checks, [
                SocketAccessCheck::new()->url($loader->host)->name($name)->label(ucfirst($loader->loyaltySystem))
            ]);
        }
        $this->takeProjectsFromDatabase($loader, $checks);

        Health::checks($checks);
    }

    private function takeProjectsFromDatabase(ConfigLoader $loader, array &$checks): void
    {
        if (!\Schema::hasTable('projects')) {
            return;
        }
        $projects = Project::with('config')->get();
        /** @var Project $project */
        foreach ($projects as $project) {
            if (!filter_var($project->config->host, FILTER_VALIDATE_URL)) {
                continue;
            }
            if (rtrim($loader->host, '/') === rtrim($project->config->host, '/')) {
                continue;
            }
            $name = sprintf(
                'Loyalty system "%s" for %s',
                ucfirst($project->config->loyalty_system),
                $project->name
            );
            $checks = array_merge($checks, [
                SocketAccessCheck::new()->url($project->config->host)->name($name)
                    ->label(ucfirst($project->config->loyalty_system) . ' ' . $project->name)
            ]);
        }
    }
}
