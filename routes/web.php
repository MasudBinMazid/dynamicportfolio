<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProjectController;
use App\Http\Controllers\Admin\AdminContactInfoController;
use App\Http\Controllers\Admin\AdminMessageController;
use App\Http\Controllers\Admin\AdminChatController;

// Public pages
Route::get('/', [PagesController::class, 'home'])->name('home');
Route::get('/about', [PagesController::class, 'about'])->name('about');
Route::get('/skills', [PagesController::class, 'skills'])->name('skills');
Route::get('/projects', [PagesController::class, 'projects'])->name('projects');
Route::get('/projects/{slug}', [PagesController::class, 'projectShow'])->name('projects.show');
Route::get('/contact', [PagesController::class, 'contact'])->name('contact');
Route::post('/contact', [PagesController::class, 'sendContact'])->name('contact.send');

// Chat routes
Route::post('/chat/send-message', [ChatController::class, 'sendMessage'])->name('chat.send');
Route::get('/chat/get-messages', [ChatController::class, 'getMessages'])->name('chat.get');

// Debug route for testing
Route::get('/debug/chat', function() {
    try {
        // Test database connection
        $connection = \DB::connection()->getPdo();
        echo "✅ Database connection successful<br>";
        
        // Test table exists
        $tableExists = \Schema::hasTable('chat_messages');
        echo $tableExists ? "✅ chat_messages table exists<br>" : "❌ chat_messages table not found<br>";
        
        // Test model
        $count = \App\Models\ChatMessage::count();
        echo "✅ ChatMessage model working, {$count} messages in database<br>";
        
        // Test config
        echo "✅ Auto-response enabled: " . (config('chat.auto_response_enabled') ? 'Yes' : 'No') . "<br>";
        echo "✅ Email notifications enabled: " . (config('chat.email_notifications') ? 'Yes' : 'No') . "<br>";
        echo "🤖 AI integration enabled: " . (config('chat.ai_enabled') ? 'Yes' : 'No') . "<br>";
        
        if (config('chat.ai_enabled')) {
            echo "🤖 AI Model: " . config('chat.ai_model') . "<br>";
            echo "🔑 OpenAI API Key configured: " . (config('services.openai.api_key') ? 'Yes' : 'No') . "<br>";
        }
        
        return "Chat system debug complete!";
    } catch (\Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
})->name('debug.chat');

// Debug route for testing Telegram
Route::get('/debug/telegram', function() {
    try {
        $telegramService = new \App\Services\TelegramService();
        
        echo "<h2>🤖 Telegram Debug</h2>";
        echo "Bot Token: " . (config('telegram.bot_token') ? '✅ Configured' : '❌ Not Set') . "<br>";
        echo "Chat ID: " . (config('telegram.chat_id') ? '✅ ' . config('telegram.chat_id') : '❌ Not Set') . "<br><br>";
        
        // Test bot connection
        $testResult = $telegramService->testBot();
        if ($testResult['success']) {
            echo "✅ Bot connection successful<br>";
            if (isset($testResult['bot']['first_name'])) {
                echo "Bot Name: " . $testResult['bot']['first_name'] . "<br>";
            }
        } else {
            echo "❌ Bot connection failed: " . $testResult['error'] . "<br>";
        }
        
        // Get recent updates
        echo "<br><h3>Recent Updates:</h3>";
        $updatesResult = $telegramService->getUpdates();
        if ($updatesResult['success']) {
            $updates = $updatesResult['updates'];
            echo "Found " . count($updates) . " updates<br>";
            
            foreach (array_slice($updates, -3) as $update) {
                echo "<pre>" . json_encode($update, JSON_PRETTY_PRINT) . "</pre><hr>";
            }
        } else {
            echo "❌ Failed to get updates: " . $updatesResult['error'] . "<br>";
        }
        
        // Check cache
        echo "<br><h3>Cache Status:</h3>";
        $cacheKey = 'telegram_reply_session_' . config('telegram.chat_id');
        $cachedSession = cache()->get($cacheKey);
        echo "Active reply session: " . ($cachedSession ? $cachedSession : 'None') . "<br>";
        
        return "";
    } catch (\Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
})->name('debug.telegram');

// Admin auth (login at /admin)
Route::get('/admin', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Admin area
Route::middleware(['is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('/projects', AdminProjectController::class)->except(['show']);
    Route::get('/contact-info', [AdminContactInfoController::class, 'edit'])->name('contact_info.edit');
    Route::post('/contact-info', [AdminContactInfoController::class, 'update'])->name('contact_info.update');

    Route::get('/messages', [AdminMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{message}', [AdminMessageController::class, 'show'])->name('messages.show');
    Route::delete('/messages/{message}', [AdminMessageController::class, 'destroy'])->name('messages.destroy');

    // Admin Chat routes
    Route::get('/chat', [AdminChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{sessionId}', [AdminChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{sessionId}/reply', [AdminChatController::class, 'sendReply'])->name('chat.reply');
    Route::get('/chat/{sessionId}/new-messages', [AdminChatController::class, 'getNewMessages'])->name('chat.new_messages');
    Route::delete('/chat/message/{message}', [AdminChatController::class, 'deleteMessage'])->name('chat.delete_message');
    Route::delete('/chat/session/{sessionId}', [AdminChatController::class, 'deleteSession'])->name('chat.delete_session');
    
    // Telegram setup routes
    Route::get('/telegram/setup', [AdminChatController::class, 'telegramSetup'])->name('telegram.setup');
    Route::post('/telegram/get-chat-id', [AdminChatController::class, 'getChatId'])->name('telegram.get_chat_id');
});
