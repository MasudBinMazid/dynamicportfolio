@extends('layouts.admin')
@section('title','Edit Project')

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

  .project-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .project-status.featured {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
  }

  .project-status.regular {
    background: var(--secondary);
    color: var(--text-light);
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

  .form-wrapper {
    max-width: 900px;
    margin: 0 auto;
  }

  .project-info-banner {
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.05) 0%, rgba(79, 70, 229, 0.1) 100%);
    border: 1px solid rgba(79, 70, 229, 0.2);
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 20px;
  }

  .project-info-content {
    flex: 1;
  }

  .project-info-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--text);
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .project-info-meta {
    display: flex;
    align-items: center;
    gap: 16px;
    color: var(--text-light);
    font-size: 14px;
  }

  .project-info-meta span {
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .project-actions-quick {
    display: flex;
    gap: 12px;
    align-items: center;
  }

  .quick-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: var(--card);
    color: var(--text);
    border: 2px solid var(--border);
    border-radius: 8px;
    font-size: 12px;
    font-weight: 500;
    text-decoration: none;
    transition: var(--transition);
  }

  .quick-action-btn:hover {
    border-color: var(--primary);
    color: var(--primary);
    transform: translateY(-1px);
  }

  .form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    margin-top: 32px;
    padding: 24px;
    background: var(--card);
    border-radius: 16px;
    box-shadow: var(--shadow);
  }

  .form-actions-left {
    display: flex;
    gap: 12px;
  }

  .form-actions-right {
    display: flex;
    gap: 12px;
    align-items: center;
  }

  .btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
  }

  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
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

  .form-tips {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.05) 0%, rgba(245, 158, 11, 0.1) 100%);
    border: 1px solid rgba(245, 158, 11, 0.2);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
  }

  .form-tips-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 16px;
    font-weight: 600;
    color: var(--warning);
    margin: 0 0 12px 0;
  }

  .form-tips-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    gap: 8px;
  }

  .form-tips-list li {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    color: var(--text);
    font-size: 14px;
  }

  .form-tips-list li::before {
    content: '⚡';
    font-size: 16px;
    flex-shrink: 0;
  }

  .delete-form-wrapper {
    margin-top: 24px;
    padding: 24px;
    background: rgba(239, 68, 68, 0.02);
    border: 1px solid rgba(239, 68, 68, 0.1);
    border-radius: 16px;
    text-align: center;
  }

  .delete-form {
    display: inline-block;
  }

  @media (max-width: 768px) {
    .form-actions {
      flex-direction: column;
      align-items: stretch;
    }

    .form-actions-left,
    .form-actions-right {
      justify-content: space-between;
      width: 100%;
    }

    .form-actions-right {
      flex-direction: column-reverse;
      gap: 12px;
    }

    .btn-primary,
    .btn-secondary {
      flex: 1;
      justify-content: center;
    }

    .page-header {
      flex-direction: column;
      align-items: flex-start;
    }

    .project-info-banner {
      flex-direction: column;
      text-align: center;
    }

    .project-actions-quick {
      width: 100%;
      justify-content: center;
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
          <a href="{{ route('admin.projects.index') }}">Projects</a>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"></polyline>
          </svg>
          <span>Edit</span>
        </div>
        <h1>
          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
          </svg>
          Edit Project
        </h1>
      </div>
    </div>
  </div>

  <div class="form-wrapper">
    <div class="project-info-banner">
      <div class="project-info-content">
        <h2 class="project-info-title">
          {{ $project->title }}
          <span class="project-status {{ $project->featured ? 'featured' : 'regular' }}">
            @if($project->featured)
              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
              </svg>
              Featured
            @else
              Regular
            @endif
          </span>
        </h2>
        <div class="project-info-meta">
          <span>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
              <circle cx="12" cy="10" r="3"></circle>
            </svg>
            {{ $project->slug }}
          </span>
          <span>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            Updated {{ $project->updated_at->diffForHumans() }}
          </span>
        </div>
      </div>
      <div class="project-actions-quick">
        @if($project->live_url)
          <a href="{{ $project->live_url }}" target="_blank" class="quick-action-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
              <polyline points="15 3 21 3 21 9"></polyline>
              <line x1="10" y1="14" x2="21" y2="3"></line>
            </svg>
            View Live
          </a>
        @endif
        @if($project->repo_url)
          <a href="{{ $project->repo_url }}" target="_blank" class="quick-action-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path>
            </svg>
            Repository
          </a>
        @endif
      </div>
    </div>

    <div class="form-tips">
      <h3 class="form-tips-title">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
        </svg>
        Editing Tips
      </h3>
      <ul class="form-tips-list">
        <li>Changes are automatically saved as drafts while you edit</li>
        <li>Update your project image to keep your portfolio fresh</li>
        <li>Review your links to ensure they're still working</li>
        <li>Consider toggling "Featured" status to highlight your best work</li>
      </ul>
    </div>

    <form method="POST" action="{{ route('admin.projects.update',$project) }}" class="modern-form" enctype="multipart/form-data">
      @csrf @method('PUT')
      @include('admin.projects.partials.form', ['project' => $project])
      
      <div class="form-actions">
        <div class="form-actions-left">
          <button type="submit" class="btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
              <polyline points="17 21 17 13 7 13 7 21"></polyline>
              <polyline points="7 3 7 8 15 8"></polyline>
            </svg>
            Update Project
          </button>
        </div>
        
        <div class="form-actions-right">
          <a href="{{ route('admin.projects.index') }}" class="btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="19" y1="12" x2="5" y2="12"></line>
              <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to Projects
          </a>
        </div>
      </div>
    </form>

    <!-- Separate Delete Form -->
    <div class="delete-form-wrapper">
      <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" class="delete-form">
        @csrf
        @method('DELETE')
        <button 
          type="submit" 
          class="btn-secondary btn-danger"
          onclick="return confirm('Are you sure you want to delete this project? This action cannot be undone.')"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
            <line x1="10" y1="11" x2="10" y2="17"></line>
            <line x1="14" y1="11" x2="14" y2="17"></line>
          </svg>
          Delete Project
        </button>
      </form>
    </div>
    </form>
  </div>
@endsection
