<?php

declare(strict_types=1);

namespace App\Exceptions\CoinGecko\Http;

use App\Exceptions\CoinGecko\CoinGeckoApiException;

class BadRequestException extends CoinGeckoApiException
{
    public function __construct(
        ?string $message = null,
        int $code = 400,
        ?\Throwable $previous = null,
        array $responseData = [],
        ?int $statusCode = 400
    ) {
        $message = $message ?? __('exceptions.coingecko.http.bad_request');
        parent::__construct($message, $code, $previous, $responseData, $statusCode);
    }
} 