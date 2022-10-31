<?php

declare(strict_types=1);

namespace App\Services;

use Spatie\Regex\Regex;

class SocketAccessChecker
{
    public const DEFAULT_PORT = 80;

    public function check(string $url): bool
    {
        [$address, $port] = $this->pullOutParams($url);

        return $this->hasAccess($address, $port);
    }

    private function pullOutParams(string $url): array
    {
        $url = Regex::replace('/^https?:\/\//', '', $url)->result();
        $address = Regex::match('/^[\w\-\.]+/', $url)->result();
        $port = Regex::match('/(?<=\w:)\d+/', $url)->result();

        return [$address, $port];
    }

    private function hasAccess(string $address, ?string $port): bool
    {
        // Build command.
        $command = "nmap -Pn {$address} -p ";
        $command .= $port ?: self::DEFAULT_PORT;

        // Gather command output.
        exec($command, $output);
        $output = implode(' ', $output);

        // Search for meaningful out string (something like "80/tcp open  http").
        $meaningful = Regex::match('/(?:\d+\/\w+)\s+(?:\w+)\s+(?:[\w\-]+)/', $output)->result();
        if (!$meaningful) {
            return false;
        }

        // Check for is port opened.
        return Regex::match('/^(?:\d+\/\w+)\s+(?:open)\s+(?!unknown)/', $meaningful)->hasMatch();
    }
}
