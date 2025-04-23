<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $client;
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('GEMINI_API_KEY');
        $this->apiUrl = env('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent');
    }

    public function generateContent($prompt, $maxTokens = 100)
    {
        $cacheKey = 'gemini_response_' . md5($prompt);
        $cachedResponse = Cache::get($cacheKey);

        if ($cachedResponse) {
            return $cachedResponse;
        }

        try {
            $response = $this->client->post($this->apiUrl, [
                'query' => ['key' => $this->apiKey],
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'maxOutputTokens' => $maxTokens,
                    ],
                ],
            ]);

            $result = json_decode($response->getBody(), true);
            $generatedText = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if ($generatedText) {
                Cache::put($cacheKey, $generatedText, now()->addHours(24));
            }

            return $generatedText;
        } catch (\Exception $e) {
            Log::error('Gemini API Error: ' . $e->getMessage());
            return null;
        }
    }
}
