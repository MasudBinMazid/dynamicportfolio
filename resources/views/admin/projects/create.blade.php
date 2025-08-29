@extends('layouts.admin')
@section('title','Create Project')

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

  .form-wrapper {
    max-width: 900px;
    margin: 0 auto;
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

  .save-draft-btn {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
    border: 2px solid rgba(245, 158, 11, 0.2);
  }

  .save-draft-btn:hover {
    background: var(--warning);
    color: white;
    border-color: var(--warning);
  }

  .form-tips {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(59, 130, 246, 0.1) 100%);
    border: 1px solid rgba(59, 130, 246, 0.2);
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
    color: var(--info);
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
    content: '💡';
    font-size: 16px;
    flex-shrink: 0;
  }

  @media (max-width: 768px) {
    .form-actions {
      flex-direction: column;
      align-items: stretch;
    }

    .form-actions-left {
      justify-content: space-between;
      width: 100%;
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
          <span>Create New</span>
        </div>
        <h1>
          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="16"></line>
            <line x1="8" y1="12" x2="16" y2="12"></line>
          </svg>
          Create New Project
        </h1>
      </div>
    </div>
  </div>

  <div class="form-wrapper">
    <div class="form-tips">
      <h3 class="form-tips-title">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"></circle>
          <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
          <line x1="12" y1="17" x2="12.01" y2="17"></line>
        </svg>
        Quick Tips
      </h3>
      <ul class="form-tips-list">
        <li>Use a descriptive title that clearly represents your project</li>
        <li>Include relevant technologies in your tech stack to improve discoverability</li>
        <li>Write a compelling description highlighting key features and achievements</li>
        <li>Add both live demo and repository links when available</li>
        <li>Mark your best work as "Featured" to showcase it prominently</li>
      </ul>
    </div>

    <form method="POST" action="{{ route('admin.projects.store') }}" class="modern-form" enctype="multipart/form-data">
      @csrf
      @include('admin.projects.partials.form', ['project' => null])
      
      <div class="form-actions">
        <div class="form-actions-left">
          <button type="submit" class="btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
              <polyline points="17 21 17 13 7 13 7 21"></polyline>
              <polyline points="7 3 7 8 15 8"></polyline>
            </svg>
            Create Project
          </button>
          
          <button type="button" class="btn-secondary save-draft-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
              <polyline points="14 2 14 8 20 8"></polyline>
            </svg>
            Save Draft
          </button>
        </div>
        
        <a href="{{ route('admin.projects.index') }}" class="btn-secondary">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
          </svg>
          Cancel
        </a>
      </div>
    </form>
  </div>
@endsection
