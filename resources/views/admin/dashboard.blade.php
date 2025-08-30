@extends('layouts.admin')

@section('title','Admin Dashboard')

@section('content')
  <div class="dashboard-header">
    <h1 class="dashboard-title">Welcome back! 👋</h1>
    <p class="dashboard-subtitle">Here's what's happening with your portfolio today.</p>
  </div>

  <div class="stats-grid">
    <div class="stat-card projects">
      <div class="stat-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
          <polyline points="2 17 12 22 22 17"></polyline>
          <polyline points="2 12 12 17 22 12"></polyline>
        </svg>
      </div>
      <div class="stat-number">{{ $stats['projects'] }}</div>
      <p class="stat-label">Total Projects</p>
      <div class="stat-change positive">
        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
          <polyline points="17 6 23 6 23 12"></polyline>
        </svg>
        Your portfolio is growing!
      </div>
    </div>

    <div class="stat-card featured">
      <div class="stat-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
        </svg>
      </div>
      <div class="stat-number">{{ $stats['featured'] }}</div>
      <p class="stat-label">Featured Projects</p>
      <div class="stat-change {{ $stats['featured'] > 0 ? 'positive' : 'negative' }}">
        {{ $stats['featured'] > 0 ? 'Great selection!' : 'Consider featuring some projects' }}
      </div>
    </div>

    <div class="stat-card messages">
      <div class="stat-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
      </div>
      <div class="stat-number">{{ $stats['messages'] }}</div>
      <p class="stat-label">Contact Messages</p>
      <div class="stat-change {{ $stats['messages'] > 0 ? 'positive' : '' }}">
        {{ $stats['messages'] > 0 ? 'People are reaching out!' : 'No new messages' }}
      </div>
    </div>

    <div class="stat-card chat">
      <div class="stat-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14 9a2 2 0 0 1-2 2H6l-4 4V4c0-1.1.9-2 2-2h8a2 2 0 0 1 2 2v5Z"></path>
          <path d="M18 9h2a2 2 0 0 1 2 2v11l-4-4h-6a2 2 0 0 1-2-2v-1"></path>
        </svg>
      </div>
      <div class="stat-number">{{ $stats['chat_sessions'] }}</div>
      <p class="stat-label">Chat Conversations</p>
      <div class="stat-change {{ $stats['unread_chats'] > 0 ? 'positive' : '' }}">
        @if($stats['unread_chats'] > 0)
          {{ $stats['unread_chats'] }} unread messages!
        @else
          All caught up!
        @endif
      </div>
    </div>
  </div>

  <div class="quick-actions">
    <h3>Quick Actions</h3>
    <div class="actions-grid">
      <a href="{{ route('admin.projects.create') }}" class="action-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"></line>
          <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Add New Project
      </a>
      
      <a href="{{ route('admin.projects.index') }}" class="action-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="7" height="7"></rect>
          <rect x="14" y="3" width="7" height="7"></rect>
          <rect x="14" y="14" width="7" height="7"></rect>
          <rect x="3" y="14" width="7" height="7"></rect>
        </svg>
        Manage Projects
      </a>
      
      <a href="{{ route('admin.contact_info.edit') }}" class="action-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 20h9"></path>
          <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
        </svg>
        Update Contact Info
      </a>
      
      <a href="{{ route('admin.messages.index') }}" class="action-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
          <polyline points="22,6 12,13 2,6"></polyline>
        </svg>
        View Messages
      </a>
      
      <a href="{{ route('admin.chat.index') }}" class="action-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14 9a2 2 0 0 1-2 2H6l-4 4V4c0-1.1.9-2 2-2h8a2 2 0 0 1 2 2v5Z"></path>
          <path d="M18 9h2a2 2 0 0 1 2 2v11l-4-4h-6a2 2 0 0 1-2-2v-1"></path>
        </svg>
        Manage Live Chat
      </a>
    </div>
  </div>

  <!-- AI Status Section -->
  <div class="ai-status-section">
    <div class="ai-status-card">
      <div class="ai-header">
        <div class="ai-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"></path>
            <path d="M19 10v2a7 7 0 0 1-14 0v-2"></path>
            <line x1="12" y1="19" x2="12" y2="22"></line>
          </svg>
        </div>
        <div class="ai-info">
          <h3>AI Chat Assistant</h3>
          <p>Intelligent responses for better user engagement</p>
        </div>
        <div class="ai-status-badge">
          @if($stats['ai_enabled'] && $stats['ai_configured'])
            <span class="status-active">🤖 Active</span>
          @elseif($stats['ai_enabled'] && !$stats['ai_configured'])
            <span class="status-warning">⚠️ API Key Required</span>
          @else
            <span class="status-inactive">😴 Disabled</span>
          @endif
        </div>
      </div>
      
      <div class="ai-details">
        <div class="detail-item">
          <span class="detail-label">Status:</span>
          <span class="detail-value">{{ $stats['ai_enabled'] ? 'Enabled' : 'Disabled' }}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">API Key:</span>
          <span class="detail-value">{{ $stats['ai_configured'] ? 'Configured' : 'Not Set' }}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Model:</span>
          <span class="detail-value">{{ config('chat.ai_model', 'gpt-3.5-turbo') }}</span>
        </div>
      </div>
      
      @if(!$stats['ai_enabled'] || !$stats['ai_configured'])
        <div class="ai-setup-info">
          <h4>Setup Instructions:</h4>
          <ol>
            <li>Get your OpenAI API key from <a href="https://platform.openai.com/api-keys" target="_blank">OpenAI Platform</a></li>
            <li>Add <code>OPENAI_API_KEY=your-key-here</code> to your .env file</li>
            <li>Set <code>CHAT_AI_ENABLED=true</code> in your .env file</li>
            <li>Restart your application</li>
          </ol>
        </div>
      @endif
    </div>
  </div>

  @if($stats['messages'] > 0)
    <div class="recent-activity">
      <div class="activity-card">
        <h3>Recent Activity</h3>
        <div class="activity-item">
          <div class="activity-icon messages">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
          </div>
          <div class="activity-content">
            <p><strong>{{ $stats['messages'] }}</strong> new contact message{{ $stats['messages'] > 1 ? 's' : '' }}</p>
            <span class="activity-time">Check your inbox for new opportunities!</span>
          </div>
        </div>
      </div>
    </div>
  @endif

@endsection

@push('styles')
<style>
  .recent-activity {
    margin-top: 24px;
  }
  
  .activity-card {
    background: var(--card);
    padding: 24px;
    border-radius: 12px;
    box-shadow: var(--shadow);
  }
  
  .activity-card h3 {
    margin: 0 0 16px 0;
    font-size: 18px;
    font-weight: 600;
  }
  
  .activity-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 12px 0;
  }
  
  .activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  
  .activity-icon.messages {
    background-color: rgba(245, 158, 11, 0.1);
    color: var(--warning);
  }
  
  .activity-content p {
    margin: 0 0 4px 0;
    font-weight: 500;
  }
  
  .activity-time {
    font-size: 14px;
    color: var(--text-light);
  }
  
  /* Animation for stat cards */
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
  
  .stat-card {
    animation: slideInUp 0.6s ease forwards;
  }
  
  .stat-card:nth-child(1) { animation-delay: 0.1s; }
  .stat-card:nth-child(2) { animation-delay: 0.2s; }
  .stat-card:nth-child(3) { animation-delay: 0.3s; }
  
  /* Responsive design */
  @media (max-width: 768px) {
    .stats-grid {
      grid-template-columns: 1fr;
    }
    
    .actions-grid {
      grid-template-columns: 1fr;
    }
  }

  /* AI Status Section Styles */
  .ai-status-section {
    margin-bottom: 32px;
  }

  .ai-status-card {
    background: var(--card);
    border-radius: 12px;
    padding: 24px;
    box-shadow: var(--shadow);
    border-left: 4px solid var(--info);
  }

  .ai-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
  }

  .ai-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(59, 130, 246, 0.1);
    color: var(--info);
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .ai-info h3 {
    margin: 0 0 4px 0;
    font-size: 18px;
    color: var(--text);
  }

  .ai-info p {
    margin: 0;
    color: var(--text-light);
    font-size: 14px;
  }

  .ai-status-badge {
    margin-left: auto;
  }

  .status-active {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
  }

  .status-warning {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
  }

  .status-inactive {
    background: rgba(107, 114, 128, 0.1);
    color: var(--text-light);
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
  }

  .ai-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 16px;
    margin-bottom: 20px;
  }

  .detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid var(--border);
  }

  .detail-label {
    font-weight: 500;
    color: var(--text-light);
    font-size: 14px;
  }

  .detail-value {
    color: var(--text);
    font-weight: 600;
    font-size: 14px;
  }

  .ai-setup-info {
    background: rgba(59, 130, 246, 0.05);
    border-radius: 8px;
    padding: 16px;
    border: 1px solid rgba(59, 130, 246, 0.2);
  }

  .ai-setup-info h4 {
    margin: 0 0 12px 0;
    color: var(--info);
    font-size: 14px;
  }

  .ai-setup-info ol {
    margin: 0;
    padding-left: 20px;
    font-size: 13px;
    color: var(--text-light);
    line-height: 1.5;
  }

  .ai-setup-info li {
    margin-bottom: 8px;
  }

  .ai-setup-info code {
    background: rgba(0, 0, 0, 0.1);
    padding: 2px 6px;
    border-radius: 4px;
    font-family: monospace;
    font-size: 12px;
  }

  .ai-setup-info a {
    color: var(--info);
    text-decoration: none;
  }

  .ai-setup-info a:hover {
    text-decoration: underline;
  }

  @media (max-width: 768px) {
    .ai-header {
      flex-direction: column;
      align-items: flex-start;
      text-align: left;
    }
    
    .ai-status-badge {
      margin-left: 0;
      align-self: flex-start;
    }
    
    .ai-details {
      grid-template-columns: 1fr;
    }
  }
</style>
@endpush

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Add interactive hover effects to stat cards
    const statCards = document.querySelectorAll('.stat-card');
    
    statCards.forEach(card => {
      card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-4px) scale(1.02)';
      });
      
      card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
      });
    });
    
    // Add click animation to action buttons
    const actionBtns = document.querySelectorAll('.action-btn');
    
    actionBtns.forEach(btn => {
      btn.addEventListener('click', function(e) {
        // Create ripple effect
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.cssText = `
          position: absolute;
          width: ${size}px;
          height: ${size}px;
          left: ${x}px;
          top: ${y}px;
          background: rgba(255, 255, 255, 0.4);
          border-radius: 50%;
          transform: scale(0);
          animation: ripple 0.6s linear;
          pointer-events: none;
        `;
        
        this.style.position = 'relative';
        this.style.overflow = 'hidden';
        this.appendChild(ripple);
        
        setTimeout(() => ripple.remove(), 600);
      });
    });
    
    // Add CSS for ripple animation
    const style = document.createElement('style');
    style.textContent = `
      @keyframes ripple {
        to {
          transform: scale(4);
          opacity: 0;
        }
      }
    `;
    document.head.appendChild(style);
  });
</script>
@endpush
