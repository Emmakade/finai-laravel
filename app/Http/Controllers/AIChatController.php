<?php
// app/Http/Controllers/AIController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIService;
use Illuminate\Support\Facades\Http;

class AIChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:100',
        ]);

        try {
            $prompt = $request->input('prompt');
            //$truncatedPrompt = substr($prompt, 0, 100);
            $response = Http::withToken(env('OPENAI_API_KEY'))->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are an AI trading assistant.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'reply' => $response->json()['choices'][0]['message']['content'] ?? 'No response received.',
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'OpenAI API error.',
                'details' => $response->json(),
            ], $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}