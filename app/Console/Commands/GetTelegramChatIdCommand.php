<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TelegramService;

class GetTelegramChatIdCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:get-chat-id {bot_token}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get your Telegram chat ID by providing the bot token';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $botToken = $this->argument('bot_token');
        
        // Temporarily set the bot token in config
        config(['telegram.bot_token' => $botToken]);
        
        try {
            $telegramService = new TelegramService();
            $result = $telegramService->getUpdates();
            
            if (!$result['success']) {
                $this->error('❌ Error: ' . $result['error']);
                return Command::FAILURE;
            }

            $updates = $result['updates'];
            
            if (empty($updates)) {
                $this->error('❌ No messages found!');
                $this->line('');
                $this->info('To get your chat ID:');
                $this->line('1. Start a chat with your bot on Telegram');
                $this->line('2. Send any message to your bot (like "Hello")');
                $this->line('3. Run this command again');
                return Command::FAILURE;
            }
            
            $this->info('✅ Found messages! Here are the chat IDs:');
            $this->line('');
            
            $chatIds = [];
            foreach ($updates as $update) {
                if (isset($update['message'])) {
                    $chat = $update['message']['chat'];
                    $chatId = $chat['id'];
                    $chatTitle = ($chat['first_name'] ?? '') . ' ' . ($chat['last_name'] ?? '');
                    $username = $chat['username'] ?? null;
                    
                    if (!in_array($chatId, $chatIds)) {
                        $chatIds[] = $chatId;
                        $this->line("💬 Chat ID: <fg=green>{$chatId}</>");
                        $this->line("👤 Name: {$chatTitle}");
                        if ($username) {
                            $this->line("🔗 Username: @{$username}");
                        }
                        $this->line('');
                    }
                }
            }
            
            if (count($chatIds) === 1) {
                $this->info('💡 Add this to your .env file:');
                $this->line("TELEGRAM_CHAT_ID={$chatIds[0]}");
            } else {
                $this->info('💡 Choose one of the Chat IDs above and add it to your .env file:');
                $this->line('TELEGRAM_CHAT_ID=your-chosen-chat-id');
            }
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
