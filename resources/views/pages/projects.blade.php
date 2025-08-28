@extends('layouts.app')
@section('title','Projects | Masud')
@push('styles') <link rel="stylesheet" href="{{ asset('assets/css/projects.css') }}"> @endpush

@section('content')
  <h1>Projects</h1>

  <div class="projects-grid">
    @foreach($projects as $p)
      <article class="project-card">
        @if($p->image_url)
          <img src="{{ $p->image_url }}" alt="{{ $p->title }}">
        @endif

        <h3><a href="{{ route('projects.show',$p->slug) }}">{{ $p->title }}</a></h3>
        @if($p->tech_stack)
          <p class="stack">{{ $p->tech_stack }}</p>
        @endif
        <p>{{ Str::limit($p->description,120) }}</p>
        <p class="links">
          @if($p->live_url) <a target="_blank" rel="noopener" href="{{ $p->live_url }}">Live</a> @endif
          @if($p->repo_url) <a target="_blank" rel="noopener" href="{{ $p->repo_url }}">Code</a> @endif
        </p>
      </article>
    @endforeach
  </div>

  {{ $projects->links() }}
@endsection

@push('scripts') <script src="{{ asset('assets/js/projects.js') }}" defer></script> @endpush
