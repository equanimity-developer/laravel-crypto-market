<?php

declare(strict_types=1);

namespace App\Exceptions\CoinGecko;

class ConnectionFailedException extends CoinGeckoApiException
{
    public function __construct(
        ?string $message = null,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $message = $message ?? __('exceptions.coingecko.connection_failed');
        parent::__construct($message, $code, $previous);
    }
} 