<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TelegramService;

class TelegramSetupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:setup 
                            {--test : Test the bot configuration}
                            {--webhook : Set up webhook}
                            {--info : Show current configuration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up and test Telegram bot integration';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $telegramService = new TelegramService();

        if ($this->option('info')) {
            return $this->showInfo();
        }

        if ($this->option('test')) {
            return $this->testBot($telegramService);
        }

        if ($this->option('webhook')) {
            return $this->setupWebhook($telegramService);
        }

        // Default: show menu
        return $this->showMenu($telegramService);
    }

    private function showInfo()
    {
        $this->info('=== Telegram Bot Configuration ===');
        $this->line('');
        
        $botToken = config('telegram.bot_token');
        $chatId = config('telegram.chat_id');
        $webhookUrl = config('telegram.webhook_url');
        
        $this->line('Bot Token: ' . ($botToken ? '✅ Configured' : '❌ Not set'));
        $this->line('Chat ID: ' . ($chatId ? '✅ ' . $chatId : '❌ Not set'));
        $this->line('Webhook URL: ' . ($webhookUrl ?: 'Not configured'));
        $this->line('');
        
        if (!$botToken) {
            $this->warn('⚠️  Bot token is not configured. Add TELEGRAM_BOT_TOKEN to your .env file.');
        }
        
        if (!$chatId) {
            $this->warn('⚠️  Chat ID is not configured. Add TELEGRAM_CHAT_ID to your .env file.');
        }
        
        return Command::SUCCESS;
    }

    private function testBot($telegramService)
    {
        $this->info('🤖 Testing Telegram bot...');
        
        $result = $telegramService->testBot();
        
        if ($result['success']) {
            $this->info('✅ Bot test successful!');
            if (isset($result['bot']['first_name'])) {
                $this->line('Bot Name: ' . $result['bot']['first_name']);
            }
            if (isset($result['bot']['username'])) {
                $this->line('Username: @' . $result['bot']['username']);
            }
        } else {
            $this->error('❌ Bot test failed: ' . $result['error']);
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }

    private function setupWebhook($telegramService)
    {
        $this->info('🔗 Setting up webhook...');
        
        $webhookUrl = config('telegram.webhook_url');
        
        if (!$webhookUrl) {
            $this->error('❌ Webhook URL not configured. Check TELEGRAM_WEBHOOK_URL in .env');
            return Command::FAILURE;
        }
        
        $result = $telegramService->setWebhook($webhookUrl);
        
        if ($result) {
            $this->info('✅ Webhook set successfully!');
            $this->line('URL: ' . $webhookUrl);
        } else {
            $this->error('❌ Failed to set webhook');
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }

    private function showMenu($telegramService)
    {
        $this->info('=== Telegram Bot Setup ===');
        $this->line('');
        
        $choice = $this->choice(
            'What would you like to do?',
            [
                'Show current configuration',
                'Test bot connection',
                'Set up webhook',
                'Exit'
            ],
            0
        );

        switch ($choice) {
            case 'Show current configuration':
                return $this->showInfo();
                
            case 'Test bot connection':
                return $this->testBot($telegramService);
                
            case 'Set up webhook':
                return $this->setupWebhook($telegramService);
                
            default:
                $this->info('👋 Goodbye!');
                return Command::SUCCESS;
        }
    }
}
