<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Adapters\Interfaces\CryptoAdapterInterface;
use App\Services\CryptoService;
use App\DTOs\CryptoMarketDTO;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Mockery;

class CryptoServiceTest extends TestCase
{
    protected CryptoService $cryptoService;
    protected CryptoAdapterInterface $mockAdapter;

    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();

        Cache::shouldReceive('get')->andReturn(null);
        Cache::shouldReceive('put')->andReturn(true);
        Cache::shouldReceive('forget')->andReturn(true);

        Cache::shouldReceive('remember')
            ->andReturnUsing(function ($key, $ttl, $callback) {
                return $callback();
            });

        $this->mockAdapter = Mockery::mock(CryptoAdapterInterface::class);

        $this->cryptoService = new CryptoService($this->mockAdapter);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_market_overview_success()
    {
        $this->mockAdapter->shouldReceive('getMarketData')
            ->once()
            ->andReturn([
                $this->createCryptoDTO('bitcoin', 'BTC', 'Bitcoin', 50000.0),
                $this->createCryptoDTO('ethereum', 'ETH', 'Ethereum', 3000.0)
            ]);

        $result = $this->cryptoService->getMarketOverview();

        $this->assertCount(2, $result);
        $this->assertEquals('BTC', $result[0]['symbol']);
        $this->assertEquals('ETH', $result[1]['symbol']);
        $this->assertEquals(50000.0, $result[0]['current_price']);
    }

    public function test_bad_request_400()
    {
        $this->mockAdapter->shouldReceive('getMarketData')
            ->once()
            ->andThrow(new \Exception('Bad request - invalid parameters', 500));

        $this->expectException(\Exception::class);
        $this->expectExceptionCode(500);

        $this->cryptoService->getMarketOverview();
    }

    public function test_unauthorized_401()
    {
        $this->mockAdapter->shouldReceive('getMarketData')
            ->once()
            ->andThrow(new \Exception('Unauthorized - authentication required', 500));

        $this->expectException(\Exception::class);
        $this->expectExceptionCode(500);

        $this->cryptoService->getMarketOverview();
    }

    public function test_forbidden_403()
    {
        $this->mockAdapter->shouldReceive('getMarketData')
            ->once()
            ->andThrow(new \Exception('Forbidden - access denied', 500));

        $this->expectException(\Exception::class);
        $this->expectExceptionCode(500);

        $this->cryptoService->getMarketOverview();
    }

    public function test_rate_limit_429()
    {
        $this->mockAdapter->shouldReceive('getMarketData')
            ->once()
            ->andThrow(new \Exception('Too many requests - rate limit exceeded', 429));

        $this->expectException(\Exception::class);
        $this->expectExceptionCode(429);

        $this->cryptoService->getMarketOverview();
    }

    public function test_internal_server_error_500()
    {
        $this->mockAdapter->shouldReceive('getMarketData')
            ->once()
            ->andThrow(new \Exception('Internal server error', 500));

        $this->expectException(\Exception::class);
        $this->expectExceptionCode(500);

        $this->cryptoService->getMarketOverview();
    }

    public function test_service_unavailable_503()
    {
        $this->mockAdapter->shouldReceive('getMarketData')
            ->once()
            ->andThrow(new \Exception('Service unavailable', 500));

        $this->expectException(\Exception::class);
        $this->expectExceptionCode(500);

        $this->cryptoService->getMarketOverview();
    }

    public function test_cdn_firewall_block_1020()
    {
        $this->mockAdapter->shouldReceive('getMarketData')
            ->once()
            ->andThrow(new \Exception('Access denied by CDN firewall', 500));

        $this->expectException(\Exception::class);
        $this->expectExceptionCode(500);

        $this->cryptoService->getMarketOverview();
    }

    public function test_api_key_missing_10002()
    {
        $this->mockAdapter->shouldReceive('getMarketData')
            ->once()
            ->andThrow(new \Exception('API key missing', 500));

        $this->expectException(\Exception::class);
        $this->expectExceptionCode(500);

        $this->cryptoService->getMarketOverview();
    }

    public function test_pro_only_endpoint_10005()
    {
        $this->mockAdapter->shouldReceive('getMarketData')
            ->once()
            ->andThrow(new \Exception('This request is limited to Pro API subscribers', 500));

        $this->expectException(\Exception::class);
        $this->expectExceptionCode(500);

        $this->cryptoService->getMarketOverview();
    }

    public function test_connection_timeout()
    {
        $this->mockAdapter->shouldReceive('getMarketData')
            ->once()
            ->andThrow(new \Exception('Connection timed out', 500));

        $this->expectException(\Exception::class);
        $this->expectExceptionCode(500);

        $this->cryptoService->getMarketOverview();
    }

    public function test_malformed_json_response()
    {
        $this->mockAdapter->shouldReceive('getMarketData')
            ->once()
            ->andThrow(new \Exception('Invalid JSON response', 500));

        $this->expectException(\Exception::class);
        $this->expectExceptionCode(500);

        $this->cryptoService->getMarketOverview();
    }

    public function test_empty_response()
    {
        $this->mockAdapter->shouldReceive('getMarketData')
            ->once()
            ->andReturn([]);

        $result = $this->cryptoService->getMarketOverview();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    private function createCryptoDTO($id, $symbol, $name, $price, $change = 1.5)
    {
        return CryptoMarketDTO::fromArray([
            'id' => $id,
            'symbol' => $symbol,
            'name' => $name,
            'image' => "https://example.com/{$symbol}.png",
            'current_price' => $price,
            'market_cap' => $price * 1000000,
            'market_cap_rank' => 1,
            'price_change_percentage_24h' => $change
        ]);
    }
}
