@extends('layouts.app')
@section('title', $project->title . ' | Masud')
@push('styles') 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('assets/css/projects.css') }}"> 
@endpush

@section('content')
<div class="project-detail-container">
  <!-- Back Navigation -->
  <div class="back-navigation">
    <a href="{{ route('projects') }}" class="back-btn">
      <i class="fas fa-arrow-left"></i> Back to Projects
    </a>
  </div>

  <article class="project-detail">
    <div class="project-detail-header">
      <h1>{{ $project->title }}</h1>
    </div>
    
    @if($project->image_url)
      <div class="hero-image-wrapper">
        <img class="hero" src="{{ $project->image_url }}" alt="{{ $project->title }}">
      </div>
    @endif

    <div class="project-detail-content">
      @if($project->tech_stack) 
        <div class="tech-section">
          <h3><i class="fas fa-tools"></i> Technologies Used</h3>
          <p class="stack">{{ $project->tech_stack }}</p>
        </div>
      @endif
      
      <div class="description-section">
        <h3><i class="fas fa-info-circle"></i> Project Description</h3>
        <div class="desc">{!! nl2br(e($project->description)) !!}</div>
      </div>
      
      @if($project->live_url || $project->repo_url)
        <div class="links-section">
          <h3><i class="fas fa-link"></i> Project Links</h3>
          <p class="links">
            @if($project->live_url) <a target="_blank" rel="noopener" href="{{ $project->live_url }}"><i class="fas fa-external-link-alt"></i> View Live</a> @endif
            @if($project->repo_url) <a target="_blank" rel="noopener" href="{{ $project->repo_url }}"><i class="fab fa-github"></i> View Code</a> @endif
          </p>
        </div>
      @endif
    </div>
  </article>
</div>
@endsection
