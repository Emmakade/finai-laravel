<?php
// app/Http/Controllers/AIController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIService;

class AIController extends Controller
{
    public function __construct(protected OpenAIService $ai) {}

    /** 
     * General chat endpoint for your AI chatbox 
     */
    public function chat(Request $req)
    {
        $req->validate([
            'messages' => 'required|array'
        ]);

        $response = $this->ai->chat($req->input('messages'));
        return response()->json($response);
    }

    /**
     * “When to trade” prediction endpoint
     */
    public function predict(Request $req)
    {
        $req->validate([
            'historical' => 'required|array'
        ]);

        $result = $this->ai->predictTradeTiming($req->input('historical'));
        return response()->json([
            'prediction' => $result
        ]);
    }
}