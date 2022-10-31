<?php

declare(strict_types=1);

namespace App\Services\Factories;

use App\Services\AbstractLoyalty;
use App\Services\Config\ConfigLoader;
use App\Traits\HasSuchLoyaltyTrait;
use Illuminate\Contracts\Container\BindingResolutionException;

class LoyaltyFactory
{
    use HasSuchLoyaltyTrait;

    public function __construct(
        private LoyaltyRequestFactory $requestFactory,
        private LoyaltyRulesFactory $rulesFactory,
        private LentaServiceFactory $lentaServiceFactory,
        private ConfigLoader $configLoader
    ) {
    }

    /**
     * @throws BindingResolutionException
     */
    public function factory(
        string $lentaHost,
        string $lentaAgent,
        string $projectToken = null,
    ): AbstractLoyalty {
        $this->configLoader->load($projectToken);

        $loyaltySystem = $this->configLoader->loyaltySystem;
        $this->attemptLoyaltySystemType($loyaltySystem);

        $request = $this->requestFactory->factory($loyaltySystem, [
            'host' => $this->configLoader->host, 'agentKey' => $this->configLoader->lentaAgent ?? $lentaAgent,
        ]);
        $rules = $this->rulesFactory->factory($loyaltySystem);
        $lentaService = $this->lentaServiceFactory->factory(
            $this->configLoader->lentaHost ?? $lentaHost,
            $this->configLoader->lentaAgent ?? $lentaAgent,
            $this->configLoader->loyaltyKey,
            $loyaltySystem
        );

        $instanceClass = sprintf(
            '%s\%s\%s',
            ConfigLoader::getLoyaltySystemsNamespace(),
            ucfirst($loyaltySystem),
            ucfirst($loyaltySystem)
        );

        return app()->makeWith(
            $instanceClass,
            [
                'request' => $request,
                'rules' => $rules,
                'lentaService' => $lentaService,
            ]
        );
    }
}
