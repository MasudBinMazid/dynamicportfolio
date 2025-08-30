<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatMessage;
use App\Services\TelegramService;

class AdminChatController extends Controller
{
    public function index()
    {
        // Get unique chat sessions with latest message
        $chatSessions = ChatMessage::select('session_id')
            ->selectRaw('MAX(created_at) as latest_message_time')
            ->selectRaw('COUNT(*) as message_count')
            ->selectRaw('SUM(CASE WHEN sender_type = "visitor" AND is_read = 0 THEN 1 ELSE 0 END) as unread_count')
            ->selectRaw('MAX(CASE WHEN name IS NOT NULL THEN name ELSE "Anonymous" END) as visitor_name')
            ->selectRaw('MAX(CASE WHEN email IS NOT NULL THEN email ELSE "" END) as visitor_email')
            ->groupBy('session_id')
            ->orderBy('latest_message_time', 'desc')
            ->get();

        return view('admin.chat.index', compact('chatSessions'));
    }

    public function show($sessionId)
    {
        $messages = ChatMessage::bySession($sessionId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark visitor messages as read
        ChatMessage::bySession($sessionId)
            ->fromVisitor()
            ->unread()
            ->update(['is_read' => true]);

        $visitorInfo = ChatMessage::bySession($sessionId)
            ->whereNotNull('name')
            ->first();

        return view('admin.chat.show', compact('messages', 'sessionId', 'visitorInfo'));
    }

    public function sendReply(Request $request, $sessionId)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $chatMessage = ChatMessage::create([
            'session_id' => $sessionId,
            'message' => $request->message,
            'sender_type' => 'admin'
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $chatMessage->id,
                'message' => $chatMessage->message,
                'sender_type' => 'admin',
                'timestamp' => $chatMessage->created_at->format('H:i')
            ]
        ]);
    }

    public function getNewMessages(Request $request, $sessionId)
    {
        $lastMessageId = $request->get('last_message_id', 0);
        
        $messages = ChatMessage::bySession($sessionId)
            ->where('id', '>', $lastMessageId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender_type' => $message->sender_type,
                    'timestamp' => $message->created_at->format('H:i'),
                    'name' => $message->name
                ];
            });

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }

    public function deleteMessage(ChatMessage $message)
    {
        $sessionId = $message->session_id;
        $message->delete();
        
        return redirect()->route('admin.chat.show', $sessionId)
            ->with('success', 'Message deleted successfully.');
    }

    public function deleteSession($sessionId)
    {
        ChatMessage::bySession($sessionId)->delete();
        
        return redirect()->route('admin.chat.index')
            ->with('success', 'Chat session deleted successfully.');
    }

    public function telegramSetup()
    {
        $currentConfig = [
            'bot_token' => config('telegram.bot_token'),
            'chat_id' => config('telegram.chat_id'),
            'notifications_enabled' => config('telegram.notify_new_messages'),
            'webhook_enabled' => config('telegram.webhook_enabled'),
        ];

        return view('admin.telegram.setup', compact('currentConfig'));
    }

    public function getChatId(Request $request)
    {
        $request->validate([
            'bot_token' => 'required|string'
        ]);

        $botToken = $request->bot_token;
        
        // Temporarily set the bot token in config
        config(['telegram.bot_token' => $botToken]);
        
        try {
            $telegramService = new TelegramService();
            $result = $telegramService->getUpdates();
            
            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'error' => $result['error']
                ]);
            }

            $updates = $result['updates'];
            
            if (empty($updates)) {
                return response()->json([
                    'success' => false,
                    'error' => 'No messages found. Please send a message to your bot first.',
                    'instructions' => [
                        'Go to Telegram and find your bot',
                        'Send any message like "Hello"',
                        'Come back and try again'
                    ]
                ]);
            }
            
            $chatIds = [];
            foreach ($updates as $update) {
                if (isset($update['message'])) {
                    $chat = $update['message']['chat'];
                    $chatId = $chat['id'];
                    $chatTitle = trim(($chat['first_name'] ?? '') . ' ' . ($chat['last_name'] ?? ''));
                    $username = $chat['username'] ?? null;
                    
                    if (!in_array($chatId, array_column($chatIds, 'id'))) {
                        $chatIds[] = [
                            'id' => $chatId,
                            'name' => $chatTitle ?: 'No Name',
                            'username' => $username
                        ];
                    }
                }
            }
            
            return response()->json([
                'success' => true,
                'chat_ids' => $chatIds
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
