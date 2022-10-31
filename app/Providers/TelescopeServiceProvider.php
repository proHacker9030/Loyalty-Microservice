<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Request\RequestData;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Telescope::night();

        $this->hideSensitiveRequestDetails();

        Telescope::filter(function (IncomingEntry $entry) {
            if (Str::startsWith(request()->getPathInfo(), '/admin')
                && !Str::contains(request()->getPathInfo(), ['orders-operation'])) {
                return false;
            }

            //if ($this->app->environment('local')) {
                return true;
            //}

//            return $entry->isReportableException() ||
//                $entry->isFailedRequest() ||
//                $entry->isFailedJob() ||
//                $entry->isScheduledTask() ||
//                $entry->hasMonitoredTag();
        });

        Telescope::tag(function (IncomingEntry $entry) {
            $projectToken = request()->input(RequestData::PROJECT_TOKEN_KEY, false);
            $order = request()->input(RequestData::ORDER_KEY, false);
            $tags = [];
            if ($entry->type === 'request') {
                if ($projectToken) {
                    $tags[] = 'projectToken:' . $projectToken;
                }
                if ($order && isset($order['id'])) {
                    $tags[] = 'orderId:' . $order['id'];
                }
            }

            return $tags;
        });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     *
     * @return void
     */
    protected function hideSensitiveRequestDetails()
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewTelescope', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }
}
