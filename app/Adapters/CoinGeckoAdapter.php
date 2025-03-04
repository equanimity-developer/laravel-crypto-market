<?php

declare(strict_types=1);

namespace App\Adapters;

use App\Adapters\Interfaces\CryptoAdapterInterface;
use App\DTOs\CryptoMarketDTO;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CoinGeckoAdapter implements CryptoAdapterInterface
{
    protected string $baseUrl;
    protected int $cacheTtl;

    public function __construct()
    {
        $this->baseUrl = Config::get('crypto.api.base_url');
        $this->cacheTtl = Config::get('crypto.cache.ttl_minutes');
    }

    public function getMarketData(array $params = []): array
    {
        $defaultParams = [
            'vs_currency' => 'usd',
            'per_page' => 50,
            'page' => 1,
            'sparkline' => false,
            'price_change_percentage' => '24h'
        ];

        $finalParams = array_merge($defaultParams, $params);
        $cacheKey = 'crypto_market_' . md5(serialize($finalParams));

        return Cache::remember($cacheKey, $this->cacheTtl * 60, function () use ($finalParams) {
            Log::info(__('crypto.logs.fetching_data'), ['params' => $finalParams]);

            $response = Http::get("{$this->baseUrl}/coins/markets", $finalParams);

            if ($response->failed()) {
                Log::error(__('crypto.logs.api_error'), [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception(__('crypto.logs.fetch_error') . ' ' . $response->status());
            }

            $data = $response->json();
            
            return array_map(
                fn (array $item) => CryptoMarketDTO::fromArray($item),
                $data
            );
        });
    }
}
