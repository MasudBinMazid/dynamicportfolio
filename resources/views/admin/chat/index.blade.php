@extends('layouts.admin')

@section('title', 'Live Chat Management')

@section('content')
<div class="admin-header">
    <h1>Live Chat Management</h1>
    <p>Manage and respond to visitor messages</p>
</div>

<div class="admin-content">
    @if($chatSessions->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">💬</div>
            <h3>No Chat Messages Yet</h3>
            <p>When visitors start chatting, their conversations will appear here.</p>
        </div>
    @else
        <div class="chat-sessions">
            @foreach($chatSessions as $session)
                <div class="chat-session-card">
                    <div class="session-info">
                        <div class="session-header">
                            <h3 class="visitor-name">
                                {{ $session->visitor_name ?? 'Anonymous Visitor' }}
                                @if($session->unread_count > 0)
                                    <span class="unread-badge">{{ $session->unread_count }}</span>
                                @endif
                            </h3>
                            <div class="session-time">
                                {{ \Carbon\Carbon::parse($session->latest_message_time)->diffForHumans() }}
                            </div>
                        </div>
                        
                        <div class="session-details">
                            @if($session->visitor_email)
                                <div class="email">📧 {{ $session->visitor_email }}</div>
                            @endif
                            <div class="message-count">💬 {{ $session->message_count }} messages</div>
                        </div>
                    </div>
                    
                    <div class="session-actions">
                        <a href="{{ route('admin.chat.show', $session->session_id) }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            View Chat
                        </a>
                        
                        <form action="{{ route('admin.chat.delete_session', $session->session_id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this entire chat session? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 20px;
}

.empty-state h3 {
    color: #495057;
    margin-bottom: 10px;
}

.chat-sessions {
    display: grid;
    gap: 20px;
}

.chat-session-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.chat-session-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.session-info {
    flex: 1;
}

.session-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.visitor-name {
    margin: 0;
    color: #333;
    font-size: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.unread-badge {
    background: #dc3545;
    color: white;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 600;
}

.session-time {
    color: #6c757d;
    font-size: 14px;
}

.session-details {
    display: flex;
    gap: 20px;
    font-size: 14px;
    color: #6c757d;
}

.session-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

.session-actions .btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 14px;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.btn-danger:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

@media (max-width: 768px) {
    .chat-session-card {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .session-details {
        flex-direction: column;
        gap: 5px;
    }

    .session-actions {
        width: 100%;
        justify-content: flex-start;
    }
    
    .session-actions .btn {
        flex: 1;
        justify-content: center;
        min-width: auto;
    }
}
</style>
@endsection
