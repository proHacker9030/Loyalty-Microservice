<?php

declare(strict_types=1);

namespace App\Helpers;

class UrlHelper
{
    public static function getGuestRedirectResponse(): \Illuminate\Http\RedirectResponse
    {
        $redirect = redirect()->guest(backpack_url('login'));
        if (!env('IS_DEPLOYED', false)) { // на локалке не нужны преобразования
            return $redirect;
        }
        $previousUrl = \Session::get('url.intended');
        $parsedUrl = parse_url($previousUrl);
        $intendedUrl = rtrim(env('APP_URL'), '/') . $parsedUrl['path'];
        if (isset($parsedUrl['query'])) {
            $intendedUrl .= '/?' . $parsedUrl['query'];
        }
        redirect()->setIntendedUrl($intendedUrl);

        return $redirect;
    }
}
