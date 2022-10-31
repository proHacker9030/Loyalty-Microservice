<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Repositories\OrderRepository;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Request\RequestData;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function __construct(Container $container, private OrderRepository $orderRepository)
    {
        parent::__construct($container);
    }

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->setRenderable();
        $this->setReportable();
    }

    private function setRenderable(): void
    {
        $this->renderable(function (LoyaltySystemException $e, $request) {
            $message = 'Error in loyalty system. ' . $e->getMessage();

            return Renderer::render(422, $message, $request);
        });

        $this->renderable(function (CardIsNotFoundException $e, $request) {
            $message = 'Card is not found.';

            return Renderer::render(404, $message, $request);
        });

        $this->renderable(function (SoapConnectFailedException $e, $request) {
            $message = 'Soap connect failed.';

            return Renderer::render(500, $message, $request);
        });

        $this->renderable(function (SoapExecuteFailedException $e, $request) {
            $message = 'Soap execute failed.';

            return Renderer::render(500, $message, $request);
        });

        $this->renderable(function (GuzzleException $e, $request) {
            $message = 'Http request exception.';

            return Renderer::render(500, $message, $request);
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            $message = 'Not found. ' . $e->getMessage();

            return Renderer::render(404, $message, $request);
        });

        $this->renderable(function (InvalidValueException $e, $request) {
            $message = 'Invalid argument exception.';

            return Renderer::render(422, $message, $request);
        });

        $this->renderable(function (HttpRequestException $e, $request) {
            $message = 'Http request exception.';

            return Renderer::render(500, $message, $request);
        });

        $this->renderable(function (LentaException $e, $request) {
            $message = 'BO exception.';

            return Renderer::render(500, $message, $request);
        });
    }

    private function setReportable(): void
    {
        $this->reportable(function (LoyaltySystemException $e): void {
            $message = 'Error in loyalty system. ' . $e->getMessage();
            $this->updateError($message);
        });

        $this->reportable(function (LentaException $e): void {
            $message = 'BO exception. ' . $e->getMessage();
            $this->updateError($message);
        });

        $this->reportable(function (SoapConnectFailedException $e): void {
            $message = 'Soap connect failed. ' . $e->getMessage();
            $this->updateError($message);
        });

        $this->reportable(function (SoapExecuteFailedException $e): void {
            $message = 'Soap execute failed. ' . $e->getMessage();
            $this->updateError($message);
        });

        $this->reportable(function (GuzzleException $e): void {
            $message = 'Http request exception. ' . $e->getMessage();
            $this->updateError($message);
        });

        $this->renderable(function (HttpRequestException $e): void {
            $message = 'Http request exception. ' . $e->getMessage();
            $this->updateError($message);
        });
    }

    private function updateError(string $message): void
    {
        $order = request()->input(RequestData::ORDER_KEY);
        if (!isset($order['id'])) {
            return;
        }
        $orderId = (int) $order['id'];
        $this->orderRepository->updateError(
            $orderId,
            $message,
            request()->input(RequestData::PROJECT_TOKEN_KEY),
        );
    }
}
