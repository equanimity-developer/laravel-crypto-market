<?php

declare(strict_types=1);

namespace App\Exceptions\CoinGecko;

class MalformedResponseException extends CoinGeckoApiException
{
    protected ?string $rawResponse = null;

    public function __construct(
        ?string $message = null,
        int $code = 0,
        ?\Throwable $previous = null,
        ?string $rawResponse = null
    ) {
        $message = $message ?? __('exceptions.coingecko.malformed_response.default');
        parent::__construct($message, $code, $previous);
        $this->rawResponse = $rawResponse;
    }

    public function getRawResponse(): ?string
    {
        return $this->rawResponse;
    }
} 