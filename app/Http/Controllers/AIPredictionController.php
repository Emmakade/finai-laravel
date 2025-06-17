<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI;

class AIPredictionController extends Controller
{
    public function getPrediction(Request $request)
    {
        $symbol = $request->input('symbol');
        $currentPrice = $request->input('current_price');

        $openAI = new \OpenAI\Client(env('OPENAI_API_KEY'));
        $response = $openAI->completions()->create([
            'model' => 'gpt-3.5-turbo',
            'prompt' => "The current price of {$symbol} is {$currentPrice}. Should I BUY or SELL?",
            'max_tokens' => 50,
        ]);

        return response()->json([
            'prediction' => $response['choices'][0]['text'],
            'confidence' => 85 // Mock confidence level
        ]);
    }
}

