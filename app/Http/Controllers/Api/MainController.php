<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enum\OrderStatuses;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Repositories\OrderRepository;
use App\Services\AbstractLoyalty;

class MainController extends Controller
{
    public function __construct(private AbstractLoyalty $loyalty, private OrderRepository $orderRepository)
    {
    }

    public function disableLoyalty(OrderRequest $request)
    {
        $dto = $request->getDto();

        $data = $this->loyalty->lentaService->clearLoyalty($dto->order->id);
        $this->orderRepository->updateStatus($dto->order->id, OrderStatuses::CANCELED, $dto->projectToken);

        return response()->json(compact('data'));
    }
}
