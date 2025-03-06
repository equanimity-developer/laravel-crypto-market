<?php

declare(strict_types=1);

namespace App\Adapters\Clients;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CoinGeckoClient
{
    private string $apiUrl;
    private string $currency;

    public function __construct()
    {
        $this->apiUrl = Config::get('crypto.api.base_url');
        $this->currency = Config::get('crypto.api.currency');
    }

    public function getMarkets(
        int $perPage,
        int $page
    ): array {
        $response = Http::get("{$this->apiUrl}/coins/markets", [
            'vs_currency' => $this->currency,
            'per_page' => $perPage,
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
}
