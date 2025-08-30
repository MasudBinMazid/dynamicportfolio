<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ContactMessage;
use App\Models\ChatMessage;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'projects' => Project::count(),
            'messages' => ContactMessage::count(),
            'featured' => Project::where('featured', true)->count(),
            'chat_sessions' => ChatMessage::distinct('session_id')->count('session_id'),
            'unread_chats' => ChatMessage::where('sender_type', 'visitor')->where('is_read', false)->count(),
            'ai_enabled' => config('chat.ai_enabled'),
            'ai_configured' => config('services.openai.api_key') ? true : false,
            'telegram_enabled' => config('telegram.notify_new_messages'),
            'telegram_configured' => config('telegram.bot_token') && config('telegram.chat_id') ? true : false,
        ];
        return view('admin.dashboard', compact('stats'));
    }
}
