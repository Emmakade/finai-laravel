<?php
// app/Services/OpenAIService.php

namespace App\Services;

use OpenAI;
use OpenAI\Client;

class OpenAIService
{
    protected Client $client;

    public function __construct()
    {
        $apiKey = config('services.openai.key');
        if (!$apiKey) {
            throw new \Exception('OpenAI API key is not set.');
        }

        $this->client = OpenAI::client($apiKey);
    }

    /**
     * Send a chat-style completion prompt.
     */
    public function chat(array $messages, string $model = 'gpt-3.5-turbo'): array
    {
        $response = $this->client->chat()->create([
            'model'    => $model ?? config('services.openai.model'),
            'messages' => $messages,
            //'max_tokens' => 500, // Prevents overly long responses
        ]);
        return $response->choices[0]->message->toArray();
    }

    /**
     * Ask for a trading prediction.
     */
    public function predictTradeTiming(array $historicalData): string
    {
        // Build a prompt
        $prompt = [
            ['role' => 'system', 'content' => 'You are a financial prediction assistant.'],
            ['role' => 'user', 'content' => 
                "Given this JSON of historical price data:\n" .
                json_encode($historicalData, JSON_PRETTY_PRINT) .
                "\nWhen is the optimal next buy/sell window? Provide date ranges and rationale."
            ],
        ];

        $reply = $this->chat($prompt);
        return $reply['content'];
    }
}
