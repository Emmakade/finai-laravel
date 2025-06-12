<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class DeepSeekService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('DEEPSEEK_API_KEY');
    }

    public function sendMessage(array $messages)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.deepseek.com/v1/chat', [
            'messages' => $messages,
        ]);

        Log::info('DeepSeek Request:', [
            'headers' => $response->headers(),
            'body' => $response->body(),
        ]);

        if ($response->failed()) {
            throw new \Exception('DeepSeek API error: ' . $response->body());
        }

        return $response->json();
    }
}
