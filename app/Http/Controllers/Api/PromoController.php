<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Services\AbstractLoyalty;
use App\Services\Operations\PromocodeService;
use App\Validations\BaseValidator;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Request\RequestData;

class PromoController extends Controller
{
    private PromocodeService $promocodeService;

    public function __construct(AbstractLoyalty $loyalty)
    {
        $this->promocodeService = new PromocodeService($loyalty);
    }

    public function applyCode(OrderRequest $request)
    {
        BaseValidator::validatePromocode($request);

        $dto = $request->getDto();
        $dto->fillPromocodeData($request->input(RequestData::PROMOCODE_KEY));

        $this->promocodeService->setDto($dto);
        $data = $this->promocodeService->applyCode();

        return response()->json(compact('data'));
    }

    public function cancelCode(OrderRequest $request)
    {
        BaseValidator::validatePromocode($request);

        $dto = $request->getDto();
        $dto->fillPromocodeData($request->input(RequestData::PROMOCODE_KEY));

        $this->promocodeService->setDto($dto);
        $data = $this->promocodeService->cancelCode();

        return response()->json(compact('data'));
    }
}
