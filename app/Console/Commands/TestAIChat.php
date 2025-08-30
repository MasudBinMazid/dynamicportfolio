<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ChatAIService;

class TestAIChat extends Command
{
    protected $signature = 'chat:test-ai {message}';
    protected $description = 'Test AI chat responses';

    public function handle()
    {
        $message = $this->argument('message');
        $sessionId = 'test-' . time();
        
        try {
            if (!config('chat.ai_enabled')) {
                $this->error('AI chat is disabled. Set CHAT_AI_ENABLED=true in your .env file.');
                return 1;
            }
            
            if (!config('services.openai.api_key')) {
                $this->error('OpenAI API key not configured. Set OPENAI_API_KEY in your .env file.');
                return 1;
            }
            
            $this->info('Testing AI response for: ' . $message);
            $this->info('Session ID: ' . $sessionId);
            $this->line('');
            
            $aiService = new ChatAIService();
            $response = $aiService->generateResponse($message, $sessionId);
            
            $this->info('AI Response:');
            $this->line($response);
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
}
