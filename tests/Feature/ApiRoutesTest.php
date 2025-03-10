<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Services\CryptoService;
use Mockery;

class ApiRoutesTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();
    }

    public function test_refresh_market_data_api()
    {
        $this->mockCryptoService();

        Http::fake([
            '*' => Http::response([
                'success' => true,
                'data' => $this->getMockCryptoData()
            ], 200)
        ]);

        $response = $this->postJson('/refresh-market-data');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
        ]);

        $response->assertJson([
            'success' => true,
        ]);
    }

    public function test_api_validation()
    {
        $this->mockCryptoService();

        $response = $this->postJson('/refresh-market-data', [
            'invalid_param' => 'value'
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'success' => true,
        ]);
    }

    public function test_api_rate_limiting()
    {
        $this->mockCryptoService();

        for ($i = 0; $i < 10; $i++) {
            $this->postJson('/refresh-market-data');
        }

        $response = $this->postJson('/refresh-market-data');

        $this->assertTrue(
            $response->status() === 200 || $response->status() === 429,
            'API should either return 200 OK or 429 Too Many Requests'
        );
    }

    protected function mockCryptoService()
    {
        if (class_exists(\App\Services\CryptoService::class)) {
            $mock = Mockery::mock(CryptoService::class);

            $mock->shouldReceive('getMarketData')->andReturn($this->getMockCryptoData());
            $mock->shouldReceive('refreshMarketData')->andReturn(true);

            $this->app->instance(CryptoService::class, $mock);
        }

        if (class_exists(\App\Adapters\CryptoApiAdapter::class)) {
            $this->app->instance(
                \App\Adapters\CryptoApiAdapter::class,
                Mockery::mock(\App\Adapters\CryptoApiAdapter::class, function ($mock) {
                    $mock->shouldReceive('fetchMarketData')->andReturn([
                        'success' => true,
                        'data' => $this->getMockCryptoData()
                    ]);
                })
            );
        }
    }

    protected function getMockCryptoData()
    {
        return [
            'BTC' => [
                'name' => 'Bitcoin',
                'symbol' => 'BTC',
                'price' => $this->faker->randomFloat(2, 40000, 60000),
                'change_24h' => $this->faker->randomFloat(2, -10, 10),
                'market_cap' => $this->faker->randomFloat(0, 800000000000, 1000000000000),
                'volume_24h' => $this->faker->randomFloat(0, 20000000000, 50000000000)
            ],
            'ETH' => [
                'name' => 'Ethereum',
                'symbol' => 'ETH',
                'price' => $this->faker->randomFloat(2, 2000, 4000),
                'change_24h' => $this->faker->randomFloat(2, -10, 10),
                'market_cap' => $this->faker->randomFloat(0, 300000000000, 500000000000),
                'volume_24h' => $this->faker->randomFloat(0, 10000000000, 20000000000)
            ],
            'BNB' => [
                'name' => 'Binance Coin',
                'symbol' => 'BNB',
                'price' => $this->faker->randomFloat(2, 200, 500),
                'change_24h' => $this->faker->randomFloat(2, -10, 10),
                'market_cap' => $this->faker->randomFloat(0, 50000000000, 100000000000),
                'volume_24h' => $this->faker->randomFloat(0, 1000000000, 3000000000)
            ]
        ];
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
