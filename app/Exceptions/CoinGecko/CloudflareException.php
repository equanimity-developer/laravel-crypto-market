<?php

declare(strict_types=1);

namespace App\Exceptions\CoinGecko;

class CloudflareException extends CoinGeckoApiException
{
    protected ?int $cloudflareCode = null;

    public function __construct(
        ?string $message = null,
        int $code = 0,
        ?\Throwable $previous = null,
        array $responseData = [],
        ?int $statusCode = 403,
        ?int $cloudflareCode = 1020
    ) {
        $message = $message ?? __('exceptions.coingecko.cloudflare');
        parent::__construct($message, $code, $previous, $responseData, $statusCode);
        $this->cloudflareCode = $cloudflareCode;
    }

    public function getCloudflareCode(): ?int
    {
        return $this->cloudflareCode;
    }
} 