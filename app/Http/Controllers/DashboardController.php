<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Investment;

class DashboardController extends Controller
{
    public function getDashboardData(Request $request)
    {
        $userId = auth()->id();

        // Portfolio Value
        $portfolio = Wallet::where('user_id', $userId)->get();
        $portfolioValue = $portfolio->sum(function ($wallet) {
            return $wallet->quantity * $wallet->price_usd; // price_usd fetched from crypto API
        });

        // Invested Value
        $investedValue = Investment::where('user_id', $userId)->sum('amount_invested');

        // Yield Calculation
        $yield = $portfolioValue - $investedValue;

        return response()->json([
            'portfolioValue' => $portfolioValue,
            'investedValue' => $investedValue,
            'yield' => $yield,
            'yieldChange' => $yield >= 1 ? 'green' : 'red'
        ]);
    }
}