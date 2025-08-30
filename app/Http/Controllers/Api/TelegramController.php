<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    private $telegramService;

    public function __construct()
    {
        $this->telegramService = new TelegramService();
    }

    /**
     * Handle incoming Telegram webhook updates
     */
    public function webhook(Request $request)
    {
        try {
            $update = $request->all();
            
            Log::info('Telegram webhook received', $update);
            
            $handled = $this->telegramService->handleWebhook($update);
            
            if ($handled) {
                return response()->json(['status' => 'ok']);
            }
            
            return response()->json(['status' => 'ignored'], 200);
            
        } catch (\Exception $e) {
            Log::error('Telegram webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Set up webhook URL for the bot
     */
    public function setupWebhook(Request $request)
    {
        try {
            $webhookUrl = config('telegram.webhook_url');
            
            if (!$webhookUrl) {
                return response()->json([
                    'success' => false,
                    'error' => 'Webhook URL not configured'
                ]);
            }
            
            $result = $this->telegramService->setWebhook($webhookUrl);
            
            return response()->json([
                'success' => $result,
                'webhook_url' => $webhookUrl,
                'message' => $result ? 'Webhook set successfully' : 'Failed to set webhook'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Test bot configuration
     */
    public function testBot()
    {
        try {
            $result = $this->telegramService->testBot();
            
            return response()->json($result);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get webhook info
     */
    public function getWebhookInfo()
    {
        try {
            // This would require extending the TelegramService
            return response()->json([
                'webhook_url' => config('telegram.webhook_url'),
                'webhook_enabled' => config('telegram.webhook_enabled')
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
