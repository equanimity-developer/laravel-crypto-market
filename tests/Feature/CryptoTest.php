<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Adapters\CoinGeckoAdapter;
use Dotenv\Repository\Adapter\AdapterInterface;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Services\CryptoService;
use Mockery;

class CryptoTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();
    }

    public function test_crypto_index_page_loads()
    {
        $this->mockCryptoService();

        $response = $this->get(route('crypto.index'));

        $response->assertStatus(200);
        $response->assertViewIs('app');
    }

    public function test_refresh_market_data()
    {
        Http::fake([
            '*' => Http::response([
                'success' => true,
                'data' => [
                    'BTC' => [
                        'price' => 50000,
                        'change_24h' => 5.2,
                        'market_cap' => 950000000000
                    ],
                    'ETH' => [
                        'price' => 3000,
                        'change_24h' => 2.1,
                        'market_cap' => 360000000000
                    ]
                ]
            ], 200)
        ]);

        $this->mockCryptoService();

        $response = $this->post(route('crypto.refresh'));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
    }

    public function test_language_switch()
    {
        $response = $this->get(route('language.switch', ['locale' => 'en']));

        $response->assertStatus(302);
        $response->assertSessionHas('locale', 'en');

        $response = $this->get(route('language.switch', ['locale' => 'es']));

        $response->assertStatus(302);

        $response->assertSessionHas('locale', 'en');
    }

    protected function mockCryptoService()
    {
        $mock = Mockery::mock(CryptoService::class);

        $mock->shouldReceive('getMarketData')->andReturn([
            'BTC' => [
                'name' => 'Bitcoin',
                'symbol' => 'BTC',
                'price' => 50000,
                'change_24h' => 5.2,
                'market_cap' => 950000000000
            ],
            'ETH' => [
                'name' => 'Ethereum',
                'symbol' => 'ETH',
                'price' => 3000,
                'change_24h' => 2.1,
                'market_cap' => 360000000000
            ]
        ]);

        $mock->shouldReceive('refreshMarketData')->andReturn(true);

        $this->app->instance(CryptoService::class, $mock);


        $this->app->instance(
            AdapterInterface::class,
            Mockery::mock(CoinGeckoAdapter::class, function ($mock) {
                $mock->shouldReceive('fetchMarketData')->andReturn([
                    'success' => true,
                    'data' => [
                        'BTC' => [
                            'price' => 50000,
                            'change_24h' => 5.2
                        ],
                        'ETH' => [
                            'price' => 3000,
                            'change_24h' => 2.1
                        ]
                    ]
                ]);
            })
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
