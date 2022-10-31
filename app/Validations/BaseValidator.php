<?php

declare(strict_types=1);

namespace App\Validations;

use Illuminate\Http\Request;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Request\RequestData;

class BaseValidator
{
    public static function validateCommonRequiredData(Request $request): void
    {
        self::validateEnv($request);
        self::validateUser($request);
        self::validateLentaParams($request);
    }

    public static function validateBonusesAmount(Request $request): void
    {
        $request->validate([
            RequestData::BONUSES_AMOUNT_KEY => 'required|numeric',
        ]);
    }

    public static function validatePromocode(Request $request): void
    {
        $request->validate([
            RequestData::PROMOCODE_KEY => 'nullable|string',
        ]);
    }

    public static function validateOrderCarts(Request $request): void
    {
        $request->validate([
            RequestData::ORDER_KEY . '.carts' => 'required|array',
            RequestData::ORDER_KEY . '.carts.*.id' => 'required|integer',
            RequestData::ORDER_KEY . '.carts.*.price' => 'nullable|numeric',
        ]);
    }

    public static function validateUser(Request $request): void
    {
        $request->validate([
            RequestData::USER_KEY => 'required|array',
            RequestData::USER_KEY . '.id' => 'nullable|integer',
            RequestData::USER_KEY . '.first' => 'nullable|string',
            RequestData::USER_KEY . '.second' => 'nullable|string',
            RequestData::USER_KEY . '.phone' => 'nullable|string',
            RequestData::USER_KEY . '.email' => 'nullable|email',
            RequestData::USER_KEY . '.middle' => 'nullable|string',
            RequestData::USER_KEY . '.loyaltyUid' => 'nullable',
            RequestData::USER_KEY . '.cardNumber' => 'nullable|string',
        ]);
    }

    public static function validateEnv(Request $request): void
    {
        $request->validate([
            RequestData::ENV_KEY => 'required|string',
        ]);
    }

    public static function validateLentaParams(Request $request): void
    {
        $request->validate([
            RequestData::LENTA_HOST_KEY => 'required|string',
            RequestData::LENTA_AGENT_KEY => 'required|string',
        ]);
    }
}
