<?php

declare(strict_types=1);

namespace App\Exceptions\CoinGecko\Http;

use App\Exceptions\CoinGecko\CoinGeckoApiException;

class UnauthorizedException extends CoinGeckoApiException
{
    public function __construct(
        ?string $message = null,
        int $code = 401,
        ?\Throwable $previous = null,
        array $responseData = [],
        ?int $statusCode = 401
    ) {
        $message = $message ?? __('exceptions.coingecko.http.unauthorized');
        parent::__construct($message, $code, $previous, $responseData, $statusCode);
    }
} 