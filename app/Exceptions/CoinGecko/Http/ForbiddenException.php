<?php

declare(strict_types=1);

namespace App\Exceptions\CoinGecko\Http;

use App\Exceptions\CoinGecko\CoinGeckoApiException;

class ForbiddenException extends CoinGeckoApiException
{
    public function __construct(
        ?string $message = null,
        int $code = 403,
        ?\Throwable $previous = null,
        array $responseData = [],
        ?int $statusCode = 403
    ) {
        $message = $message ?? __('exceptions.coingecko.http.forbidden');
        parent::__construct($message, $code, $previous, $responseData, $statusCode);
    }
} 