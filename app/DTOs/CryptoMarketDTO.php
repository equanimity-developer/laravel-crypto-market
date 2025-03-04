<?php

declare(strict_types=1);

namespace App\DTOs;

readonly class CryptoMarketDTO
{
    public function __construct(
        public string $id,
        public string $symbol,
        public string $name,
        public float $currentPrice,
        public int $marketCapRank,
        public float $marketCap,
        public string $image,
        public ?float $priceChangePercentage24h,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            symbol: $data['symbol'],
            name: $data['name'],
            currentPrice: (float)$data['current_price'],
            marketCapRank: (int)$data['market_cap_rank'],
            marketCap: (float)$data['market_cap'],
            image: $data['image'],
            priceChangePercentage24h: isset($data['price_change_percentage_24h'])
                ? (float)$data['price_change_percentage_24h']
                : null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'symbol' => $this->symbol,
            'name' => $this->name,
            'current_price' => $this->currentPrice,
            'market_cap_rank' => $this->marketCapRank,
            'market_cap' => $this->marketCap,
            'image' => $this->image,
            'price_change_percentage_24h' => $this->priceChangePercentage24h,
        ];
    }
}
