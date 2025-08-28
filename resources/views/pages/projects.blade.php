@extends('layouts.app')
@section('title','Projects | Masud')
@push('styles') 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('assets/css/projects.css') }}"> 
@endpush

@section('content')
<div class="projects-container">
  <!-- Modern Header -->
  <div class="projects-header">
    <h1 class="projects-title">My <span class="highlight">Projects</span></h1>
    <p class="projects-subtitle">A showcase of my development work and technical expertise</p>
  </div>

  <div class="projects-grid">
    @foreach($projects as $p)
      <article class="project-card">
        @if($p->image_url)
          <div class="project-image">
            <img src="{{ $p->image_url }}" alt="{{ $p->title }}">
          </div>
        @endif

        <div class="project-content">
          <h3><a href="{{ route('projects.show',$p->slug) }}">{{ $p->title }}</a></h3>
          @if($p->tech_stack)
            <p class="stack">{{ $p->tech_stack }}</p>
          @endif
          <p class="project-description">{{ Str::limit($p->description,120) }}</p>
          <p class="links">
            @if($p->live_url) <a target="_blank" rel="noopener" href="{{ $p->live_url }}"><i class="fas fa-external-link-alt"></i> Live</a> @endif
            @if($p->repo_url) <a target="_blank" rel="noopener" href="{{ $p->repo_url }}"><i class="fab fa-github"></i> Code</a> @endif
          </p>
        </div>
      </article>
    @endforeach
  </div>

  <div class="pagination-wrapper">
    {{ $projects->links() }}
  </div>
</div>
@endsection

@push('scripts') <script src="{{ asset('assets/js/projects.js') }}" defer></script> @endpush
