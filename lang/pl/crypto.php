<?php

return [
    'page_title' => 'Rynek Krypto',
    'title' => 'Rynek Kryptowalut',
    'loading' => 'Ładowanie danych kryptowalut...',
    'error' => 'Nie można pobrać danych kryptowalut. Spróbuj ponownie później.',

    'refresh' => [
        'button' => 'Odśwież Dane',
        'loading' => 'Odświeżanie...',
        'success' => 'Dane zostały odświeżone',
    ],

    'table' => [
        'rank' => 'Pozycja',
        'name' => 'Nazwa',
        'price' => 'Cena',
        'change_24h' => 'Zmiana 24h',
        'market_cap' => 'Kapitalizacja',
    ],

    'filters' => [
        'title' => 'Filtry',
        'apply' => 'Zastosuj',
        'reset' => 'Resetuj',
        'search' => 'Szukaj',
        'min_price' => 'Min. cena ($)',
        'max_price' => 'Maks. cena ($)',
        'search_placeholder' => 'Bitcoin, ETH...',
        'min_placeholder' => '0',
        'max_placeholder' => '1000',
    ],

    'logs' => [
        'fetching_data' => 'Pobieranie świeżych danych z API CoinGecko',
        'api_error' => 'Błąd API CoinGecko',
        'fetch_error' => 'Nie udało się pobrać danych rynkowych: :error',
        'error_overview' => 'Błąd podczas pobierania przeglądu rynku',
        'connection_error' => 'Błąd połączenia z API CoinGecko: :message',
        'unexpected_error' => 'Nieoczekiwany błąd API CoinGecko: :message',
        'rate_limit_reached' => 'Osiągnięto limit zapytań API CoinGecko. Spróbuj ponownie za: :seconds sekund',
        'parse_error' => 'Nie udało się przetworzyć odpowiedzi z API CoinGecko',
        'invalid_response' => 'Nieprawidłowy format odpowiedzi z API CoinGecko',
        'http_error' => 'Błąd HTTP API CoinGecko (:status): :message',
        'timeout_error' => 'Przekroczono limit czasu połączenia z API CoinGecko',
        'refreshing_data' => 'Odświeżanie danych rynkowych z API CoinGecko',
        'empty_response' => 'Otrzymano pustą odpowiedź z API CoinGecko',
    ],

    'languages' => [
        'en' => 'Angielski',
        'pl' => 'Polski',
    ],

    'sorting' => [
        'asc' => 'Rosnąco',
        'desc' => 'Malejąco',
    ],

    'pagination' => [
        'previous' => 'Poprzednia',
        'next' => 'Następna',
        'showing' => 'Wyświetlanie',
        'to' => 'do',
        'of' => 'z',
        'results' => 'wyników',
        'per_page' => 'Elementów na stronie:',
    ],

    'messages' => [
        'cache_cleared' => 'Pamięć podręczna wyczyszczona. Wyświetlam najnowsze dane rynkowe.',
    ],

    'controls' => [
        'refresh' => 'Odśwież dane',
    ],
];
