<?php

declare(strict_types=1);

namespace App\Adapters\Interfaces;

use App\DTOs\CryptoMarketDTO;

interface CryptoAdapterInterface
{
    /** @return array<CryptoMarketDTO> */
    public function getMarketData(array $params = []): array;
}
