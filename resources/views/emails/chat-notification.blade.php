<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Chat Message</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 12px 12px 0 0;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            background: white;
            padding: 30px;
            border: 1px solid #e9ecef;
            border-top: none;
            border-radius: 0 0 12px 12px;
        }
        .message-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .message-info strong {
            color: #495057;
        }
        .message-content {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            margin: 20px 0;
            text-align: center;
        }
        .button:hover {
            opacity: 0.9;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 14px;
        }
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .header, .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>💬 New Chat Message</h1>
        <p>You have received a new message on your portfolio website</p>
    </div>
    
    <div class="content">
        <div class="message-info">
            <p><strong>From:</strong> {{ $chatMessage->name ?: 'Anonymous Visitor' }}</p>
            @if($chatMessage->email)
                <p><strong>Email:</strong> {{ $chatMessage->email }}</p>
            @endif
            <p><strong>Session ID:</strong> {{ $chatMessage->session_id }}</p>
            <p><strong>Time:</strong> {{ $chatMessage->created_at->format('M d, Y \a\t H:i') }}</p>
        </div>
        
        <h3>Message:</h3>
        <div class="message-content">
            {{ $chatMessage->message }}
        </div>
        
        <div style="text-align: center;">
            <a href="{{ $chatUrl }}" class="button">
                Reply to Message
            </a>
        </div>
        
        <div class="footer">
            <p>This email was sent from your portfolio website's live chat system.</p>
            <p>You can manage all chat conversations from your admin dashboard.</p>
        </div>
    </div>
</body>
</html>
