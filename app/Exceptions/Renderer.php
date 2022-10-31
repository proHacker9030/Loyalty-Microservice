<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Renderer
{
    public static function render($code, $message, Request $request)
    {
        if ($request->is('api/*')) {
            return response()->json([
                'message' => $message,
            ], $code);
        }

        throw new HttpException($code, $message);
    }
}
