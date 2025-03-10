<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Adapters\CoinGeckoAdapter;
use App\Adapters\Clients\CoinGeckoClient;
use App\DTOs\CryptoMarketDTO;
use Mockery;
use Tests\TestCase;

class CoinGeckoAdapterTest extends TestCase
{
    protected CoinGeckoAdapter $adapter;
    protected CoinGeckoClient $mockClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockClient = Mockery::mock(CoinGeckoClient::class);

        $this->adapter = new CoinGeckoAdapter($this->mockClient);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_market_data_returns_empty_array_when_client_returns_empty()
    {
        $this->mockClient->shouldReceive('getMarkets')
            ->once()
            ->withAnyArgs()
            ->andReturn([]);

        $result = $this->adapter->getMarketData();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function test_get_market_data_transforms_data_into_dtos()
    {
        $mockData = [
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
        ];

        $this->mockClient->shouldReceive('getMarkets')
            ->once()
            ->withAnyArgs()
            ->andReturn($mockData);

        $result = $this->adapter->getMarketData();

        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(CryptoMarketDTO::class, $result);

        $bitcoin = $result[0];
        $this->assertEquals('bitcoin', $bitcoin->id);
        $this->assertEquals('btc', $bitcoin->symbol);
        $this->assertEquals('Bitcoin', $bitcoin->name);
        $this->assertEquals(50000, $bitcoin->currentPrice);

        $ethereum = $result[1];
        $this->assertEquals('ethereum', $ethereum->id);
        $this->assertEquals('eth', $ethereum->symbol);
        $this->assertEquals('Ethereum', $ethereum->name);
        $this->assertEquals(3000, $ethereum->currentPrice);
    }

    public function test_get_market_data_handles_exceptions_from_client()
    {
        $this->mockClient->shouldReceive('getMarkets')
            ->once()
            ->withAnyArgs()
            ->andThrow(new \Exception(__('exceptions.coingecko.connection_failed')));

        $this->expectException(\Exception::class);

        $this->adapter->getMarketData();
    }
}
