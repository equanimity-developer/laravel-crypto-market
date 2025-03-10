<?php

declare(strict_types=1);

namespace App\Exceptions\CoinGecko;

use App\Exceptions\CoinGecko\Http\BadRequestException;
use App\Exceptions\CoinGecko\Http\ForbiddenException;
use App\Exceptions\CoinGecko\Http\NotFoundException;
use App\Exceptions\CoinGecko\Http\RateLimitException;
use App\Exceptions\CoinGecko\Http\ServerErrorException;
use App\Exceptions\CoinGecko\Http\UnauthorizedException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class CoinGeckoExceptionFactory
{
    public static function createFromResponse(Response $response): CoinGeckoApiException
    {
        $status = $response->status();
        $data = self::getResponseData($response);

        if (isset($data['status'])) {
            return match ($data['status']) {
                10002 => new ApiKeyMissingException(
                    code: 10002,
                    responseData: $data,
                    statusCode: $status
                ),
                10005 => new ProOnlyEndpointException(
                    code: 10005,
                    responseData: $data,
                    statusCode: $status
                ),
                default => self::createFromHttpStatus($status, $data, $response)
            };
        }

        if (isset($data['cloudflare_status']) || $status === 1020) {
            return new CloudflareException(
                responseData: $data,
                statusCode: $status,
                cloudflareCode: $data['cloudflare_status'] ?? 1020
            );
        }

        return self::createFromHttpStatus($status, $data, $response);
    }

    private static function createFromHttpStatus(int $status, array $data, Response $response): CoinGeckoApiException
    {
        return match ($status) {
            400 => new BadRequestException(
                code: 400,
                responseData: $data,
                statusCode: $status
            ),
            401 => new UnauthorizedException(
                code: 401,
                responseData: $data,
                statusCode: $status
            ),
            403 => new ForbiddenException(
                code: 403,
                responseData: $data,
                statusCode: $status
            ),
            404 => new NotFoundException(
                code: 404,
                responseData: $data,
                statusCode: $status
            ),
            429 => new RateLimitException(
                code: 429,
                responseData: $data,
                statusCode: $status,
                retryAfter: (int)($response->header('Retry-After') ?? 60)
            ),
            500, 502, 503, 504 => new ServerErrorException(
                code: 500,
                responseData: $data,
                statusCode: $status
            ),
            default => new CoinGeckoApiException(
                message: __('exceptions.coingecko.unexpected_status', ['status' => $status]),
                responseData: $data,
                statusCode: $status
            )
        };
    }

    public static function createFromConnectionException(ConnectionException $exception): ConnectionFailedException
    {
        Log::error(__('crypto.logs.connection_error', ['message' => $exception->getMessage()]));
        return new ConnectionFailedException(
            previous: $exception
        );
    }

    public static function createMalformedResponseException(string $body, \Exception $previous = null): MalformedResponseException
    {
        return new MalformedResponseException(
            message: $previous && $previous->getMessage() === __('exceptions.coingecko.malformed_response.not_array')
                ? __('exceptions.coingecko.malformed_response.not_array')
                : __('exceptions.coingecko.malformed_response.parse_failed'),
            previous: $previous,
            rawResponse: $body
        );
    }

    private static function getResponseData(Response $response): array
    {
        try {
            return $response->json() ?: [];
        } catch (\Exception $e) {
            return [];
        }
    }
}
