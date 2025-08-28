@extends('layouts.app')
@section('title', $project->title . ' | Masud')
@push('styles') 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('assets/css/projects.css') }}"> 
@endpush

@section('content')
<div class="project-detail-container">
  <!-- Background Elements -->
  <div class="detail-background">
    <div class="floating-shapes">
      <div class="shape shape-1"></div>
      <div class="shape shape-2"></div>
      <div class="shape shape-3"></div>
    </div>
  </div>

  <!-- Back Navigation -->
  <div class="back-navigation">
    <a href="{{ route('projects') }}" class="back-btn">
      <i class="fas fa-arrow-left"></i> 
      <span>Back to Projects</span>
    </a>
  </div>

  <article class="project-detail">
    <!-- Project Header with Badge -->
    <div class="project-detail-header">
      <div class="project-badge">
        <i class="fas fa-star"></i>
        <span>Featured Project</span>
      </div>
      <h1>{{ $project->title }}</h1>
      
      <div class="project-meta-info">
        <div class="meta-item">
          <i class="fas fa-calendar-alt"></i>
          <span>Recently Updated</span>
        </div>
        @if($project->live_url)
          <div class="meta-item status-live">
            <i class="fas fa-circle"></i>
            <span>Live & Active</span>
          </div>
        @else
          <div class="meta-item">
            <i class="fas fa-code-branch"></i>
            <span>Development Complete</span>
          </div>
        @endif
      </div>
    </div>
    
    @if($project->image_url)
      <div class="hero-image-wrapper">
        <div class="image-frame">
          <img class="hero" src="{{ $project->image_url }}" alt="{{ $project->title }}">
          <div class="image-border"></div>
        </div>
        
        <!-- Quick Action Buttons Over Image -->
        <div class="hero-actions">
          @if($project->live_url)
            <a href="{{ $project->live_url }}" target="_blank" rel="noopener" class="hero-action-btn primary">
              <i class="fas fa-external-link-alt"></i>
              <span>View Live</span>
            </a>
          @endif
          @if($project->repo_url)
            <a href="{{ $project->repo_url }}" target="_blank" rel="noopener" class="hero-action-btn secondary">
              <i class="fab fa-github"></i>
              <span>Source Code</span>
            </a>
          @endif
        </div>
      </div>
    @endif

    <div class="project-detail-content">
      @if($project->tech_stack) 
        <div class="tech-section">
          <div class="section-header">
            <div class="section-icon">
              <i class="fas fa-tools"></i>
            </div>
            <h3>Technologies Used</h3>
            <div class="section-line"></div>
          </div>
          
          <div class="tech-grid">
            @php
              $techs = explode(',', $project->tech_stack);
            @endphp
            @foreach($techs as $tech)
              <div class="tech-item">
                <div class="tech-icon">
                  @php
                    $techLower = strtolower(trim($tech));
                  @endphp
                  @if(str_contains($techLower, 'laravel'))
                    <i class="fab fa-laravel"></i>
                  @elseif(str_contains($techLower, 'php'))
                    <i class="fab fa-php"></i>
                  @elseif(str_contains($techLower, 'javascript') || str_contains($techLower, 'js'))
                    <i class="fab fa-js-square"></i>
                  @elseif(str_contains($techLower, 'html'))
                    <i class="fab fa-html5"></i>
                  @elseif(str_contains($techLower, 'css'))
                    <i class="fab fa-css3-alt"></i>
                  @elseif(str_contains($techLower, 'python'))
                    <i class="fab fa-python"></i>
                  @elseif(str_contains($techLower, 'mysql'))
                    <i class="fas fa-database"></i>
                  @else
                    <i class="fas fa-code"></i>
                  @endif
                </div>
                <span class="tech-name">{{ trim($tech) }}</span>
              </div>
            @endforeach
          </div>
        </div>
      @endif
      
        <div class="description-section">
          <div class="section-header">
            <div class="section-icon">
              <i class="fas fa-info-circle"></i>
            </div>
            <h3>Project Overview</h3>
            <div class="section-line"></div>
          </div>        <div class="description-content">
          <div class="desc">{!! nl2br(e($project->description)) !!}</div>
        </div>

        <!-- Project Features -->
        <div class="project-features">
          <h4><i class="fas fa-check-circle"></i> Key Features</h4>
          <ul class="features-list">
            <li><i class="fas fa-arrow-right"></i> Modern & Responsive Design</li>
            <li><i class="fas fa-arrow-right"></i> Clean Code Architecture</li>
            <li><i class="fas fa-arrow-right"></i> User-Friendly Interface</li>
            <li><i class="fas fa-arrow-right"></i> Cross-Platform Compatibility</li>
            <li><i class="fas fa-arrow-right"></i> Performance Optimized</li>
            @if($project->live_url)
              <li><i class="fas fa-arrow-right"></i> Live Production Deployment</li>
            @endif
          </ul>
        </div>
      </div>
      
      @if($project->live_url || $project->repo_url)
        <div class="links-section">
          <div class="section-header">
            <div class="section-icon">
              <i class="fas fa-link"></i>
            </div>
            <h3>Project Links</h3>
            <div class="section-line"></div>
          </div>
          
          <div class="links-grid">
            @if($project->live_url)
              <a href="{{ $project->live_url }}" target="_blank" rel="noopener" class="link-card primary-card">
                <div class="link-card-icon">
                  <i class="fas fa-globe"></i>
                </div>
                <div class="link-card-content">
                  <h4>Live Demo</h4>
                  <p>Experience the project in action</p>
                  <span class="link-url">{{ parse_url($project->live_url, PHP_URL_HOST) }}</span>
                </div>
                <div class="link-arrow">
                  <i class="fas fa-external-link-alt"></i>
                </div>
              </a>
            @endif
            
            @if($project->repo_url)
              <a href="{{ $project->repo_url }}" target="_blank" rel="noopener" class="link-card secondary-card">
                <div class="link-card-icon">
                  <i class="fab fa-github"></i>
                </div>
                <div class="link-card-content">
                  <h4>Source Code</h4>
                  <p>Explore the complete codebase</p>
                  <span class="link-url">{{ parse_url($project->repo_url, PHP_URL_HOST) }}</span>
                </div>
                <div class="link-arrow">
                  <i class="fas fa-external-link-alt"></i>
                </div>
              </a>
            @endif
          </div>
        </div>
      @endif

      <!-- Project Stats -->
      <div class="project-stats-section">
        <div class="section-header">
          <div class="section-icon">
            <i class="fas fa-chart-bar"></i>
          </div>
          <h3>Project Highlights</h3>
          <div class="section-line"></div>
        </div>
        
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-icon">
              <i class="fas fa-code"></i>
            </div>
            <div class="stat-info">
              <span class="stat-value">Clean Code</span>
              <span class="stat-label">Architecture</span>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon">
              <i class="fas fa-mobile-alt"></i>
            </div>
            <div class="stat-info">
              <span class="stat-value">Responsive</span>
              <span class="stat-label">Design</span>
            </div>
          </div>
          
          @if($project->live_url)
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-rocket"></i>
              </div>
              <div class="stat-info">
                <span class="stat-value">Live</span>
                <span class="stat-label">Deployment</span>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </article>
</div>
@endsection
