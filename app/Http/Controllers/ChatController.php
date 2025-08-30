<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Services\ChatAIService;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string|max:1000',
                'session_id' => 'required|string',
                'name' => 'nullable|string|max:100',
                'email' => 'nullable|email|max:100'
            ]);

            $chatMessage = ChatMessage::create([
                'session_id' => $request->session_id,
                'name' => $request->name,
                'email' => $request->email,
                'message' => $request->message,
                'sender_type' => 'visitor'
            ]);

            // Send email notification to admin (non-blocking)
            if (config('chat.email_notifications')) {
                try {
                    $this->sendEmailNotification($chatMessage);
                } catch (\Exception $e) {
                    Log::error('Email notification failed: ' . $e->getMessage());
                }
            }

            // Send auto-response or AI response (if enabled)
            if (config('chat.ai_enabled')) {
                try {
                    $this->sendAIResponse($request->session_id, $request->message);
                } catch (\Exception $e) {
                    Log::error('AI response failed: ' . $e->getMessage());
                    // Fallback to traditional auto-response
                    if (config('chat.auto_response_enabled')) {
                        $this->sendAutoResponse($request->session_id, $request->message);
                    }
                }
            } elseif (config('chat.auto_response_enabled')) {
                try {
                    $this->sendAutoResponse($request->session_id, $request->message);
                } catch (\Exception $e) {
                    Log::error('Auto-response failed: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => $chatMessage,
                'timestamp' => $chatMessage->created_at->format('H:i')
            ]);
        } catch (\Exception $e) {
            Log::error('Chat message error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to send message. Please try again.'
            ], 500);
        }
    }

    public function getMessages(Request $request)
    {
        try {
            $request->validate([
                'session_id' => 'required|string'
            ]);

            $messages = ChatMessage::bySession($request->session_id)
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
        } catch (\Exception $e) {
            Log::error('Get messages error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to load messages.'
            ], 500);
        }
    }

    public function sendAdminReply(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string|max:1000',
                'session_id' => 'required|string'
            ]);

            $chatMessage = ChatMessage::create([
                'session_id' => $request->session_id,
                'message' => $request->message,
                'sender_type' => 'admin'
            ]);

            return response()->json([
                'success' => true,
                'message' => $chatMessage,
                'timestamp' => $chatMessage->created_at->format('H:i')
            ]);
        } catch (\Exception $e) {
            Log::error('Admin reply error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to send reply.'
            ], 500);
        }
    }

    private function sendAIResponse($sessionId, $message)
    {
        try {
            if (!config('chat.ai_enabled')) {
                return;
            }

            $aiService = new ChatAIService();
            
            // Generate AI response
            $aiResponse = $aiService->generateResponse($message, $sessionId);
            
            if (!empty($aiResponse)) {
                // In a real application, you might want to queue this with a delay
                // For now, we'll add the response immediately
                ChatMessage::create([
                    'session_id' => $sessionId,
                    'message' => $aiResponse,
                    'sender_type' => 'admin'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('AI response error: ' . $e->getMessage());
            
            // Fallback to keyword-based responses if AI fails and fallback is enabled
            if (config('chat.ai_fallback_to_keywords')) {
                $this->sendAutoResponse($sessionId, $message);
            }
        }
    }

    private function sendAutoResponse($sessionId, $message)
    {
        try {
            if (!config('chat.auto_response_enabled')) {
                return;
            }

            // Check if this is the first message from this session
            $messageCount = ChatMessage::bySession($sessionId)->fromVisitor()->count();
            
            if ($messageCount === 1) {
                // Send welcome auto-response
                ChatMessage::create([
                    'session_id' => $sessionId,
                    'message' => config('chat.welcome_message'),
                    'sender_type' => 'admin'
                ]);
            } else {
                // Send contextual auto-responses based on keywords
                $lowerMessage = strtolower($message);
                $keywordResponses = config('chat.keyword_responses');
                
                foreach ($keywordResponses as $keywords => $response) {
                    $keywordList = explode('|', $keywords);
                    
                    foreach ($keywordList as $keyword) {
                        if (strpos($lowerMessage, trim($keyword)) !== false) {
                            ChatMessage::create([
                                'session_id' => $sessionId,
                                'message' => $response,
                                'sender_type' => 'admin'
                            ]);
                            
                            // Only send one auto-response per message
                            return;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Auto response error: ' . $e->getMessage());
        }
    }

    private function sendEmailNotification($chatMessage)
    {
        try {
            if (!config('chat.email_notifications')) {
                return;
            }

            // Get admin users
            $admins = User::where('is_admin', true)->get();
            
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new \App\Mail\ChatNotificationMail($chatMessage));
            }
        } catch (\Exception $e) {
            Log::error('Email notification error: ' . $e->getMessage());
            // Don't fail the chat message if email fails
        }
    }
}
