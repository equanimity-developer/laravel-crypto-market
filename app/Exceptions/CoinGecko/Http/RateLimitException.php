<?php

declare(strict_types=1);

namespace App\Exceptions\CoinGecko\Http;

use App\Exceptions\CoinGecko\CoinGeckoApiException;

class RateLimitException extends CoinGeckoApiException
{
    protected ?int $retryAfter = null;

    public function __construct(
        ?string $message = null,
        int $code = 429,
        ?\Throwable $previous = null,
        array $responseData = [],
        ?int $statusCode = 429,
        ?int $retryAfter = null
    ) {
        $message = $message ?? __('exceptions.coingecko.http.rate_limit');
        parent::__construct($message, $code, $previous, $responseData, $statusCode);
        $this->retryAfter = $retryAfter;
    }

    public function getRetryAfter(): ?int
    {
        return $this->retryAfter;
    }
} 