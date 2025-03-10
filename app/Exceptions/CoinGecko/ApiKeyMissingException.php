<?php

declare(strict_types=1);

namespace App\Exceptions\CoinGecko;

use App\Exceptions\CoinGecko\Http\UnauthorizedException;

class ApiKeyMissingException extends UnauthorizedException
{
    public function __construct(
        ?string $message = null,
        int $code = 10002,
        ?\Throwable $previous = null,
        array $responseData = [],
        ?int $statusCode = 401
    ) {
        $message = $message ?? __('exceptions.coingecko.api_key_missing');
        parent::__construct($message, $code, $previous, $responseData, $statusCode);
    }
} 