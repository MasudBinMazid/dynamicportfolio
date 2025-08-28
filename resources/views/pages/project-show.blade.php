@extends('layouts.app')
@section('title', $project->title . ' | Masud')
@push('styles') <link rel="stylesheet" href="{{ asset('assets/css/projects.css') }}"> @endpush

@section('content')
  <article class="project-detail">
    <h1>{{ $project->title }}</h1>
    @if($project->image_url)
      <img class="hero" src="{{ $project->image_url }}" alt="{{ $project->title }}">
    @endif

    @if($project->tech_stack) <p class="stack">{{ $project->tech_stack }}</p> @endif
    <div class="desc">{!! nl2br(e($project->description)) !!}</div>
    <p class="links">
      @if($project->live_url) <a target="_blank" rel="noopener" href="{{ $project->live_url }}">View Live</a> @endif
      @if($project->repo_url) <a target="_blank" rel="noopener" href="{{ $project->repo_url }}">View Code</a> @endif
    </p>
  </article>
@endsection
