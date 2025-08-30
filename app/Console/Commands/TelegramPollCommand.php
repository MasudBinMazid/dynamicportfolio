<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Log;

class TelegramPollCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:poll {--once : Run only once instead of continuous polling}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Poll Telegram for updates (useful for local development)';

    private $telegramService;
    private $lastUpdateId = 0;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->telegramService = new TelegramService();
        
        if (!config('telegram.bot_token')) {
            $this->error('❌ Telegram bot token not configured');
            return Command::FAILURE;
        }

        $this->info('🤖 Starting Telegram polling...');
        $this->info('💡 This will check for new messages every 3 seconds');
        $this->info('⚠️  Press Ctrl+C to stop');
        $this->line('');

        do {
            try {
                $this->pollUpdates();
                
                if ($this->option('once')) {
                    break;
                }
                
                sleep(3); // Poll every 3 seconds
                
            } catch (\Exception $e) {
                $this->error('❌ Polling error: ' . $e->getMessage());
                Log::error('Telegram polling error: ' . $e->getMessage());
                
                if ($this->option('once')) {
                    return Command::FAILURE;
                }
                
                sleep(5); // Wait longer on error
            }
        } while (true);

        return Command::SUCCESS;
    }

    private function pollUpdates()
    {
        $offset = $this->lastUpdateId + 1;
        $result = $this->telegramService->getUpdates($offset);
        
        if (!$result['success']) {
            throw new \Exception($result['error']);
        }

        $updates = $result['updates'];
        
        if (empty($updates)) {
            return;
        }

        foreach ($updates as $update) {
            $updateId = $update['update_id'];
            $this->lastUpdateId = max($this->lastUpdateId, $updateId);
            
            // Process the update
            $processed = $this->telegramService->handleWebhook($update);
            
            if ($processed) {
                if (isset($update['message'])) {
                    $message = $update['message'];
                    $text = $message['text'] ?? '[Non-text message]';
                    $this->line("📨 Processed message: " . substr($text, 0, 50) . "...");
                } elseif (isset($update['callback_query'])) {
                    $this->line("🔘 Processed callback query");
                }
            }
        }
    }
}
