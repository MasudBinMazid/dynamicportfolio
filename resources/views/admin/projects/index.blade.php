@extends('layouts.admin')
@section('title','Projects')

@push('styles')
<style>
  .projects-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
    flex-wrap: wrap;
    gap: 16px;
  }

  .projects-title {
    font-size: 28px;
    font-weight: 700;
    margin: 0;
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .projects-stats {
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

  .projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
  }

  .project-card {
    background: var(--card);
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--shadow);
    transition: var(--transition);
    position: relative;
    border: 2px solid transparent;
    overflow: hidden;
  }

  .project-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary);
  }

  .project-card.featured {
    border-color: var(--success);
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.03) 0%, rgba(16, 185, 129, 0.08) 100%);
  }

  .project-card.featured::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 0 40px 40px 0;
    border-color: transparent var(--success) transparent transparent;
  }

  .project-card.featured::after {
    content: '★';
    position: absolute;
    top: 8px;
    right: 8px;
    color: white;
    font-size: 16px;
    z-index: 1;
  }

  .project-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 16px;
  }

  .project-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--text);
    margin: 0 0 8px 0;
    line-height: 1.4;
    flex: 1;
    margin-right: 12px;
  }

  .project-slug {
    font-size: 12px;
    color: var(--text-light);
    background: var(--secondary);
    padding: 4px 8px;
    border-radius: 12px;
    font-family: 'Monaco', 'Menlo', monospace;
    margin-bottom: 16px;
    display: inline-block;
  }

  .project-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 20px;
    padding-top: 16px;
    border-top: 1px solid var(--border);
  }

  .featured-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .featured-badge.yes {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
  }

  .featured-badge.no {
    background: var(--secondary);
    color: var(--text-light);
  }

  .project-actions {
    display: flex;
    gap: 8px;
    align-items: center;
  }

  .action-btn {
    padding: 8px 12px;
    border: none;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }

  .action-btn.edit {
    background: rgba(59, 130, 246, 0.1);
    color: var(--info);
  }

  .action-btn.edit:hover {
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

  .new-project-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
    color: white;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 600;
    transition: var(--transition);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
  }

  .new-project-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4);
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
    margin: 0 0 24px 0;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
  }

  .pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 32px;
  }

  /* Animation for new cards */
  .project-card {
    animation: slideInUp 0.5s ease forwards;
  }

  .project-card:nth-child(1) { animation-delay: 0.1s; }
  .project-card:nth-child(2) { animation-delay: 0.2s; }
  .project-card:nth-child(3) { animation-delay: 0.3s; }
  .project-card:nth-child(4) { animation-delay: 0.4s; }
  .project-card:nth-child(5) { animation-delay: 0.5s; }
  .project-card:nth-child(6) { animation-delay: 0.6s; }

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
    .projects-header {
      flex-direction: column;
      align-items: stretch;
      text-align: center;
    }

    .projects-grid {
      grid-template-columns: 1fr;
      gap: 16px;
    }

    .projects-stats {
      justify-content: center;
      flex-wrap: wrap;
    }

    .project-meta {
      flex-direction: column;
      gap: 12px;
      align-items: flex-start;
    }

    .project-actions {
      width: 100%;
      justify-content: space-between;
    }
  }

  /* Loading state */
  .loading-shimmer {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
  }

  @keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
  }
</style>
@endpush

@section('content')
  <div class="projects-header">
    <div>
      <h1 class="projects-title">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
          <polyline points="2 17 12 22 22 17"></polyline>
          <polyline points="2 12 12 17 22 12"></polyline>
        </svg>
        Projects
      </h1>
      <div class="projects-stats">
        <div class="stat-item">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
            <polyline points="2 17 12 22 22 17"></polyline>
            <polyline points="2 12 12 17 22 12"></polyline>
          </svg>
          {{ $projects->total() }} Total
        </div>
        <div class="stat-item">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
            <polyline points="2 17 12 22 22 17"></polyline>
            <polyline points="2 12 12 17 22 12"></polyline>
          </svg>
          {{ $projects->where('featured', true)->count() }} Featured
        </div>
      </div>
    </div>
    <a href="{{ route('admin.projects.create') }}" class="new-project-btn">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"></circle>
        <line x1="12" y1="8" x2="12" y2="16"></line>
        <line x1="8" y1="12" x2="16" y2="12"></line>
      </svg>
      New Project
    </a>
  </div>

  @if($projects->count() > 0)
    <div class="projects-grid">
      @foreach($projects as $p)
        <div class="project-card {{ $p->featured ? 'featured' : '' }}">
          <div class="project-header">
            <div>
              <h3 class="project-title">{{ $p->title }}</h3>
              <div class="project-slug">{{ $p->slug }}</div>
            </div>
          </div>
          
          <div class="project-meta">
            <div class="featured-badge {{ $p->featured ? 'yes' : 'no' }}">
              @if($p->featured)
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
                Featured
              @else
                Regular
              @endif
            </div>
            
            <div class="project-actions">
              <a href="{{ route('admin.projects.edit',$p) }}" class="action-btn edit">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Edit
              </a>
              <form action="{{ route('admin.projects.destroy',$p) }}" method="POST" style="display:inline">
                @csrf @method('DELETE')
                <button type="submit" class="action-btn delete" onclick="return confirm('Are you sure you want to delete this project?')">
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
        <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
        <polyline points="2 17 12 22 22 17"></polyline>
        <polyline points="2 12 12 17 22 12"></polyline>
      </svg>
      <h3>No projects yet</h3>
      <p>Start building your portfolio by creating your first project. Showcase your work and attract potential clients or employers.</p>
      <a href="{{ route('admin.projects.create') }}" class="new-project-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="12" y1="8" x2="12" y2="16"></line>
          <line x1="8" y1="12" x2="16" y2="12"></line>
        </svg>
        Create Your First Project
      </a>
    </div>
  @endif

  @if($projects->hasPages())
    <div class="pagination-wrapper">
      {{ $projects->links() }}
    </div>
  @endif
@endsection
