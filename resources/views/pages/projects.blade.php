@extends('layouts.app')
@section('title','Projects | Masud')
@push('styles') 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('assets/css/projects.css') }}"> 
@endpush

@section('content')
<div class="projects-container">
  <!-- Modern Header with Stats -->
  <div class="projects-header">
    <div class="header-background">
      <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
      </div>
    </div>
    
    <h1 class="projects-title">My <span class="highlight">Projects</span></h1>
    <p class="projects-subtitle">A showcase of my development work and technical expertise</p>
    
    <div class="projects-stats">
      <div class="stat-item">
        <div class="stat-icon">
          <i class="fas fa-code"></i>
        </div>
        <div class="stat-content">
          <span class="stat-number">{{ $projects->total() }}</span>
          <span class="stat-label">Projects</span>
        </div>
      </div>
      
      <div class="stat-item">
        <div class="stat-icon">
          <i class="fas fa-tools"></i>
        </div>
        <div class="stat-content">
          <span class="stat-number">10+</span>
          <span class="stat-label">Technologies</span>
        </div>
      </div>
      
      <div class="stat-item">
        <div class="stat-icon">
          <i class="fas fa-rocket"></i>
        </div>
        <div class="stat-content">
          <span class="stat-number">Live</span>
          <span class="stat-label">Deployments</span>
        </div>
      </div>
    </div>
  </div>

  <div class="projects-grid">
    @foreach($projects as $index => $p)
      <article class="project-card" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
        @if($p->image_url)
          <div class="project-image">
            <img src="{{ $p->image_url }}" alt="{{ $p->title }}">
            <div class="image-overlay">
              <div class="overlay-content">
                <h4>{{ $p->title }}</h4>
                <div class="overlay-buttons">
                  @if($p->live_url) 
                    <a href="{{ $p->live_url }}" target="_blank" rel="noopener" class="overlay-btn live-btn" onclick="event.stopPropagation()">
                      <i class="fas fa-external-link-alt"></i>
                    </a>
                  @endif
                  @if($p->repo_url) 
                    <a href="{{ $p->repo_url }}" target="_blank" rel="noopener" class="overlay-btn code-btn" onclick="event.stopPropagation()">
                      <i class="fab fa-github"></i>
                    </a>
                  @endif
                </div>
              </div>
            </div>
          </div>
        @else
          <div class="project-placeholder">
            <i class="fas fa-code project-icon"></i>
            <div class="placeholder-text">{{ $p->title }}</div>
          </div>
        @endif

        <div class="project-content">
          <div class="project-header">
            <h3><a href="{{ route('projects.show',$p->slug) }}">{{ $p->title }}</a></h3>
            <div class="project-meta">
              <span class="project-date">
                <i class="fas fa-calendar-alt"></i>
                Recent
              </span>
            </div>
          </div>
          
          @if($p->tech_stack)
            <div class="tech-stack-wrapper">
              <p class="stack">
                @php
                  $techs = array_slice(explode(',', $p->tech_stack), 0, 3);
                @endphp
                @foreach($techs as $tech)
                  <span class="tech-badge">{{ trim($tech) }}</span>
                @endforeach
                @if(count(explode(',', $p->tech_stack)) > 3)
                  <span class="tech-badge more-badge">+{{ count(explode(',', $p->tech_stack)) - 3 }}</span>
                @endif
              </p>
            </div>
          @endif
          
          <p class="project-description">{{ Str::limit($p->description,120) }}</p>

          <div class="project-footer">
            <div class="project-links">
              @if($p->live_url) 
                <a href="{{ $p->live_url }}" target="_blank" rel="noopener" class="project-link live-link" onclick="event.stopPropagation()">
                  <i class="fas fa-external-link-alt"></i> 
                  <span>Live</span>
                </a> 
              @endif
              @if($p->repo_url) 
                <a href="{{ $p->repo_url }}" target="_blank" rel="noopener" class="project-link code-link" onclick="event.stopPropagation()">
                  <i class="fab fa-github"></i> 
                  <span>Code</span>
                </a> 
              @endif
            </div>
            
            <a href="{{ route('projects.show',$p->slug) }}" class="view-details-btn">
              <span>View Details</span>
              <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        </div>

        <div class="card-glow"></div>
      </article>
    @endforeach
  </div>

  @if($projects->hasPages())
    <div class="pagination-wrapper">
      <div class="pagination-container">
        {{ $projects->links() }}
      </div>
    </div>
  @endif
</div>
@endsection

@push('scripts') <script src="{{ asset('assets/js/projects.js') }}" defer></script> @endpush
