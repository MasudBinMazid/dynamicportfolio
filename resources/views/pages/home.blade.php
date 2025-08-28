@extends('layouts.app')
@section('title','Home | Masud')
@push('styles') 
  <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
  <section class="hero">
    <div class="hero-content">
      <img src="{{ asset('assets/image/Profile1.jpg') }}" alt="Masud Bin Mazid" class="profile-img">
      <div>
        <h1 class="hero-title">Hi, I’m <span class="highlight">Masud Rana Mamun</span> <span class="wave">👋</span></h1>
        <p class="hero-subtitle">"Final-year CSE student passionate about data analysis and crafting web apps with Laravel PHP."</p>
        <div class="hero-actions">
          <a href="{{ route('projects') }}" class="btn btn-primary">View Projects</a>
          <a href="{{ route('contact') }}" class="btn btn-secondary">Hire Me</a>
        </div>
        <div class="hero-social">
          <a href="https://github.com/MasudBinMazid" target="_blank" rel="noopener" aria-label="GitHub"><i class="fab fa-github"></i></a>
          <a href="https://linkedin.com/in/masudbinmazid" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
          <a href="mailto:masudranamamun222@gmail.com" aria-label="Email"><i class="fas fa-envelope"></i></a>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts') <script src="{{ asset('assets/js/home.js') }}" defer></script> @endpush
