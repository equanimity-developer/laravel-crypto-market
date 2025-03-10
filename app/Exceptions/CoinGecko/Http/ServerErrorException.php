<?php

declare(strict_types=1);

namespace App\Exceptions\CoinGecko\Http;

use App\Exceptions\CoinGecko\CoinGeckoApiException;

class ServerErrorException extends CoinGeckoApiException
{
    public function __construct(
        ?string $message = null,
        int $code = 500,
        ?\Throwable $previous = null,
        array $responseData = [],
        ?int $statusCode = 500
    ) {
        $message = $message ?? __('exceptions.coingecko.http.server_error');
        parent::__construct($message, $code, $previous, $responseData, $statusCode);
    }
} 