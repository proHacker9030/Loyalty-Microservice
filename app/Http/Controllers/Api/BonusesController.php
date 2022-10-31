<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BonusesOperationRequest;
use App\Http\Requests\MainRequest;
use App\Http\Requests\OrderRequest;
use App\Services\AbstractLoyalty;
use App\Services\Operations\BonusesService;
use App\Validations\BaseValidator;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Request\RequestData;

class BonusesController extends Controller
{
    private BonusesService $bonusesService;

    public function __construct(private AbstractLoyalty $loyalty)
    {
        $this->bonusesService = new BonusesService($loyalty);
    }

    public function getAvailableBonuses(MainRequest $request)
    {
        $dto = $request->getDto();

        $this->bonusesService->setDto($dto);
        $data = $this->bonusesService->getAvailableBonuses();

        return response()->json(compact('data'));
    }

    /**
     * @throws \Exception
     */
    public function spendBonuses(BonusesOperationRequest $request)
    {
        $dto = $request->getDto();
        $dto->fillBonusesData($request->input(RequestData::BONUSES_AMOUNT_KEY));

        $this->bonusesService->setDto($dto);
        $data = $this->bonusesService->spendBonuses();

        return response()->json(compact('data'));
    }

    public function reSpendBonuses(BonusesOperationRequest $request)
    {
        $dto = $request->getDto();

        if (is_null($request->input(RequestData::ORDER_KEY . '.carts'))) {
            $dto->order->carts = [];
        } else {
            BaseValidator::validateOrderCarts($request);
        }

        $dto->fillBonusesData($request->input(RequestData::BONUSES_AMOUNT_KEY));

        $this->bonusesService->setDto($dto);
        $data = $this->bonusesService->respendBonuses();

        return response()->json(compact('data'));
    }

    public function getOrderAmountAndBonuses(OrderRequest $request)
    {
        $dto = $request->getDto();
        $data = $this->loyalty->lentaService->getSumLoyaltyBonuses($dto->order->id);

        return response()->json(compact('data'));
    }
}
