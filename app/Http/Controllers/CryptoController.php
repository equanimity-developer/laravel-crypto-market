<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\CryptoService;
use Inertia\Inertia;
use Inertia\Response;

class CryptoController extends Controller
{
    protected CryptoService $cryptoService;

    public function __construct(CryptoService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }

    public function index(): Response
    {
        try {
            $cryptos = $this->cryptoService->getMarketOverview();
            
            return Inertia::render('Dashboard', [
                'cryptos' => $cryptos,
                'filters' => [],
            ]);
        } catch (\Exception $e) {
            return Inertia::render('Dashboard', [
                'cryptos' => [],
                'filters' => [],
                'error' => $e->getMessage(),
            ]);
        }
    }
}
