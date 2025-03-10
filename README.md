# Laravel Finance - Cryptocurrency Market Data

A Laravel-based application that provides cryptocurrency market data from the CoinGecko API.

## Features

- **Modern Stack**: Built with Laravel, Vue3, Inertia.js, and TailwindCSS
- **API Integration**: Uses Laravel HTTP Client to communicate with CoinGecko API
- **Caching Mechanism**: Responses are cached to improve performance and reduce API calls
- **Multilingual**: Full internationalization support for UI and error messages (English and Polish)
- **Error Handling**: Robust exception system for handling API failures gracefully
- **Clean UI**: User-friendly interface for viewing and filtering cryptocurrency data
- **Design Patterns**: Implements Adapter, Factory, and Repository patterns

## Architecture

The application follows a service-oriented architecture:

1. **Controllers**: Handle HTTP requests and render Inertia views
2. **Services**: Contain business logic and interact with adapters
3. **Adapters**: Interface with the CoinGecko API and transform responses
4. **DTOs**: Type-safe domain objects representing cryptocurrency data
5. **Exception Handling**: Custom exception classes for different API error scenarios

## Error Handling

The application provides robust error handling:

- Custom exception hierarchy for different API error types
- User-friendly error message for frontend
- Detailed error logging for debugging
- Automatic handling of API rate limiting

## Setup

1. Clone the repository
2. Install dependencies:
   ```
   composer install
   npm install
   ```
3. Copy environment file:
   ```
   cp .env.example .env
   ```
4. Generate application key:
   ```
   php artisan key:generate
   ```
5. Configure API settings in `.env`:
   ```
   CRYPTO_API_BASE_URL=https://api.coingecko.com/api/v3
   CRYPTO_API_CURRENCY=usd
   CRYPTO_API_TIMEOUT=30
   CRYPTO_API_RETRIES=3
   CRYPTO_CACHE_TTL_MINUTES=5
   ```
6. Compile assets:
   ```
   npm run dev
   ```
7. Start the development server:
   ```
   sail up
   ```

## Testing

Tests can be run without an internet connection as all external APIs are mocked:

```bash
php artisan test
```
