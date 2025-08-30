@extends('layouts.admin')

@section('title', 'Telegram Setup')

@section('content')
<div class="telegram-setup-container">
    <div class="setup-header">
        <h1>🤖 Telegram Bot Setup</h1>
        <p>Configure your Telegram bot to receive live chat notifications on your phone</p>
    </div>

    <div class="setup-steps">
        <!-- Step 1: Current Configuration -->
        <div class="step-card">
            <div class="step-number">📊</div>
            <div class="step-content">
                <h3>Current Configuration</h3>
                <div class="config-grid">
                    <div class="config-item">
                        <span class="config-label">Bot Token:</span>
                        <span class="config-value {{ $currentConfig['bot_token'] ? 'configured' : 'not-configured' }}">
                            {{ $currentConfig['bot_token'] ? '✅ Configured' : '❌ Not Set' }}
                        </span>
                    </div>
                    <div class="config-item">
                        <span class="config-label">Chat ID:</span>
                        <span class="config-value {{ $currentConfig['chat_id'] ? 'configured' : 'not-configured' }}">
                            {{ $currentConfig['chat_id'] ? '✅ ' . $currentConfig['chat_id'] : '❌ Not Set' }}
                        </span>
                    </div>
                    <div class="config-item">
                        <span class="config-label">Notifications:</span>
                        <span class="config-value {{ $currentConfig['notifications_enabled'] ? 'configured' : 'not-configured' }}">
                            {{ $currentConfig['notifications_enabled'] ? '✅ Enabled' : '❌ Disabled' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: Create Bot -->
        <div class="step-card">
            <div class="step-number">1</div>
            <div class="step-content">
                <h3>Create Your Telegram Bot</h3>
                <ol class="step-list">
                    <li>Open Telegram and search for <strong>@BotFather</strong></li>
                    <li>Start a chat and send <code>/newbot</code></li>
                    <li>Choose a name for your bot (e.g., "Portfolio Chat Bot")</li>
                    <li>Choose a username ending with "bot" (e.g., "YourNamePortfolioBot")</li>
                    <li>Copy the bot token that BotFather gives you</li>
                </ol>
                <div class="bot-token-input">
                    <label for="bot_token">Bot Token:</label>
                    <input type="text" id="bot_token" placeholder="Paste your bot token here..." value="{{ $currentConfig['bot_token'] }}">
                </div>
            </div>
        </div>

        <!-- Step 3: Get Chat ID -->
        <div class="step-card">
            <div class="step-number">2</div>
            <div class="step-content">
                <h3>Get Your Chat ID</h3>
                <p>After creating your bot, you need to get your chat ID:</p>
                <ol class="step-list">
                    <li>Find your bot on Telegram and start a chat</li>
                    <li>Send any message to your bot (like "Hello")</li>
                    <li>Click the button below to get your Chat ID</li>
                </ol>
                
                <button id="getChatIdBtn" class="btn-primary" onclick="getChatId()">
                    🔍 Get My Chat ID
                </button>
                
                <div id="chatIdResult" class="chat-id-result" style="display: none;"></div>
            </div>
        </div>

        <!-- Step 4: Update .env -->
        <div class="step-card">
            <div class="step-number">3</div>
            <div class="step-content">
                <h3>Update Your .env File</h3>
                <p>Add these lines to your <code>.env</code> file:</p>
                <div class="env-code" id="envCode">
                    <pre>TELEGRAM_BOT_TOKEN={{ $currentConfig['bot_token'] ?: 'your_bot_token_here' }}
TELEGRAM_CHAT_ID={{ $currentConfig['chat_id'] ?: 'your_chat_id_here' }}
TELEGRAM_NOTIFY_NEW_MESSAGES=true</pre>
                </div>
                <button onclick="copyEnvCode()" class="btn-copy">📋 Copy to Clipboard</button>
            </div>
        </div>

        <!-- Step 5: Test -->
        <div class="step-card">
            <div class="step-number">4</div>
            <div class="step-content">
                <h3>Test Your Setup</h3>
                <p>Once you've updated your .env file, test the connection:</p>
                <div class="test-buttons">
                    <a href="{{ url('/api/telegram/test-bot') }}" class="btn-test" target="_blank">
                        🤖 Test Bot Connection
                    </a>
                    <button onclick="testNotification()" class="btn-test">
                        📱 Send Test Notification
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="setup-footer">
        <div class="help-section">
            <h4>Need Help? 🤔</h4>
            <ul>
                <li><strong>Bot not responding?</strong> Make sure you've sent at least one message to it</li>
                <li><strong>Can't find your bot?</strong> Check the username you gave to @BotFather</li>
                <li><strong>Token not working?</strong> Copy it exactly from @BotFather, including the numbers and colons</li>
            </ul>
        </div>
    </div>
</div>

<style>
.telegram-setup-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.setup-header {
    text-align: center;
    margin-bottom: 40px;
}

.setup-header h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
    color: var(--primary);
}

.setup-steps {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.step-card {
    background: var(--card);
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    display: flex;
    gap: 20px;
    align-items: flex-start;
}

.step-number {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    font-weight: bold;
    flex-shrink: 0;
}

.step-content {
    flex: 1;
}

.step-content h3 {
    margin: 0 0 16px 0;
    color: var(--text);
    font-size: 1.4rem;
}

.step-list {
    margin: 16px 0;
    padding-left: 20px;
    color: var(--text-light);
    line-height: 1.6;
}

.step-list li {
    margin-bottom: 8px;
}

.config-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-top: 16px;
}

.config-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    background: rgba(0, 0, 0, 0.02);
    border-radius: 8px;
    border: 1px solid var(--border);
}

.config-label {
    font-weight: 500;
    color: var(--text);
}

.config-value.configured {
    color: var(--success);
    font-weight: 600;
}

.config-value.not-configured {
    color: var(--danger);
    font-weight: 600;
}

.bot-token-input {
    margin-top: 16px;
}

.bot-token-input label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: var(--text);
}

.bot-token-input input {
    width: 100%;
    padding: 12px;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-family: monospace;
    font-size: 14px;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    margin-top: 16px;
    transition: transform 0.2s;
}

.btn-primary:hover {
    transform: translateY(-2px);
}

.btn-test {
    background: rgba(34, 197, 94, 0.1);
    color: var(--success);
    padding: 10px 20px;
    border: 1px solid rgba(34, 197, 94, 0.3);
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    display: inline-block;
    margin: 8px 8px 8px 0;
    transition: all 0.2s;
}

.btn-test:hover {
    background: rgba(34, 197, 94, 0.2);
}

.btn-copy {
    background: rgba(59, 130, 246, 0.1);
    color: var(--info);
    padding: 8px 16px;
    border: 1px solid rgba(59, 130, 246, 0.3);
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    margin-top: 12px;
}

.chat-id-result {
    margin-top: 20px;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid var(--border);
}

.env-code {
    background: #1a1a1a;
    color: #e1e5e9;
    padding: 16px;
    border-radius: 8px;
    margin: 16px 0;
    position: relative;
}

.env-code pre {
    margin: 0;
    font-family: 'Courier New', monospace;
    font-size: 14px;
    line-height: 1.4;
}

.setup-footer {
    margin-top: 40px;
    padding: 24px;
    background: rgba(59, 130, 246, 0.05);
    border-radius: 12px;
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.help-section h4 {
    margin: 0 0 16px 0;
    color: var(--info);
}

.help-section ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.help-section li {
    margin-bottom: 12px;
    padding-left: 20px;
    position: relative;
    color: var(--text-light);
    line-height: 1.5;
}

.help-section li:before {
    content: "💡";
    position: absolute;
    left: 0;
    top: 0;
}

@media (max-width: 768px) {
    .step-card {
        flex-direction: column;
        text-align: center;
    }
    
    .config-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
async function getChatId() {
    const botToken = document.getElementById('bot_token').value;
    const btn = document.getElementById('getChatIdBtn');
    const result = document.getElementById('chatIdResult');
    
    if (!botToken) {
        alert('Please enter your bot token first!');
        return;
    }
    
    btn.disabled = true;
    btn.textContent = '🔄 Getting Chat ID...';
    
    try {
        const response = await fetch('{{ route("admin.telegram.get_chat_id") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ bot_token: botToken })
        });
        
        const data = await response.json();
        
        if (data.success) {
            let html = '<div class="success-message">✅ <strong>Found Chat IDs:</strong></div>';
            data.chat_ids.forEach(chat => {
                html += `<div class="chat-id-item">
                    <strong>Chat ID:</strong> <code>${chat.id}</code><br>
                    <strong>Name:</strong> ${chat.name}
                    ${chat.username ? `<br><strong>Username:</strong> @${chat.username}` : ''}
                </div>`;
            });
            
            if (data.chat_ids.length === 1) {
                // Update the env code with the found chat ID
                updateEnvCode(botToken, data.chat_ids[0].id);
                html += `<div class="auto-update-notice">💡 The .env code below has been updated with your Chat ID!</div>`;
            }
            
            result.innerHTML = html;
            result.style.display = 'block';
            result.className = 'chat-id-result success';
        } else {
            result.innerHTML = `
                <div class="error-message">❌ <strong>Error:</strong> ${data.error}</div>
                ${data.instructions ? `
                    <div class="instructions">
                        <strong>Please:</strong>
                        <ul>
                            ${data.instructions.map(instruction => `<li>${instruction}</li>`).join('')}
                        </ul>
                    </div>
                ` : ''}
            `;
            result.style.display = 'block';
            result.className = 'chat-id-result error';
        }
    } catch (error) {
        result.innerHTML = `<div class="error-message">❌ <strong>Error:</strong> ${error.message}</div>`;
        result.style.display = 'block';
        result.className = 'chat-id-result error';
    }
    
    btn.disabled = false;
    btn.textContent = '🔍 Get My Chat ID';
}

function updateEnvCode(botToken, chatId) {
    const envCode = document.getElementById('envCode');
    envCode.innerHTML = `<pre>TELEGRAM_BOT_TOKEN=${botToken}
TELEGRAM_CHAT_ID=${chatId}
TELEGRAM_NOTIFY_NEW_MESSAGES=true</pre>`;
}

function copyEnvCode() {
    const envCode = document.getElementById('envCode').textContent;
    navigator.clipboard.writeText(envCode).then(() => {
        alert('✅ Copied to clipboard! Paste this in your .env file.');
    });
}

async function testNotification() {
    // This would require implementing a test endpoint
    alert('💡 After updating your .env file, use the "Test Bot Connection" button to verify everything is working!');
}

// Auto-update env code when bot token changes
document.getElementById('bot_token').addEventListener('input', function() {
    const botToken = this.value;
    const chatId = '{{ $currentConfig["chat_id"] }}' || 'your_chat_id_here';
    if (botToken && chatId !== 'your_chat_id_here') {
        updateEnvCode(botToken, chatId);
    }
});
</script>
@endsection
