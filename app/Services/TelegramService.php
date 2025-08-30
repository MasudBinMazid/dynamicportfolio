<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Log;
use Exception;

class TelegramService
{
    private $botToken;
    private $chatId;
    private $baseUrl;

    public function __construct()
    {
        $this->botToken = config('telegram.bot_token');
        $this->chatId = config('telegram.chat_id');
        $this->baseUrl = "https://api.telegram.org/bot{$this->botToken}";
    }

    /**
     * Send a new chat message notification to Telegram
     */
    public function sendNewMessageNotification(ChatMessage $message)
    {
        try {
            if (!$this->botToken || !$this->chatId) {
                Log::warning('Telegram not configured properly');
                return false;
            }

            $text = $this->formatNewMessageText($message);
            
            // Send notification with inline keyboard for quick reply
            $keyboard = [
                'inline_keyboard' => [
                    [
                        [
                            'text' => '💬 Reply',
                            'callback_data' => 'reply_' . $message->session_id
                        ],
                        [
                            'text' => '👁️ View Chat',
                            'url' => url('/admin/chat/' . $message->session_id)
                        ]
                    ]
                ]
            ];

            $response = Http::post("{$this->baseUrl}/sendMessage", [
                'chat_id' => $this->chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode($keyboard)
            ]);

            if ($response->successful()) {
                return true;
            } else {
                Log::error('Telegram API error: ' . $response->body());
                return false;
            }

        } catch (Exception $e) {
            Log::error('Telegram notification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Handle incoming Telegram webhook updates
     */
    public function handleWebhook($update)
    {
        try {
            // Handle callback queries (inline button presses)
            if (isset($update['callback_query'])) {
                return $this->handleCallbackQuery($update['callback_query']);
            }
            
            // Handle regular messages (replies)
            if (isset($update['message'])) {
                return $this->handleMessage($update['message']);
            }

            return false;
        } catch (Exception $e) {
            Log::error('Telegram webhook handling failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Handle callback queries from inline keyboards
     */
    private function handleCallbackQuery($callbackQuery)
    {
        try {
            $data = $callbackQuery['data'];
            $messageId = $callbackQuery['message']['message_id'];
            $chatId = $callbackQuery['message']['chat']['id'];
            
            Log::info('Telegram callback query received', [
                'data' => $data,
                'chat_id' => $chatId,
                'message_id' => $messageId
            ]);
            
            if (strpos($data, 'reply_') === 0) {
                $sessionId = substr($data, 6);
                
                Log::info('Entering reply mode for session', ['session_id' => $sessionId]);
                
                // Update the message to show reply mode
                $text = "💬 <b>Reply Mode Active</b>\n\n";
                $text .= "Session ID: <code>{$sessionId}</code>\n\n";
                $text .= "Send your reply message now, or use /cancel to exit reply mode.";
                
                // Store the active reply session
                cache()->put('telegram_reply_session_' . $chatId, $sessionId, 3600); // 1 hour
                
                Log::info('Reply session cached', [
                    'cache_key' => 'telegram_reply_session_' . $chatId,
                    'session_id' => $sessionId
                ]);
                
                Http::post("{$this->baseUrl}/editMessageText", [
                    'chat_id' => $chatId,
                    'message_id' => $messageId,
                    'text' => $text,
                    'parse_mode' => 'HTML'
                ]);
                
                Http::post("{$this->baseUrl}/answerCallbackQuery", [
                    'callback_query_id' => $callbackQuery['id'],
                    'text' => 'Reply mode activated! Send your message.'
                ]);
                
                Log::info('Reply mode activated successfully');
            }
            
            return true;
        } catch (Exception $e) {
            Log::error('Callback query handling failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Handle regular text messages (replies)
     */
    private function handleMessage($message)
    {
        try {
            $chatId = $message['chat']['id'];
            $text = $message['text'];
            
            Log::info('Telegram message received', [
                'chat_id' => $chatId,
                'text' => $text,
                'expected_chat_id' => $this->chatId
            ]);
            
            // Only handle messages from the configured admin chat
            if ($chatId != $this->chatId) {
                Log::info('Ignoring message from different chat ID');
                return false;
            }
            
            // Handle cancel command
            if ($text === '/cancel') {
                cache()->forget('telegram_reply_session_' . $chatId);
                $this->sendMessage($chatId, '❌ Reply mode cancelled.');
                Log::info('Reply mode cancelled');
                return true;
            }
            
            // Check if we're in reply mode
            $sessionId = cache()->get('telegram_reply_session_' . $chatId);
            
            Log::info('Checking reply mode', [
                'session_id' => $sessionId,
                'cache_key' => 'telegram_reply_session_' . $chatId
            ]);
            
            if ($sessionId) {
                // Send the reply to the chat session
                $chatMessage = ChatMessage::create([
                    'session_id' => $sessionId,
                    'message' => $text,
                    'sender_type' => 'admin'
                ]);
                
                Log::info('Chat message created', [
                    'id' => $chatMessage->id,
                    'session_id' => $sessionId,
                    'message' => $text
                ]);
                
                // Clear reply mode
                cache()->forget('telegram_reply_session_' . $chatId);
                
                // Confirm reply sent
                $this->sendMessage(
                    $chatId,
                    "✅ Reply sent to session: <code>{$sessionId}</code>",
                    'HTML'
                );
                
                Log::info('Reply sent successfully');
                return true;
            }
            
            // If not in reply mode, show help
            $this->sendHelpMessage($chatId);
            Log::info('Help message sent');
            return true;
            
        } catch (Exception $e) {
            Log::error('Message handling failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send a simple text message
     */
    private function sendMessage($chatId, $text, $parseMode = null)
    {
        $data = [
            'chat_id' => $chatId,
            'text' => $text
        ];

        if ($parseMode) {
            $data['parse_mode'] = $parseMode;
        }

        return Http::post("{$this->baseUrl}/sendMessage", $data);
    }

    /**
     * Send help message
     */
    private function sendHelpMessage($chatId)
    {
        $text = "🤖 <b>Portfolio Chat Bot</b>\n\n";
        $text .= "I'll notify you when visitors send messages to your portfolio chat.\n\n";
        $text .= "<b>Commands:</b>\n";
        $text .= "/cancel - Cancel reply mode\n\n";
        $text .= "<b>How to reply:</b>\n";
        $text .= "1. Click 💬 Reply button on notifications\n";
        $text .= "2. Send your reply message\n";
        $text .= "3. Message will be sent to the visitor";
        
        $this->sendMessage($chatId, $text, 'HTML');
    }

    /**
     * Format new message notification text
     */
    private function formatNewMessageText(ChatMessage $message)
    {
        $text = "🔔 <b>New Chat Message</b>\n\n";
        
        if ($message->name) {
            $text .= "👤 <b>Name:</b> " . htmlspecialchars($message->name) . "\n";
        }
        
        if ($message->email) {
            $text .= "📧 <b>Email:</b> " . htmlspecialchars($message->email) . "\n";
        }
        
        $text .= "🔗 <b>Session:</b> <code>" . $message->session_id . "</code>\n";
        $text .= "⏰ <b>Time:</b> " . $message->created_at->format('M d, Y H:i') . "\n\n";
        
        $text .= "💬 <b>Message:</b>\n";
        $text .= htmlspecialchars($message->message);
        
        // Add context if there are previous messages
        $previousCount = ChatMessage::bySession($message->session_id)
            ->where('id', '<', $message->id)
            ->count();
            
        if ($previousCount > 0) {
            $text .= "\n\n📝 <i>This is message #" . ($previousCount + 1) . " in this conversation.</i>";
        }
        
        return $text;
    }

    /**
     * Set webhook URL for the bot
     */
    public function setWebhook($url)
    {
        try {
            if (!$this->botToken) {
                return false;
            }
            
            $response = Http::post("{$this->baseUrl}/setWebhook", [
                'url' => $url
            ]);

            return $response->successful();
        } catch (Exception $e) {
            Log::error('Webhook setup failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Test the bot connection
     */
    public function testBot()
    {
        try {
            if (!$this->botToken || !$this->chatId) {
                return [
                    'success' => false,
                    'error' => 'Bot token or chat ID not configured'
                ];
            }
            
            $response = Http::get("{$this->baseUrl}/getMe");
            
            if (!$response->successful()) {
                return [
                    'success' => false,
                    'error' => 'Invalid bot token'
                ];
            }

            $botInfo = $response->json()['result'];
            
            $this->sendMessage(
                $this->chatId,
                "🤖 Bot test successful!\n\nBot Name: " . $botInfo['first_name'] . "\nUsername: @" . $botInfo['username']
            );
            
            return [
                'success' => true,
                'bot' => $botInfo
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get chat updates to find chat ID
     */
    public function getUpdates($offset = null)
    {
        try {
            if (!$this->botToken) {
                return [
                    'success' => false,
                    'error' => 'Bot token not configured'
                ];
            }

            $params = [];
            if ($offset !== null) {
                $params['offset'] = $offset;
                $params['timeout'] = 1; // Short timeout for polling
            }

            $response = Http::get("{$this->baseUrl}/getUpdates", $params);
            
            if (!$response->successful()) {
                return [
                    'success' => false,
                    'error' => 'Failed to get updates'
                ];
            }

            return [
                'success' => true,
                'updates' => $response->json()['result']
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
