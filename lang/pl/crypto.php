<?php

return [
    'page_title' => 'Rynek Krypto',
    'title' => 'Rynek Kryptowalut',
    'loading' => 'Ładowanie danych kryptowalut...',
    'error' => 'Nie można pobrać danych kryptowalut. Spróbuj ponownie później.',
    
    'table' => [
        'rank' => 'Pozycja',
        'name' => 'Nazwa',
        'price' => 'Cena',
        'change_24h' => 'Zmiana 24h',
        'market_cap' => 'Kapitalizacja',
    ],
    
    'filters' => [
        'title' => 'Filtry',
        'apply' => 'Zastosuj Filtry',
        'reset' => 'Resetuj',
        'search' => 'Szukaj',
        'min_price' => 'Min. Cena ($)',
        'max_price' => 'Maks. Cena ($)',
        'search_placeholder' => 'Bitcoin, ETH...',
        'min_placeholder' => '0',
        'max_placeholder' => '1000',
    ],
    
    'logs' => [
        'fetching_data' => 'Pobieranie świeżych danych z API CoinGecko',
        'api_error' => 'Błąd API CoinGecko',
        'fetch_error' => 'Nie udało się pobrać danych rynkowych:',
        'error_overview' => 'Błąd podczas pobierania przeglądu rynku',
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
        'showing' => 'Wyświetlanie',
        'to' => 'do',
        'of' => 'z',
        'results' => 'wyników',
        'previous' => 'Poprzednia',
        'next' => 'Następna',
    ],
    
    'messages' => [
        'cache_cleared' => 'Pamięć podręczna wyczyszczona. Wyświetlam najnowsze dane rynkowe.',
    ],
    
    'controls' => [
        'refresh' => 'Odśwież dane',
    ],
]; 