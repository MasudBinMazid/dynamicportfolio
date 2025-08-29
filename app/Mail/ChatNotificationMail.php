<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\ChatMessage;

class ChatNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $chatMessage;
    public $chatUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ChatMessage $chatMessage)
    {
        $this->chatMessage = $chatMessage;
        $this->chatUrl = route('admin.chat.show', $chatMessage->session_id);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Chat Message - Portfolio Website')
                   ->view('emails.chat-notification');
    }
}
