<?php

declare(strict_types=1);

namespace App\Exceptions\CoinGecko\Http;

use App\Exceptions\CoinGecko\CoinGeckoApiException;

class NotFoundException extends CoinGeckoApiException
{
    public function __construct(
        ?string $message = null,
        int $code = 404,
        ?\Throwable $previous = null,
        array $responseData = [],
        ?int $statusCode = 404
    ) {
        $message = $message ?? __('exceptions.coingecko.http.not_found');
        parent::__construct($message, $code, $previous, $responseData, $statusCode);
    }
} 