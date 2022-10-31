<?php

namespace App\Providers;

use App\Models\DTO\RequestApiData;
use App\Validations\BaseValidator;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Request\RequestData;
use App\Services\AbstractLoyalty;
use App\Services\Factories\LoyaltyFactory;
use Illuminate\Support\ServiceProvider;
use Infomatika\Microservice\Sdk\Loyalty\Enum\EnvironmentEnum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AbstractLoyalty::class, function ($app) {
            BaseValidator::validateCommonRequiredData(request());

            /** @var LoyaltyFactory $loyaltyFactory */
            $loyaltyFactory = $app->make(LoyaltyFactory::class);
            $loyalty = $loyaltyFactory->factory(
                request()->input(RequestData::LENTA_HOST_KEY),
                request()->input(RequestData::LENTA_AGENT_KEY),
                request()->input(RequestData::PROJECT_TOKEN_KEY)
            );
            $loyalty->request->setUser((new RequestApiData())->getUser(request()));

            return $loyalty;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (env('IS_DEPLOYED', false)) {
            $this->setUpUrl();
        }
        $this->setUpEnv();
    }

    private function setUpEnv()
    {
        if (request()->input(RequestData::ENV_KEY) === EnvironmentEnum::ENV_PROD) {
            \Config::set('APP_ENV', 'production');
            \Config::set('APP_DEBUG', false);
        }
    }

    private function setUpUrl()
    {
        \URL::forceScheme(env('APP_URL_SCHEME', 'http'));
        \URL::forceRootUrl(env('APP_URL'));
    }
}
