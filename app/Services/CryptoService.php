<?php

declare(strict_types=1);

namespace App\Services;

use App\Adapters\Interfaces\CryptoAdapterInterface;
use App\DTOs\CryptoMarketDTO;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class CryptoService
{
    protected CryptoAdapterInterface $cryptoAdapter;
    private int $cacheTtl;
    private string $cacheKey;

    public function __construct(CryptoAdapterInterface $cryptoAdapter)
    {
        $this->cryptoAdapter = $cryptoAdapter;
        $this->cacheTtl = (int)Config::get('crypto.cache.ttl_minutes', 5);
        $this->cacheKey = Config::get('crypto.cache.key', 'crypto_market_full_dataset');
    }

    public function getMarketOverview(): array
    {
        try {
            $cryptos = $this->getCryptoData();

            return array_map(
                fn (CryptoMarketDTO $crypto) => $crypto->toArray(),
                $cryptos
            );
        } catch (\Exception $e) {
            Log::error(__('crypto.logs.error_overview'), [
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    private function getCryptoData(): array
    {
        return Cache::remember($this->cacheKey, $this->cacheTtl * 60, function () {
            Log::info(__('crypto.logs.fetching_data'), ['source' => 'service']);
            return $this->cryptoAdapter->getMarketData();
        });
    }
}
