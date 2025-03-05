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
    private string $apiUrl;
    private string $currency;
    private int $perPage;
    private int $cacheTtl;
    private string $cacheKey;

    public function __construct()
    {
        $this->apiUrl = Config::get('crypto.api.base_url', 'https://api.coingecko.com/api/v3');
        $this->currency = Config::get('crypto.api.currency', 'usd');
        $this->perPage = (int)Config::get('crypto.api.per_page', 250);
        $this->cacheTtl = (int)Config::get('crypto.cache.ttl_minutes', 5);
        $this->cacheKey = Config::get('crypto.cache.key', 'crypto_market_full_dataset');
    }

    public function getMarketData(array $params = []): array
    {
        return Cache::remember($this->cacheKey, $this->cacheTtl * 60, function () {
            Log::info(__('crypto.logs.fetching_data'), ['params' => ['per_page' => $this->perPage]]);
            return $this->fetchAllPages();
        });
    }

    private function fetchAllPages(): array
    {
        $page = 1;
        $allCryptos = [];
        
        do {
            try {
                $cryptos = $this->fetchPage($page);
                
                if (empty($cryptos)) {
                    break;
                }
                
                $allCryptos = array_merge($allCryptos, $cryptos);
                
                if (count($cryptos) < $this->perPage) {
                    break;
                }
                
                $page++;
                
            } catch (\Exception $e) {
                Log::error(__('crypto.logs.fetch_error'), ['error' => $e->getMessage()]);
                break;
            }
        } while (true);
        
        return $this->transformToDto($allCryptos);
    }
    
    private function fetchPage(int $page): array
    {
        $response = Http::get("{$this->apiUrl}/coins/markets", [
            'vs_currency' => $this->currency,
            'per_page' => $this->perPage,
            'page' => $page,
            'sparkline' => false,
            'price_change_percentage' => '24h'
        ]);
        
        if ($response->failed()) {
            Log::error(__('crypto.logs.api_error'), [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            throw new \Exception($response->body());
        }
        
        return $response->json() ?? [];
    }
    
    private function transformToDto(array $cryptos): array
    {
        return array_map(function ($crypto) {
            return CryptoMarketDTO::fromArray([
                'id' => $crypto['id'],
                'symbol' => $crypto['symbol'],
                'name' => $crypto['name'],
                'image' => $crypto['image'],
                'current_price' => (float)$crypto['current_price'],
                'market_cap' => (float)$crypto['market_cap'],
                'market_cap_rank' => (int)$crypto['market_cap_rank'],
                'price_change_percentage_24h' => $crypto['price_change_percentage_24h'] !== null 
                    ? (float)$crypto['price_change_percentage_24h'] 
                    : null
            ]);
        }, $cryptos);
    }
}
