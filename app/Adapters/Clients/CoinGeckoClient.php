<?php

declare(strict_types=1);

namespace App\Adapters\Clients;

use App\Exceptions\CoinGecko\CoinGeckoExceptionFactory;
use App\Exceptions\CoinGecko\MalformedResponseException;
use App\Exceptions\CoinGecko\ConnectionFailedException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CoinGeckoClient
{
    private string $baseUrl;
    private string $currency;
    private int $timeout;
    private int $retries;
    private int $retryDelay;

    public function __construct()
    {
        $this->baseUrl = Config::get('crypto.api.base_url', 'https://api.coingecko.com/api/v3');
        $this->currency = Config::get('crypto.api.currency', 'usd');
        $this->timeout = Config::get('crypto.api.timeout', 30);
        $this->retries = Config::get('crypto.api.retries', 3);
        $this->retryDelay = Config::get('crypto.api.retry_delay', 1000);
    }

    public function getMarkets(int $perPage = 250, int $page = 1): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->retry($this->retries, $this->retryDelay)
                ->get("{$this->baseUrl}/coins/markets", [
                    'vs_currency' => $this->currency,
                    'order' => 'market_cap_desc',
                    'per_page' => $perPage,
                    'page' => $page,
                    'sparkline' => false,
                    'price_change_percentage' => '24h'
                ]);

            if ($response->failed()) {
                throw CoinGeckoExceptionFactory::createFromResponse($response);
            }

            return $this->parseResponseData($response);

        } catch (ConnectionException $e) {
            throw CoinGeckoExceptionFactory::createFromConnectionException($e);
        } catch (MalformedResponseException|ConnectionFailedException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('CoinGecko API unexpected error: '.$e->getMessage());
            throw $e;
        }
    }

    protected function parseResponseData($response): array
    {
        $body = $response->body();

        try {
            $data = $response->json();
            if ($data === null) {
                throw CoinGeckoExceptionFactory::createMalformedResponseException(
                    $body,
                    new \Exception('Failed to parse JSON response')
                );
            }
            return $data;
        } catch (\Exception $e) {
            throw CoinGeckoExceptionFactory::createMalformedResponseException(
                $body,
                $e
            );
        }
    }
}
