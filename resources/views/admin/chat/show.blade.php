@extends('layouts.admin')

@section('title', 'Chat Conversation')

@section('content')
<div class="admin-header">
    <div class="header-left">
        <h1>Chat Conversation</h1>
        <p>
            with {{ $visitorInfo->name ?? 'Anonymous Visitor' }}
            @if($visitorInfo?->email)
                ({{ $visitorInfo->email }})
            @endif
        </p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.chat.index') }}" class="btn btn-secondary">← Back to Chats</a>
    </div>
</div>

<div class="admin-content">
    <div class="chat-interface">
        <div class="chat-messages-container" id="chat-messages-container">
            @foreach($messages as $message)
                <div class="chat-message {{ $message->sender_type }}" data-message-id="{{ $message->id }}">
                    <div class="message-bubble">
                        {{ $message->message }}
                    </div>
                    <div class="message-info">
                        <span class="message-sender">
                            @if($message->sender_type === 'admin')
                                You
                            @else
                                {{ $message->name ?? 'Visitor' }}
                            @endif
                        </span>
                        <span class="message-time">
                            {{ $message->created_at->format('M d, H:i') }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="chat-reply-form">
            <div class="reply-input-container">
                <textarea 
                    id="reply-message" 
                    placeholder="Type your reply..." 
                    maxlength="1000"
                    rows="3"
                ></textarea>
                <button id="send-reply-btn" onclick="sendReply()">
                    <span class="btn-text">Send Reply</span>
                    <span class="btn-loading" style="display: none;">Sending...</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
class AdminChat {
    constructor(sessionId) {
        this.sessionId = sessionId;
        this.lastMessageId = this.getLastMessageId();
        this.pollingInterval = null;
        
        this.startPolling();
        this.scrollToBottom();
    }
    
    getLastMessageId() {
        const messages = document.querySelectorAll('[data-message-id]');
        if (messages.length === 0) return 0;
        
        return Math.max(...Array.from(messages).map(el => 
            parseInt(el.dataset.messageId)
        ));
    }
    
    startPolling() {
        this.pollingInterval = setInterval(() => {
            this.checkForNewMessages();
        }, 2000);
    }
    
    async checkForNewMessages() {
        try {
            const response = await fetch(`/admin/chat/${this.sessionId}/new-messages?last_message_id=${this.lastMessageId}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            
            if (data.success && data.messages.length > 0) {
                data.messages.forEach(message => {
                    this.addMessage(message);
                    this.lastMessageId = Math.max(this.lastMessageId, message.id);
                });
                this.scrollToBottom();
            }
        } catch (error) {
            console.error('Error checking for new messages:', error);
        }
    }
    
    addMessage(message) {
        const container = document.getElementById('chat-messages-container');
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `chat-message ${message.sender_type}`;
        messageDiv.dataset.messageId = message.id;
        
        const bubbleDiv = document.createElement('div');
        bubbleDiv.className = 'message-bubble';
        bubbleDiv.textContent = message.message;
        
        const infoDiv = document.createElement('div');
        infoDiv.className = 'message-info';
        
        const senderSpan = document.createElement('span');
        senderSpan.className = 'message-sender';
        senderSpan.textContent = message.sender_type === 'admin' ? 'You' : (message.name || 'Visitor');
        
        const timeSpan = document.createElement('span');
        timeSpan.className = 'message-time';
        timeSpan.textContent = message.timestamp;
        
        infoDiv.appendChild(senderSpan);
        infoDiv.appendChild(timeSpan);
        
        messageDiv.appendChild(bubbleDiv);
        messageDiv.appendChild(infoDiv);
        
        container.appendChild(messageDiv);
    }
    
    scrollToBottom() {
        const container = document.getElementById('chat-messages-container');
        container.scrollTop = container.scrollHeight;
    }
    
    async sendReply() {
        const textarea = document.getElementById('reply-message');
        const sendBtn = document.getElementById('send-reply-btn');
        const btnText = sendBtn.querySelector('.btn-text');
        const btnLoading = sendBtn.querySelector('.btn-loading');
        
        const message = textarea.value.trim();
        if (!message) return;
        
        // Disable button and show loading
        sendBtn.disabled = true;
        btnText.style.display = 'none';
        btnLoading.style.display = 'inline';
        
        try {
            const response = await fetch(`/admin/chat/${this.sessionId}/reply`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ message })
            });
            
            const data = await response.json();
            
            if (data.success) {
                textarea.value = '';
                this.addMessage(data.message);
                this.lastMessageId = Math.max(this.lastMessageId, data.message.id);
                this.scrollToBottom();
            } else {
                alert('Failed to send reply. Please try again.');
            }
        } catch (error) {
            console.error('Error sending reply:', error);
            alert('Connection error. Please try again.');
        } finally {
            sendBtn.disabled = false;
            btnText.style.display = 'inline';
            btnLoading.style.display = 'none';
        }
    }
}

// Initialize admin chat
const adminChat = new AdminChat('{{ $sessionId }}');

// Global function for HTML onclick
function sendReply() {
    adminChat.sendReply();
}

// Handle Enter key (Shift+Enter for new line)
document.getElementById('reply-message').addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendReply();
    }
});
</script>

<style>
.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
}

.header-left h1 {
    margin: 0 0 5px 0;
}

.header-left p {
    margin: 0;
    color: #6c757d;
}

.header-actions .btn {
    background: #6c757d;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
}

.chat-interface {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    height: 70vh;
    display: flex;
    flex-direction: column;
}

.chat-messages-container {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    background: #f8f9fa;
}

.chat-message {
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
}

.chat-message.visitor {
    align-items: flex-start;
}

.chat-message.admin {
    align-items: flex-end;
}

.message-bubble {
    max-width: 70%;
    padding: 15px 20px;
    border-radius: 20px;
    font-size: 14px;
    line-height: 1.5;
    word-wrap: break-word;
}

.chat-message.visitor .message-bubble {
    background: white;
    border: 1px solid #e9ecef;
    border-bottom-left-radius: 5px;
}

.chat-message.admin .message-bubble {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom-right-radius: 5px;
}

.message-info {
    margin-top: 5px;
    font-size: 12px;
    color: #6c757d;
    display: flex;
    gap: 10px;
}

.chat-message.admin .message-info {
    flex-direction: row-reverse;
}

.chat-reply-form {
    border-top: 1px solid #e9ecef;
    padding: 20px;
    background: white;
}

.reply-input-container {
    display: flex;
    gap: 15px;
    align-items: flex-end;
}

#reply-message {
    flex: 1;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 12px 16px;
    font-size: 14px;
    font-family: inherit;
    outline: none;
    resize: none;
    transition: border-color 0.3s ease;
}

#reply-message:focus {
    border-color: #667eea;
}

#send-reply-btn {
    padding: 12px 24px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s ease;
    white-space: nowrap;
}

#send-reply-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

#send-reply-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

@media (max-width: 768px) {
    .admin-header {
        flex-direction: column;
        gap: 15px;
    }
    
    .chat-interface {
        height: 60vh;
    }
    
    .message-bubble {
        max-width: 85%;
    }
    
    .reply-input-container {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>
@endsection
