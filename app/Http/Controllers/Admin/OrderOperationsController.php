<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Services\Operations\PayService;

class OrderOperationsController
{
    public function cancelOrder(int $id)
    {
        $service = new PayService(null, $id);

        return $this->callServiceMethod([$service, 'cancelFiscalCheck']);
    }

    public function forceCancelOrder(int $id)
    {
        $service = new PayService(null, $id);

        return $this->callServiceMethod([$service, 'forceCancelFiscalCheck']);
    }

    public function confirmOrder(int $id)
    {
        $service = new PayService(null, $id);

        return $this->callServiceMethod([$service, 'confirmOrder']);
    }

    public function setFiscalCheck(int $id)
    {
        $service = new PayService(null, $id);

        return $this->callServiceMethod([$service, 'setFiscalCheck']);
    }

    private function callServiceMethod(callable $action)
    {
        try {
            $action();
        } catch (\Exception $exception) {
            \Alert::add('error', $exception->getMessage())->flash();

            return redirect()->back();
        }
        \Alert::add('success', 'Статус успешно изменен.')->flash();

        return redirect()->back();
    }
}
