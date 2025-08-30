<div id="chat-widget">
    <button class="chat-toggle" onclick="toggleChat()" aria-label="Open chat">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
            <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
        </svg>
    </button>
    
    <div class="chat-container" id="chat-container">
        <div class="chat-header">
            <h3>Live Chat</h3>
            <p>Hi! How can I help you today?</p>
            <div class="online-indicator">
                <span class="online-dot"></span>
                <span id="chat-status-text">Online</span>
                @if(config('chat.ai_enabled'))
                    <span class="ai-badge">🤖</span>
                @endif
            </div>
            <button class="chat-close" onclick="toggleChat()" aria-label="Close chat">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
            </button>
        </div>
        
        <div class="chat-messages" id="chat-messages">
            <div class="chat-message admin">
                <div class="message-bubble">
                    Welcome! Feel free to ask me anything about my work or services. I'll get back to you as soon as possible.
                </div>
                <div class="message-time">Just now</div>
            </div>
        </div>
        
        <div class="chat-form">
            <div id="initial-form" style="display: block;">
                <div class="form-row">
                    <input type="text" class="chat-input half" id="chat-name" placeholder="Your name (optional)">
                    <input type="email" class="chat-input half" id="chat-email" placeholder="Email (optional)">
                </div>
                <div class="message-input-row">
                    <input type="text" class="chat-input message-input" id="chat-message" placeholder="Type your message..." maxlength="1000">
                    <button class="send-btn" onclick="sendMessage()">
                        <span class="btn-text">Send</span>
                    </button>
                </div>
            </div>
            <div class="chat-status" id="chat-status"></div>
        </div>
    </div>
</div>

<script>
class ChatWidget {
    constructor() {
        this.sessionId = this.getOrCreateSessionId();
        this.isOpen = false;
        this.lastMessageId = 0;
        this.pollingInterval = null;
        this.hasUserInfo = false;
        this.isTyping = false;
        
        // Initialize chat widget
        this.init();
    }

    init() {
        // Start polling for new messages
        this.startPolling();
        
        // Load existing messages when widget is first created
        if (localStorage.getItem('chat_has_messages') === 'true') {
            this.loadMessages();
        }
        
        // Add event listeners
        this.addEventListeners();
        
        // Check for unread messages
        this.checkForUnreadMessages();
    }

    addEventListeners() {
        const messageInput = document.getElementById('chat-message');
        if (messageInput) {
            messageInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.sendMessage();
                }
            });
            
            // Show typing indicator
            messageInput.addEventListener('input', () => {
                this.handleTyping();
            });
        }
        
        // Close chat when clicking outside
        document.addEventListener('click', (e) => {
            const chatWidget = document.getElementById('chat-widget');
            if (this.isOpen && !chatWidget.contains(e.target)) {
                // Don't close immediately, add a small delay for better UX
                setTimeout(() => {
                    if (this.isOpen && !chatWidget.matches(':hover')) {
                        this.toggleChat();
                    }
                }, 100);
            }
        });
    }

    handleTyping() {
        if (!this.isTyping) {
            this.isTyping = true;
            // You could send a typing indicator to the server here
            
            setTimeout(() => {
                this.isTyping = false;
            }, 1000);
        }
    }

    getOrCreateSessionId() {
        let sessionId = localStorage.getItem('chat_session_id');
        if (!sessionId) {
            sessionId = 'chat_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            localStorage.setItem('chat_session_id', sessionId);
        }
        return sessionId;
    }

    toggleChat() {
        const container = document.getElementById('chat-container');
        const toggle = document.querySelector('.chat-toggle');
        
        this.isOpen = !this.isOpen;
        
        if (this.isOpen) {
            container.classList.add('active');
            toggle.classList.add('active');
            toggle.classList.remove('has-unread');
            this.loadMessages();
            
            // Focus on message input
            setTimeout(() => {
                const messageInput = document.getElementById('chat-message');
                if (messageInput) messageInput.focus();
            }, 300);
        } else {
            container.classList.remove('active');
            toggle.classList.remove('active');
        }
    }

    async sendMessage() {
        const messageInput = document.getElementById('chat-message');
        const nameInput = document.getElementById('chat-name');
        const emailInput = document.getElementById('chat-email');
        const sendBtn = document.querySelector('.send-btn');
        
        const message = messageInput.value.trim();
        if (!message) return;

        // Show loading state
        sendBtn.classList.add('loading');
        sendBtn.disabled = true;
        const btnText = sendBtn.querySelector('.btn-text');
        btnText.textContent = 'Sending...';

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            
            if (!csrfToken) {
                throw new Error('CSRF token not found. Please refresh the page.');
            }

            const response = await fetch('/chat/send-message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message: message,
                    session_id: this.sessionId,
                    name: nameInput.value.trim() || null,
                    email: emailInput.value.trim() || null
                })
            });

            const data = await response.json();
            
            if (response.ok && data.success) {
                // Clear message input
                messageInput.value = '';
                
                // Add message to chat
                this.addMessage(message, 'visitor', data.timestamp, nameInput.value.trim());
                
                // Hide name/email inputs after first message
                if (!this.hasUserInfo && (nameInput.value.trim() || emailInput.value.trim())) {
                    this.hasUserInfo = true;
                    nameInput.style.display = 'none';
                    emailInput.style.display = 'none';
                    document.querySelector('.form-row').style.display = 'none';
                }
                
                // Update last message ID for polling
                this.lastMessageId = Math.max(this.lastMessageId, data.message.id);
                
                // Mark that this session has messages
                localStorage.setItem('chat_has_messages', 'true');
                
                this.showStatus('Message sent', 'success');
                
                // Show typing indicator from admin (simulation)
                this.showTypingIndicator();
                
                // Start checking for auto-response after a short delay
                setTimeout(() => {
                    this.checkForNewMessages();
                }, 3000);
                
            } else {
                const errorMessage = data.error || 'Failed to send message. Please try again.';
                this.showStatus(errorMessage, 'error');
            }
        } catch (error) {
            console.error('Error sending message:', error);
            
            let errorMessage = 'Connection error. Please try again.';
            
            if (error.message.includes('CSRF')) {
                errorMessage = 'Security token expired. Please refresh the page.';
            } else if (error.name === 'TypeError' && error.message.includes('fetch')) {
                errorMessage = 'Network error. Please check your connection.';
            }
            
            this.showStatus(errorMessage, 'error');
        } finally {
            sendBtn.classList.remove('loading');
            sendBtn.disabled = false;
            btnText.textContent = 'Send';
        }
    }

    showTypingIndicator() {
        // Show typing indicator for 2-5 seconds (simulate admin typing)
        const messagesContainer = document.getElementById('chat-messages');
        const typingDiv = document.createElement('div');
        typingDiv.className = 'typing-indicator';
        typingDiv.innerHTML = `
            <span class="typing-text">Admin is typing</span>
            <div class="typing-dots">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
        `;
        
        messagesContainer.appendChild(typingDiv);
        this.scrollToBottom();
        
        // Remove after random time (2-5 seconds)
        const timeout = Math.random() * 3000 + 2000;
        setTimeout(() => {
            if (typingDiv.parentNode) {
                typingDiv.remove();
            }
        }, timeout);
    }

    async loadMessages() {
        try {
            const response = await fetch(`/chat/get-messages?session_id=${this.sessionId}`);
            const data = await response.json();
            
            if (data.success) {
                const messagesContainer = document.getElementById('chat-messages');
                
                // Clear existing messages except welcome message
                const welcomeMessage = messagesContainer.querySelector('.chat-message.admin');
                messagesContainer.innerHTML = '';
                messagesContainer.appendChild(welcomeMessage);
                
                // Add all messages
                data.messages.forEach(msg => {
                    this.addMessage(msg.message, msg.sender_type, msg.timestamp, msg.name, false);
                    this.lastMessageId = Math.max(this.lastMessageId, msg.id);
                });
                
                this.scrollToBottom();
            }
        } catch (error) {
            console.error('Error loading messages:', error);
        }
    }

    startPolling() {
        // Poll for new messages every 3 seconds when chat is open
        this.pollingInterval = setInterval(() => {
            this.checkForNewMessages();
        }, 3000);
    }

    async checkForNewMessages() {
        try {
            const response = await fetch(`/chat/get-messages?session_id=${this.sessionId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                const newMessages = data.messages.filter(msg => msg.id > this.lastMessageId);
                newMessages.forEach(msg => {
                    this.addMessage(msg.message, msg.sender_type, msg.timestamp, msg.name);
                    this.lastMessageId = Math.max(this.lastMessageId, msg.id);
                    
                    // Show notification for admin messages
                    if (msg.sender_type === 'admin') {
                        this.showNotification();
                    }
                });
            }
        } catch (error) {
            console.error('Error checking for new messages:', error);
            // Don't show error to user for background polling, just log it
        }
    }

    async checkForUnreadMessages() {
        // Check if there are unread messages and show indicator
        if (!this.isOpen) {
            try {
                const response = await fetch(`/chat/get-messages?session_id=${this.sessionId}`);
                const data = await response.json();
                
                if (data.success && data.messages.length > 0) {
                    const lastStoredId = parseInt(localStorage.getItem('chat_last_read_id') || '0');
                    const hasUnread = data.messages.some(msg => msg.id > lastStoredId && msg.sender_type === 'admin');
                    
                    if (hasUnread) {
                        document.querySelector('.chat-toggle').classList.add('has-unread');
                    }
                }
            } catch (error) {
                console.error('Error checking for unread messages:', error);
            }
        }
    }

    addMessage(message, senderType, timestamp, name = null, scrollToBottom = true) {
        const messagesContainer = document.getElementById('chat-messages');
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `chat-message ${senderType}`;
        
        const bubbleDiv = document.createElement('div');
        bubbleDiv.className = 'message-bubble';
        bubbleDiv.textContent = message;
        
        const timeDiv = document.createElement('div');
        timeDiv.className = 'message-time';
        timeDiv.textContent = timestamp;
        
        messageDiv.appendChild(bubbleDiv);
        messageDiv.appendChild(timeDiv);
        messagesContainer.appendChild(messageDiv);
        
        if (scrollToBottom) {
            this.scrollToBottom();
        }
        
        // Store last read message ID
        if (this.isOpen) {
            localStorage.setItem('chat_last_read_id', this.lastMessageId.toString());
        }
    }

    scrollToBottom() {
        const messagesContainer = document.getElementById('chat-messages');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    showStatus(message, type = 'info') {
        const statusDiv = document.getElementById('chat-status');
        statusDiv.textContent = message;
        statusDiv.className = `chat-status ${type}`;
        
        setTimeout(() => {
            statusDiv.textContent = '';
            statusDiv.className = 'chat-status';
        }, 3000);
    }

    showNotification() {
        if (!this.isOpen && 'Notification' in window && Notification.permission === 'granted') {
            new Notification('New message from Masud', {
                body: 'You have a new message in the chat',
                icon: '/favicon.ico',
                tag: 'chat-notification'
            });
        }
        
        // Make chat button show unread indicator
        const toggle = document.querySelector('.chat-toggle');
        toggle.classList.add('has-unread');
    }
}

// Initialize chat widget
let chatWidget;
document.addEventListener('DOMContentLoaded', function() {
    chatWidget = new ChatWidget();
    
    // Request notification permission
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }
});

// Global functions for HTML onclick
function toggleChat() {
    if (chatWidget) {
        chatWidget.toggleChat();
    }
}

function sendMessage() {
    if (chatWidget) {
        chatWidget.sendMessage();
    }
}
</script>
