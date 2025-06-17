<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CryptoService;

class CryptoController extends Controller
{
    private $cryptoService;

    public function __construct(CryptoService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }

    public function getSymbols()
    {
        $symbols = $this->cryptoService->getSymbols();       
        return response()->json($symbols);
    }

    public function getFilteredSymbols()
    {
        try {
            $symbols = $this->cryptoService->getFilteredSymbols();
            return response()->json($symbols);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getSymbolDetails($symbol)
    {
        $details = $this->cryptoService->getSymbolDetails($symbol);
        return response()->json($details);
    }

    public function getPredictionWithConfidence($symbol)
    {
        try {
            $prediction = $this->cryptoService->getPredictionWithConfidence($symbol);
            return response()->json($prediction);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
