<?php

declare(strict_types=1);

namespace App\Adapters;

use App\Adapters\Clients\CoinGeckoClient;
use App\Adapters\Interfaces\CryptoAdapterInterface;
use App\DTOs\CryptoMarketDTO;
use Illuminate\Support\Facades\Log;

class CoinGeckoAdapter implements CryptoAdapterInterface
{
    const MAX_PAGES = 4;
    const PER_PAGE = 250;

    public function __construct(readonly private CoinGeckoClient $client)
    {
    }

    public function getMarketData(): array
    {
        Log::info(__('crypto.logs.fetching_data'));

        return $this->fetchTopResults();
    }

    private function fetchTopResults(): array
    {
        $allCryptos = [];

        for ($page = 1; $page <= self::MAX_PAGES; $page++) {
            try {
                $cryptos = $this->client->getMarkets(self::PER_PAGE, $page);

                if (empty($cryptos)) {
                    break;
                }

                $allCryptos = array_merge($allCryptos, $cryptos);

                if (count($cryptos) < self::PER_PAGE) {
                    break;
                }

            } catch (\Exception $e) {
                Log::error(__('crypto.logs.fetch_error'), ['error' => $e->getMessage()]);
                throw $e;
            }
        }

        return $this->transformToDto($allCryptos);
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
