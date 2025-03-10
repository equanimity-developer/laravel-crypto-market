<?php

declare(strict_types=1);

namespace App\Exceptions\CoinGecko;

use App\Exceptions\CoinGecko\Http\ForbiddenException;

class ProOnlyEndpointException extends ForbiddenException
{
    public function __construct(
        ?string $message = null,
        int $code = 10005,
        ?\Throwable $previous = null,
        array $responseData = [],
        ?int $statusCode = 403
    ) {
        $message = $message ?? __('exceptions.coingecko.pro_only_endpoint');
        parent::__construct($message, $code, $previous, $responseData, $statusCode);
    }
} 