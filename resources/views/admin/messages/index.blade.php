@extends('layouts.admin')
@section('title','Messages')

@push('styles')
<style>
  .messages-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
    flex-wrap: wrap;
    gap: 16px;
  }

  .messages-title {
    font-size: 28px;
    font-weight: 700;
    margin: 0;
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .messages-stats {
    display: flex;
    align-items: center;
    gap: 24px;
    font-size: 14px;
    color: var(--text-light);
  }

  .stat-item {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: var(--secondary);
    border-radius: 20px;
    font-weight: 500;
  }

  .stat-item.unread {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger);
  }

  .messages-grid {
    display: grid;
    gap: 20px;
    margin-bottom: 32px;
  }

  .message-card {
    background: var(--card);
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--shadow);
    transition: var(--transition);
    position: relative;
    border-left: 4px solid transparent;
    cursor: pointer;
    overflow: hidden;
  }

  .message-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    border-left-color: var(--primary);
  }

  .message-card.unread {
    border-left-color: var(--danger);
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.02) 0%, rgba(239, 68, 68, 0.05) 100%);
  }

  .message-card.unread::before {
    content: '';
    position: absolute;
    top: 16px;
    right: 16px;
    width: 8px;
    height: 8px;
    background: var(--danger);
    border-radius: 50%;
  }

  .message-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
    gap: 16px;
  }

  .message-sender {
    flex: 1;
  }

  .sender-name {
    font-size: 18px;
    font-weight: 600;
    color: var(--text);
    margin: 0 0 4px 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .sender-email {
    font-size: 14px;
    color: var(--text-light);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .message-date {
    font-size: 12px;
    color: var(--text-light);
    background: var(--secondary);
    padding: 4px 8px;
    border-radius: 12px;
    white-space: nowrap;
  }

  .message-subject {
    font-size: 16px;
    font-weight: 500;
    color: var(--text);
    margin: 0 0 12px 0;
    line-height: 1.4;
  }

  .message-subject.no-subject {
    color: var(--text-light);
    font-style: italic;
  }

  .message-preview {
    font-size: 14px;
    color: var(--text-light);
    line-height: 1.5;
    margin: 0 0 16px 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .message-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 16px;
    border-top: 1px solid var(--border);
  }

  .message-meta {
    display: flex;
    align-items: center;
    gap: 16px;
    font-size: 12px;
    color: var(--text-light);
  }

  .message-meta span {
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .action-buttons {
    display: flex;
    gap: 8px;
  }

  .action-btn {
    padding: 8px 16px;
    border: none;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .action-btn.view {
    background: rgba(59, 130, 246, 0.1);
    color: var(--info);
  }

  .action-btn.view:hover {
    background: var(--info);
    color: white;
    transform: translateY(-1px);
  }

  .action-btn.delete {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger);
  }

  .action-btn.delete:hover {
    background: var(--danger);
    color: white;
    transform: translateY(-1px);
  }

  .empty-state {
    text-align: center;
    padding: 64px 32px;
    color: var(--text-light);
  }

  .empty-state svg {
    width: 64px;
    height: 64px;
    margin-bottom: 16px;
    opacity: 0.5;
  }

  .empty-state h3 {
    font-size: 20px;
    margin: 0 0 8px 0;
    color: var(--text);
  }

  .empty-state p {
    margin: 0;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
  }

  .pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 32px;
  }

  /* Animation for message cards */
  .message-card {
    animation: slideInUp 0.5s ease forwards;
  }

  .message-card:nth-child(1) { animation-delay: 0.1s; }
  .message-card:nth-child(2) { animation-delay: 0.2s; }
  .message-card:nth-child(3) { animation-delay: 0.3s; }
  .message-card:nth-child(4) { animation-delay: 0.4s; }
  .message-card:nth-child(5) { animation-delay: 0.5s; }

  @keyframes slideInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Responsive design */
  @media (max-width: 768px) {
    .messages-header {
      flex-direction: column;
      align-items: stretch;
      text-align: center;
    }

    .messages-stats {
      justify-content: center;
      flex-wrap: wrap;
    }

    .message-header {
      flex-direction: column;
      gap: 8px;
    }

    .message-actions {
      flex-direction: column;
      gap: 12px;
      align-items: stretch;
    }

    .action-buttons {
      width: 100%;
      justify-content: space-between;
    }
  }

  /* Hover effect for clickable cards */
  .message-card-link {
    color: inherit;
    text-decoration: none;
    display: block;
  }

  .message-card-link:hover {
    color: inherit;
  }
</style>
@endpush

@section('content')
  <div class="messages-header">
    <div>
      <h1 class="messages-title">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
        Messages
      </h1>
      <div class="messages-stats">
        <div class="stat-item">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
          </svg>
          {{ $messages->total() }} Total
        </div>
        <div class="stat-item unread">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
            <line x1="9" y1="9" x2="9.01" y2="9"></line>
            <line x1="15" y1="9" x2="15.01" y2="9"></line>
          </svg>
          All Messages
        </div>
      </div>
    </div>
  </div>

  @if($messages->count() > 0)
    <div class="messages-grid">
      @foreach($messages as $m)
        <div class="message-card">
          <div class="message-header">
            <div class="message-sender">
              <h3 class="sender-name">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                  <circle cx="12" cy="7" r="4"></circle>
                </svg>
                {{ $m->name }}
              </h3>
              <p class="sender-email">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                  <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
                {{ $m->email }}
              </p>
            </div>
            <div class="message-date">
              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
              </svg>
              {{ $m->created_at->format('M j, Y') }}
            </div>
          </div>

          <h4 class="message-subject {{ !$m->subject ? 'no-subject' : '' }}">
            {{ $m->subject ?: '(No subject)' }}
          </h4>

          <p class="message-preview">{{ Str::limit($m->message, 120) }}</p>

          <div class="message-actions">
            <div class="message-meta">
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"></circle>
                  <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                {{ $m->created_at->diffForHumans() }}
              </span>
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M14 9V5a3 3 0 0 0-6 0v4"></path>
                  <rect x="2" y="9" width="20" height="12" rx="2" ry="2"></rect>
                </svg>
                {{ $m->created_at->format('H:i') }}
              </span>
            </div>

            <div class="action-buttons">
              <a href="{{ route('admin.messages.show',$m) }}" class="action-btn view">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                  <circle cx="12" cy="12" r="3"></circle>
                </svg>
                View
              </a>
              <form action="{{ route('admin.messages.destroy',$m) }}" method="POST" style="display: inline;">
                @csrf @method('DELETE')
                <button type="submit" class="action-btn delete" onclick="return confirm('Are you sure you want to delete this message?')">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
        </div>
      @endforeach
    </div>
  @else
    <div class="empty-state">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
      </svg>
      <h3>No messages yet</h3>
      <p>When visitors contact you through your portfolio, their messages will appear here. Stay connected with potential clients and collaborators.</p>
    </div>
  @endif

  @if($messages->hasPages())
    <div class="pagination-wrapper">
      {{ $messages->links() }}
    </div>
  @endif
@endsection
