<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Services\AbstractLoyalty;
use App\Services\Operations\PayService;
use App\Validations\BaseValidator;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Request\RequestData;

class PayController extends Controller
{
    private PayService $payService;

    public function __construct(AbstractLoyalty $loyalty)
    {
        $this->payService = new PayService($loyalty);
    }

    public function confirmOrder(OrderRequest $request)
    {
        BaseValidator::validateBonusesAmount($request);

        $dto = $request->getDto();
        $dto->fillBonusesData($request->input(RequestData::BONUSES_AMOUNT_KEY));
        $dto->setLoyaltySystemOperationId($request->input(RequestData::LOYALTY_SYSTEM_OPERATION_ID_KEY));

        $this->payService->setDto($dto);
        $data = $this->payService->confirmOrder();

        return response()->json(compact('data'));
    }

    public function setFiscalCheck(OrderRequest $request)
    {
        BaseValidator::validateBonusesAmount($request);

        $dto = $request->getDto();
        $dto->fillBonusesData($request->input(RequestData::BONUSES_AMOUNT_KEY));
        $dto->fillPromocodeData($request->input(RequestData::PROMOCODE_KEY));

        $this->payService->setDto($dto);
        $data = $this->payService->setFiscalCheck();

        return response()->json(compact('data'));
    }

    public function cancelFiscalCheck(OrderRequest $request)
    {
        BaseValidator::validateBonusesAmount($request);

        $dto = $request->getDto();
        $dto->fillBonusesData($request->input(RequestData::BONUSES_AMOUNT_KEY));
        $dto->setLoyaltySystemOperationId($request->input(RequestData::LOYALTY_SYSTEM_OPERATION_ID_KEY));

        $this->payService->setDto($dto);
        $data = $this->payService->cancelFiscalCheck();

        return response()->json(compact('data'));
    }
}
