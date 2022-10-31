<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Services\AbstractLoyalty;
use App\Services\Operations\RefundService;
use App\Validations\BaseValidator;

class RefundController extends Controller
{
    private RefundService $refundService;

    public function __construct(AbstractLoyalty $loyalty)
    {
        $this->refundService = new RefundService($loyalty);
    }

    public function refundOrder(OrderRequest $request)
    {
        BaseValidator::validateOrderCarts($request);

        $dto = $request->getDto();

        $this->refundService->setDto($dto);
        $data = $this->refundService->refund();

        return response()->json(compact('data'));
    }

    public function refundCart(OrderRequest $request)
    {
        BaseValidator::validateOrderCarts($request);

        $dto = $request->getDto();

        $this->refundService->setDto($dto);
        $data = $this->refundService->refundCart();

        return response()->json(compact('data'));
    }
}
