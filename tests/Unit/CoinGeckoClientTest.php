<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Adapters\Clients\CoinGeckoClient;
use App\Exceptions\CoinGecko\ApiKeyMissingException;
use App\Exceptions\CoinGecko\CloudflareException;
use App\Exceptions\CoinGecko\ConnectionFailedException;
use App\Exceptions\CoinGecko\MalformedResponseException;
use App\Exceptions\CoinGecko\ProOnlyEndpointException;
use App\Exceptions\CoinGecko\Http\BadRequestException;
use App\Exceptions\CoinGecko\Http\ForbiddenException;
use App\Exceptions\CoinGecko\Http\RateLimitException;
use App\Exceptions\CoinGecko\Http\ServerErrorException;
use App\Exceptions\CoinGecko\Http\UnauthorizedException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Tests\TestCase;

class CoinGeckoClientTest extends TestCase
{
    protected CoinGeckoClient $client;
    protected int $perPage = 250;
    protected int $page = 1;

    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();

        $this->client = new CoinGeckoClient();
    }

    public function test_client_can_get_markets()
    {
        Http::fake([
            '*coins/markets*' => Http::response([
                [
                    'id' => 'bitcoin',
                    'symbol' => 'btc',
                    'name' => 'Bitcoin',
                    'image' => 'https://example.com/btc.png',
                    'current_price' => 50000,
                    'market_cap' => 950000000000,
                    'market_cap_rank' => 1,
                    'price_change_percentage_24h' => 2.5
                ],
                [
                    'id' => 'ethereum',
                    'symbol' => 'eth',
                    'name' => 'Ethereum',
                    'image' => 'https://example.com/eth.png',
                    'current_price' => 3000,
                    'market_cap' => 360000000000,
                    'market_cap_rank' => 2,
                    'price_change_percentage_24h' => 1.2
                ]
            ], 200)
        ]);

        $result = $this->client->getMarkets($this->perPage, $this->page);

        $this->assertCount(2, $result);
        $this->assertEquals('bitcoin', $result[0]['id']);
        $this->assertEquals('ethereum', $result[1]['id']);
    }

    public function test_client_handles_bad_request_400()
    {
        Http::fake([
            '*coins/markets*' => Http::response(['error' => 'Invalid parameters'], 400)
        ]);

        $this->expectException(BadRequestException::class);

        $this->client->getMarkets($this->perPage, $this->page);
    }

    public function test_client_handles_unauthorized_401()
    {
        Http::fake([
            '*coins/markets*' => Http::response(['error' => 'Unauthorized access'], 401)
        ]);

        $this->expectException(UnauthorizedException::class);

        $this->client->getMarkets($this->perPage, $this->page);
    }

    public function test_client_handles_forbidden_403()
    {
        Http::fake([
            '*coins/markets*' => Http::response(['error' => 'Access forbidden'], 403)
        ]);

        $this->expectException(ForbiddenException::class);

        $this->client->getMarkets($this->perPage, $this->page);
    }

    public function test_client_handles_rate_limit_429()
    {
        Http::fake([
            '*coins/markets*' => Http::response(
                ['error' => 'Too many requests'],
                429,
                ['Retry-After' => '60']
            )
        ]);

        $this->expectException(RateLimitException::class);

        try {
            $this->client->getMarkets($this->perPage, $this->page);
        } catch (RateLimitException $e) {
            $this->assertEquals(60, $e->getRetryAfter());
            throw $e;
        }
    }

    public function test_client_handles_server_error_500()
    {
        Http::fake([
            '*coins/markets*' => Http::response(['error' => 'Internal server error'], 500)
        ]);

        $this->expectException(ServerErrorException::class);

        $this->client->getMarkets($this->perPage, $this->page);
    }

    public function test_client_handles_service_unavailable_503()
    {
        Http::fake([
            '*coins/markets*' => Http::response(['error' => 'Service unavailable'], 503)
        ]);

        $this->expectException(ServerErrorException::class);

        $this->client->getMarkets($this->perPage, $this->page);
    }

    public function test_client_handles_cdn_firewall()
    {
        Http::fake([
            '*coins/markets*' => Http::response([
                'error' => 'Access denied by CDN',
                'cloudflare_status' => 1020
            ], 403)
        ]);

        $this->expectException(CloudflareException::class);

        try {
            $this->client->getMarkets($this->perPage, $this->page);
        } catch (CloudflareException $e) {
            $this->assertEquals(1020, $e->getCloudflareCode());
            $this->assertEquals([
                'error' => 'Access denied by CDN',
                'cloudflare_status' => 1020
            ], $e->getResponseData());
            $this->assertEquals(403, $e->getStatusCode());
            throw $e;
        }
    }

    public function test_client_handles_api_key_missing_10002()
    {
        Http::fake([
            '*coins/markets*' => Http::response(
                ['error' => 'API key missing', 'status' => 10002],
                401
            )
        ]);

        $this->expectException(ApiKeyMissingException::class);

        $this->client->getMarkets($this->perPage, $this->page);
    }

    public function test_client_handles_pro_only_endpoint_10005()
    {
        Http::fake([
            '*coins/markets*' => Http::response(
                ['error' => 'This request is limited to Pro API subscribers', 'status' => 10005],
                403
            )
        ]);

        $this->expectException(ProOnlyEndpointException::class);

        $this->client->getMarkets($this->perPage, $this->page);
    }

    public function test_client_handles_connection_timeout()
    {
        Http::fake(function () {
            throw new ConnectionException('Connection timed out');
        });

        $this->expectException(ConnectionFailedException::class);

        $this->client->getMarkets($this->perPage, $this->page);
    }

    public function test_client_handles_malformed_json()
    {
        Http::fake([
            '*coins/markets*' => Http::response(
                'This is not valid JSON',
                200,
                ['Content-Type' => 'application/json']
            )
        ]);

        $this->expectException(MalformedResponseException::class);

        try {
            $this->client->getMarkets($this->perPage, $this->page);
        } catch (MalformedResponseException $e) {
            $this->assertEquals('This is not valid JSON', $e->getRawResponse());
            throw $e;
        }
    }
}
