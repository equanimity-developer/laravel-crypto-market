<?php

declare(strict_types=1);

namespace App\Services;

use App\Adapters\Interfaces\CryptoAdapterInterface;
use App\DTOs\CryptoMarketDTO;
use Illuminate\Support\Facades\Log;

class CryptoService
{
    protected CryptoAdapterInterface $cryptoAdapter;

    public function __construct(CryptoAdapterInterface $cryptoAdapter)
    {
        $this->cryptoAdapter = $cryptoAdapter;
    }

    public function getMarketOverview(array $params = []): array
    {
        try {
            $cryptos = $this->cryptoAdapter->getMarketData($params);

            return array_map(
                fn (CryptoMarketDTO $crypto) => $crypto->toArray(),
                $cryptos
            );
        } catch (\Exception $e) {
            Log::error(__('crypto.logs.error_overview'), [
                'message' => $e->getMessage(),
                'params' => $params
            ]);

            throw $e;
        }
    }
}
