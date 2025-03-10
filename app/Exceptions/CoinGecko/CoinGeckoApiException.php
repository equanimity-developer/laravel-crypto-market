<?php

declare(strict_types=1);

namespace App\Exceptions\CoinGecko;

class CoinGeckoApiException extends \Exception
{
    protected array $responseData = [];
    protected ?int $statusCode = null;

    public function __construct(
        ?string $message = null,
        int $code = 0,
        ?\Throwable $previous = null,
        array $responseData = [],
        ?int $statusCode = null
    ) {
        $message = $message ?? __('exceptions.coingecko.default');
        parent::__construct($message, $code, $previous);
        $this->responseData = $responseData;
        $this->statusCode = $statusCode;
    }

    public function getResponseData(): array
    {
        return $this->responseData;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }
}
