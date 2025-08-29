@extends('layouts.admin')
@section('title','Message')

@push('styles')
<style>
  .page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 32px;
    flex-wrap: wrap;
    gap: 16px;
  }

  .page-title {
    display: flex;
    align-items: center;
    gap: 16px;
    margin: 0;
  }

  .page-title h1 {
    font-size: 28px;
    font-weight: 700;
    color: var(--text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-light);
    font-size: 14px;
  }

  .breadcrumb a {
    color: var(--text-light);
    text-decoration: none;
    transition: var(--transition);
  }

  .breadcrumb a:hover {
    color: var(--primary);
  }

  .message-container {
    max-width: 800px;
    margin: 0 auto;
  }

  .message-card {
    background: var(--card);
    border-radius: 16px;
    box-shadow: var(--shadow);
    overflow: hidden;
    margin-bottom: 24px;
  }

  .message-header {
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.05) 0%, rgba(79, 70, 229, 0.1) 100%);
    border-bottom: 1px solid var(--border);
    padding: 32px;
  }

  .sender-info {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    margin-bottom: 24px;
  }

  .sender-avatar {
    width: 60px;
    height: 60px;
    background: var(--primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    font-weight: 600;
    flex-shrink: 0;
  }

  .sender-details {
    flex: 1;
  }

  .sender-name {
    font-size: 20px;
    font-weight: 600;
    color: var(--text);
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .sender-email {
    font-size: 16px;
    color: var(--text-light);
    margin: 0 0 12px 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .sender-email a {
    color: var(--primary);
    text-decoration: none;
    transition: var(--transition);
  }

  .sender-email a:hover {
    text-decoration: underline;
  }

  .message-meta {
    display: flex;
    align-items: center;
    gap: 20px;
    font-size: 14px;
    color: var(--text-light);
    flex-wrap: wrap;
  }

  .meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: var(--card);
    border-radius: 20px;
    border: 1px solid var(--border);
  }

  .message-subject {
    background: var(--card);
    padding: 24px 32px;
    border-bottom: 1px solid var(--border);
  }

  .subject-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .subject-title.no-subject {
    color: var(--text-light);
    font-style: italic;
  }

  .message-content {
    padding: 32px;
  }

  .content-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--text);
    margin: 0 0 16px 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .message-text {
    background: var(--bg);
    padding: 24px;
    border-radius: 12px;
    border-left: 4px solid var(--primary);
    font-family: 'Courier New', monospace;
    font-size: 14px;
    line-height: 1.6;
    color: var(--text);
    white-space: pre-wrap;
    word-wrap: break-word;
    margin: 0;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
  }

  .message-actions {
    background: var(--bg);
    padding: 24px 32px;
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
  }

  .action-buttons {
    display: flex;
    gap: 12px;
    align-items: center;
  }

  .btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
  }

  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4);
  }

  .btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: var(--secondary);
    color: var(--text);
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
  }

  .btn-secondary:hover {
    background: var(--secondary-hover);
    transform: translateY(-1px);
  }

  .btn-danger {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger);
    border: 2px solid rgba(239, 68, 68, 0.2);
  }

  .btn-danger:hover {
    background: var(--danger);
    color: white;
    border-color: var(--danger);
  }

  .reply-info {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: var(--text-light);
  }

  .delete-form-wrapper {
    margin-top: 24px;
    padding: 24px;
    background: rgba(239, 68, 68, 0.02);
    border: 1px solid rgba(239, 68, 68, 0.1);
    border-radius: 16px;
    text-align: center;
  }

  .delete-warning {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-bottom: 16px;
    font-size: 14px;
    color: var(--danger);
    font-weight: 500;
  }

  /* Responsive design */
  @media (max-width: 768px) {
    .page-header {
      flex-direction: column;
      align-items: flex-start;
    }

    .message-header,
    .message-subject,
    .message-content,
    .message-actions {
      padding-left: 20px;
      padding-right: 20px;
    }

    .sender-info {
      flex-direction: column;
      text-align: center;
      gap: 16px;
    }

    .sender-details {
      text-align: center;
    }

    .message-meta {
      justify-content: center;
    }

    .message-actions {
      flex-direction: column;
      gap: 20px;
    }

    .action-buttons {
      width: 100%;
      justify-content: space-between;
    }

    .btn-primary,
    .btn-secondary {
      flex: 1;
      justify-content: center;
    }
  }

  /* Animation */
  .message-card {
    animation: slideInUp 0.6s ease forwards;
  }

  @keyframes slideInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>
@endpush

@section('content')
  <div class="page-header">
    <div class="page-title">
      <div>
        <div class="breadcrumb">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"></polyline>
          </svg>
          <a href="{{ route('admin.messages.index') }}">Messages</a>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"></polyline>
          </svg>
          <span>View Message</span>
        </div>
        <h1>
          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
          </svg>
          Message Details
        </h1>
      </div>
    </div>
  </div>

  <div class="message-container">
    <div class="message-card">
      <!-- Message Header with Sender Info -->
      <div class="message-header">
        <div class="sender-info">
          <div class="sender-avatar">
            {{ strtoupper(substr($message->name, 0, 1)) }}
          </div>
          <div class="sender-details">
            <h2 class="sender-name">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
              {{ $message->name }}
            </h2>
            <p class="sender-email">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                <polyline points="22,6 12,13 2,6"></polyline>
              </svg>
              <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
            </p>
            <div class="message-meta">
              <div class="meta-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                  <line x1="16" y1="2" x2="16" y2="6"></line>
                  <line x1="8" y1="2" x2="8" y2="6"></line>
                  <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                {{ $message->created_at->format('M j, Y') }}
              </div>
              <div class="meta-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"></circle>
                  <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                {{ $message->created_at->format('H:i') }}
              </div>
              <div class="meta-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"></circle>
                  <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                {{ $message->created_at->diffForHumans() }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Message Subject -->
      <div class="message-subject">
        <h3 class="subject-title {{ !$message->subject ? 'no-subject' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
            <line x1="16" y1="13" x2="8" y2="13"></line>
            <line x1="16" y1="17" x2="8" y2="17"></line>
            <polyline points="10 9 9 9 8 9"></polyline>
          </svg>
          {{ $message->subject ?? '(No subject)' }}
        </h3>
      </div>

      <!-- Message Content -->
      <div class="message-content">
        <h4 class="content-title">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
          </svg>
          Message Content
        </h4>
        <pre class="message-text">{{ $message->message }}</pre>
      </div>

      <!-- Message Actions -->
      <div class="message-actions">
        <div class="reply-info">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
            <line x1="12" y1="17" x2="12.01" y2="17"></line>
          </svg>
          Click email address above to reply directly
        </div>
        
        <div class="action-buttons">
          <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject ?? 'Your message' }}" class="btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
            </svg>
            Reply via Email
          </a>
          
          <a href="{{ route('admin.messages.index') }}" class="btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="19" y1="12" x2="5" y2="12"></line>
              <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to Messages
          </a>
        </div>
      </div>
    </div>

    <!-- Delete Form Section -->
    <div class="delete-form-wrapper">
      <div class="delete-warning">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
          <line x1="12" y1="9" x2="12" y2="13"></line>
          <line x1="12" y1="17" x2="12.01" y2="17"></line>
        </svg>
        Danger Zone - This action cannot be undone
      </div>
      
      <form action="{{ route('admin.messages.destroy',$message) }}" method="POST">
        @csrf @method('DELETE')
        <button type="submit" class="btn-secondary btn-danger" onclick="return confirm('Are you sure you want to delete this message? This action cannot be undone.')">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
            <line x1="10" y1="11" x2="10" y2="17"></line>
            <line x1="14" y1="11" x2="14" y2="17"></line>
          </svg>
          Delete Message
        </button>
      </form>
    </div>
  </div>
@endsection
