# Telegram Integration Setup Guide

This guide will help you set up Telegram notifications for your portfolio's live chat system.

## Overview

When visitors send messages through your portfolio's live chat, you'll receive:
- 🔔 **Instant notifications** on your phone via Telegram
- 💬 **Quick reply functionality** - respond directly from Telegram
- 📱 **Mobile-friendly interface** with inline buttons

## Prerequisites

1. A Telegram account
2. Your Laravel portfolio application running
3. Access to your `.env` configuration file

## Step 1: Create a Telegram Bot

1. Open Telegram and search for **@BotFather**
2. Start a chat with BotFather
3. Send `/newbot` command
4. Follow the prompts:
   - Choose a name for your bot (e.g., "Portfolio Chat Bot")
   - Choose a username (must end with "bot", e.g., "YourNamePortfolioBot")
5. Copy the **bot token** that BotFather provides

## Step 2: Get Your Chat ID

### Option A: Using the Command (Recommended)

1. Start a chat with your newly created bot on Telegram
2. Send any message to your bot (like "Hello")
3. Run this command in your terminal:
   ```bash
   php artisan telegram:get-chat-id YOUR_BOT_TOKEN
   ```
4. Copy the Chat ID from the output

### Option B: Manual Method

1. Start a chat with your bot and send a message
2. Open this URL in your browser:
   ```
   https://api.telegram.org/botYOUR_BOT_TOKEN/getUpdates
   ```
3. Look for the "chat":{"id": number in the response
4. That number is your Chat ID

## Step 3: Configure Your Application

Add these lines to your `.env` file:

```env
# Telegram Bot Settings
TELEGRAM_BOT_TOKEN=your_bot_token_here
TELEGRAM_CHAT_ID=your_chat_id_here
TELEGRAM_NOTIFY_NEW_MESSAGES=true
TELEGRAM_WEBHOOK_ENABLED=false
TELEGRAM_WEBHOOK_URL=https://yourdomain.com/api/telegram/webhook
```

## Step 4: Test the Configuration

Run the setup command:
```bash
php artisan telegram:setup --test
```

If successful, you should receive a test message on Telegram!

## Step 5: Set Up Webhook (Optional but Recommended)

For real-time replies from Telegram to your chat visitors:

1. Make sure your application is accessible from the internet
2. Update `TELEGRAM_WEBHOOK_URL` in your `.env` with your public domain
3. Set `TELEGRAM_WEBHOOK_ENABLED=true`
4. Run the webhook setup:
   ```bash
   php artisan telegram:setup --webhook
   ```

## How It Works

### When a Visitor Sends a Message:
1. Message is saved to your database
2. You receive a Telegram notification with:
   - Visitor's name and email (if provided)
   - The message content
   - Session information
   - Quick action buttons

### To Reply from Telegram:
1. Click the "💬 Reply" button on the notification
2. Send your reply message
3. Your reply is automatically sent to the visitor
4. You'll get a confirmation message

### Available Commands:
- `/cancel` - Exit reply mode
- Any other message while in reply mode = reply to visitor

## Troubleshooting

### "Bot token is not configured"
- Check your `.env` file has the correct `TELEGRAM_BOT_TOKEN`
- Run `php artisan config:cache` after making changes

### "No messages found" when getting Chat ID
- Make sure you've sent at least one message to your bot
- Try sending another message and run the command again

### Webhook not working
- Ensure your domain is accessible via HTTPS
- Check Laravel logs for webhook errors: `tail -f storage/logs/laravel.log`

### Not receiving notifications
- Test the bot connection: `php artisan telegram:setup --test`
- Check if `TELEGRAM_NOTIFY_NEW_MESSAGES=true` in your `.env`
- Verify your Chat ID is correct

## Security Notes

1. **Keep your bot token secret** - don't share it publicly
2. **Use HTTPS** for webhook URLs in production
3. **Validate webhook requests** (automatically handled by the application)

## Commands Reference

```bash
# Setup and test the bot
php artisan telegram:setup

# Test bot connection only
php artisan telegram:setup --test

# Setup webhook only  
php artisan telegram:setup --webhook

# Show current configuration
php artisan telegram:setup --info

# Get chat ID for a bot token
php artisan telegram:get-chat-id YOUR_BOT_TOKEN
```

## API Endpoints

For advanced users or external integrations:

- `GET /api/telegram/test-bot` - Test bot connection
- `POST /api/telegram/setup-webhook` - Setup webhook programmatically
- `POST /api/telegram/webhook` - Webhook endpoint (used by Telegram)
- `GET /api/telegram/webhook-info` - Get webhook information

---

## That's It! 🎉

Your Telegram integration is now ready. Visitors can chat on your portfolio, and you'll receive instant notifications on Telegram with the ability to reply directly from your phone!

For support, check the admin dashboard for real-time status information.
