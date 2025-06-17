<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CryptoService
{
    private $baseUrl = 'https://api.coingecko.com/api/v3';

    public function getSymbols()
    {
        return Cache::remember('crypto_symbols', 3600, function () {
            $response = Http::get("{$this->baseUrl}/coins/markets", [
                'vs_currency' => 'usd',
                'order' => 'market_cap_desc',
                'per_page' => 20,
                'page' => 1
            ]);
            if ($response->successful()) {
                return $response->json();
            }
            return [];
        });
    }

    public function getFilteredSymbols()
    {
        $cacheKey = 'crypto_filtered_symbols';
        $cachedData = Cache::get($cacheKey);

        if ($cachedData) {
            return $cachedData;
        }

        $response = Http::get("{$this->baseUrl}/coins/markets", [
            'vs_currency' => 'usd',
            'order' => 'market_cap_desc',
            'per_page' => 20,
            'page' => 1,
            'sparkline' => false,
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to fetch symbols from API');
        }

        $filteredSymbols = collect($response->json())->map(function ($coin) {
            return [
                'symbol' => $coin['symbol'],
                'name' => $coin['name'],
                'current_price' => $coin['current_price'],
                'price_change_percentage_24h' => $coin['price_change_percentage_24h'],
            ];
        });

        Cache::put($cacheKey, $filteredSymbols, now()->addMinutes(60));

        return $filteredSymbols;
    }

    public function getSymbolDetails($symbol)
    {
        return Cache::remember("crypto_symbol_details_{$symbol}", 3600, function () use ($symbol) {
            $response = Http::get("{$this->baseUrl}/coins/{$symbol}");

            if ($response->failed()) {
                throw new \Exception('Failed to fetch symbol details: ' . $response->body());
            }

            $data = $response->json();

            // Ensure the response has the expected structure
            if (!is_array($data) || empty($data['symbol'])) {
                throw new \Exception('Unexpected response structure: ' . json_encode($data));
            }

            // Map the fields directly
            return [
                'symbol' => $data['symbol'],
                'name' => $data['name'],
                'current_price' => $data['market_data']['current_price']['usd'] ?? null,
                'price_change_percentage_24h' => $data['market_data']['price_change_percentage_24h'] ?? null,
                'price_change_percentage_7d' => $data['market_data']['price_change_percentage_7d'] ?? null,
                'price_change_percentage_14d' => $data['market_data']['price_change_percentage_14d'] ?? null,
                'price_change_percentage_30d' => $data['market_data']['price_change_percentage_30d'] ?? null,
            ];
        });
    }

    public function getPredictionWithConfidence($symbol)
    {
        $data = Http::get("{$this->baseUrl}/coins/{$symbol}")->json();

        if (isset($data['market_data'])) {
            $marketData = $data['market_data'];

            // Extract changes for different timeframes
            $changes = [
                '24h' => $marketData['price_change_percentage_24h'] ?? 0,
                '7d' => $marketData['price_change_percentage_7d'] ?? 0,
                '14d' => $marketData['price_change_percentage_14d'] ?? 0,
                '30d' => $marketData['price_change_percentage_30d'] ?? 0,
                '60d' => $marketData['price_change_percentage_60d'] ?? 0,
                '200d' => $marketData['price_change_percentage_200d'] ?? 0,
                '1y' => $marketData['price_change_percentage_1y'] ?? 0,
            ];

            // Define weights for timeframes
            $weights = [
                '24h' => 0.4,
                '7d' => 0.3,
                '14d' => 0.15,
                '30d' => 0.1,
                '60d' => 0.03,
                '200d' => 0.02,
                '1y' => 0.02,
            ];

            // Calculate weighted average
            $weightedAverage = 0;
            foreach ($changes as $timeframe => $change) {
                $weightedAverage += ($change * $weights[$timeframe]);
            }

            // Determine prediction category and confidence
            $confidence = min(100, abs($weightedAverage) * 10); // Scale confidence appropriately
            $recommendation = 'Hold'; // Default recommendation
            $color = 'orange';

            if ($weightedAverage > 2) {
                $recommendation = 'Buy';
                $color = 'green';
            } elseif ($weightedAverage < -2) {
                $recommendation = 'Sell';
                $color = 'red';
            }

            return [
                'symbol' => $data['symbol'],
                'name' => $data['name'],
                'weighted_average_change' => $weightedAverage,
                'recommendation' => $recommendation,
                'color' => $color,
                'confidence' => round($confidence, 2),
            ];
        }

        return ['error' => 'Unable to fetch market data for prediction.'];
    }

}