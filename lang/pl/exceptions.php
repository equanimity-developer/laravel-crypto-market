<?php

return [
    'coingecko' => [
        'default' => 'Błąd API CoinGecko',
        'http' => [
            'bad_request' => 'Nieprawidłowe żądanie - błędne parametry',
            'unauthorized' => 'Nieautoryzowany dostęp - wymagane uwierzytelnienie',
            'forbidden' => 'Dostęp zabroniony',
            'not_found' => 'Zasób nie został znaleziony',
            'rate_limit' => 'Przekroczono limit zapytań. Spróbuj ponownie później.',
            'server_error' => 'Wewnętrzny błąd serwera',
        ],
        'api_key_missing' => 'Brak lub nieprawidłowy klucz API',
        'pro_only_endpoint' => 'Ta funkcja jest dostępna tylko dla subskrybentów wersji Pro',
        'cloudflare' => 'Dostęp zablokowany przez zaporę CDN',
        'connection_failed' => 'Nie udało się połączyć z API CoinGecko',
        'malformed_response' => [
            'default' => 'Otrzymano nieprawidłową odpowiedź',
            'not_array' => 'Odpowiedź nie jest tablicą',
            'parse_failed' => 'Nie udało się przetworzyć odpowiedzi JSON',
        ],
        'unexpected_status' => 'Nieoczekiwany kod statusu: :status',
    ],
]; 