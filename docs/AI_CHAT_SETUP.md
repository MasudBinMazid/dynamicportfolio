# AI Chat Integration Documentation

## Overview
This document explains how to set up and use the AI-powered live chat feature in your Laravel portfolio application.

## Features
✅ **Smart AI Responses** - Uses OpenAI GPT to provide intelligent responses  
✅ **Contextual Understanding** - AI considers conversation history  
✅ **Fallback System** - Falls back to keyword-based responses if AI fails  
✅ **Professional Tone** - AI represents you professionally  
✅ **Admin Dashboard** - Monitor AI status and configuration  

## Setup Instructions

### 1. Get OpenAI API Key
1. Visit [OpenAI Platform](https://platform.openai.com/api-keys)
2. Create an account or log in
3. Generate a new API key
4. Copy the API key for the next step

### 2. Configure Environment Variables
Add these variables to your `.env` file:

```env
# Enable AI Chat
CHAT_AI_ENABLED=true

# OpenAI Configuration
OPENAI_API_KEY=your_openai_api_key_here

# Optional AI Settings
CHAT_AI_MODEL=gpt-3.5-turbo
CHAT_AI_MAX_TOKENS=150
CHAT_AI_FALLBACK=true
```

### 3. Install Dependencies
The required OpenAI PHP client is already installed via Composer:
```bash
composer require openai-php/client
```

## Configuration Options

### Chat Configuration (`config/chat.php`)

- `ai_enabled` - Enable/disable AI responses
- `ai_model` - OpenAI model to use (gpt-3.5-turbo, gpt-4, etc.)
- `ai_max_tokens` - Maximum response length
- `ai_fallback_to_keywords` - Use keyword responses if AI fails

### AI System Context
The AI is configured with information about:
- Your name and role
- Your skills and expertise
- Your availability for projects
- Professional guidelines

## Testing AI Responses

### Command Line Testing
Test AI responses using the custom Artisan command:
```bash
php artisan chat:test-ai "Hello, what are your skills?"
```

### Debug Route
Visit `/debug/chat` to check AI configuration status.

### Admin Dashboard
Check the AI status section in your admin dashboard at `/admin/dashboard`.

## How It Works

### 1. Message Flow
1. User sends a message
2. System checks if AI is enabled
3. If AI enabled: generates intelligent response using conversation context
4. If AI disabled/fails: uses keyword-based auto-responses
5. Response is stored and displayed to user

### 2. AI Context Building
- System prompt defines your professional persona
- Recent conversation history provides context
- User message is processed with full context
- AI generates relevant, professional response

### 3. Fallback System
- If AI service is unavailable, falls back to keyword responses
- If API key is invalid, displays appropriate error
- Ensures chat remains functional even if AI fails

## Customization

### Modify AI Personality
Edit the system prompt in `app/Services/ChatAIService.php`:
```php
private function buildSystemPrompt(): string
{
    return "You are [Your Name]'s AI assistant...";
}
```

### Add New Fallback Responses
Update fallback responses in `ChatAIService.php`:
```php
private function getFallbackResponse(string $message): string
{
    // Add your custom keyword responses
}
```

## Cost Management

### Token Usage
- Each conversation uses tokens based on:
  - System prompt length
  - Conversation history
  - User message
  - Generated response

### Cost Optimization Tips
1. Set reasonable `ai_max_tokens` limit
2. Monitor usage in OpenAI dashboard
3. Consider using GPT-3.5-turbo for cost efficiency
4. Implement usage tracking if needed

## Monitoring & Analytics

### Admin Dashboard
- View AI status (enabled/disabled)
- Check API key configuration
- Monitor system health

### Debug Information
- Check `/debug/chat` for system status
- View logs for AI response errors
- Monitor API usage in OpenAI dashboard

## Security Considerations

### API Key Protection
- Store API key in environment variables
- Never commit API keys to version control
- Use different keys for development/production

### Rate Limiting
- OpenAI has rate limits on API usage
- Consider implementing request throttling for high-traffic sites

### Content Filtering
- AI responses are filtered for professionalism
- Fallback responses provide safe alternatives

## Troubleshooting

### Common Issues

**AI Not Responding:**
- Check `CHAT_AI_ENABLED=true` in .env
- Verify `OPENAI_API_KEY` is set correctly
- Check API key validity in OpenAI dashboard

**API Errors:**
- Check OpenAI account has sufficient credits
- Verify API key permissions
- Review error logs in Laravel

**Fallback Not Working:**
- Ensure `CHAT_AI_FALLBACK=true` in config
- Check keyword response configuration

### Error Messages
- "AI service unavailable" - API connection issues
- "API key not configured" - Missing/invalid API key
- "Rate limit exceeded" - Too many requests

## Support

For additional help:
1. Check Laravel logs for detailed error messages
2. Review OpenAI documentation for API issues
3. Test with the provided Artisan command
4. Monitor the admin dashboard for system status

---
*Last updated: August 30, 2025*
