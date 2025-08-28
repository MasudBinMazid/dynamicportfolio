@extends('layouts.app')
@section('title','Contact | Masud')
@push('styles') 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('assets/css/contact.css') }}"> 
@endpush

@section('content')
<div class="contact-container">
  <!-- Background Elements -->
  <div class="contact-background">
    <div class="floating-shapes">
      <div class="shape shape-1"></div>
      <div class="shape shape-2"></div>
      <div class="shape shape-3"></div>
      <div class="shape shape-4"></div>
    </div>
  </div>

  <!-- Contact Header -->
  <div class="contact-header">
    <h1 class="contact-title">Get In <span class="highlight">Touch</span></h1>
    <p class="contact-subtitle">Let's discuss your next project or data analysis opportunity</p>
  </div>

  <div class="contact-layout">
    <!-- Contact Information Section -->
    <section class="contact-info">
      <div class="info-card">
        <div class="info-header">
          <div class="info-icon">
            <i class="fas fa-address-card"></i>
          </div>
          <h2>Contact Information</h2>
        </div>
        
        <div class="info-content">
          <ul class="contact-list">
            @if($info?->email)   
              <li class="contact-item">
                <div class="item-icon">
                  <i class="fas fa-envelope"></i>
                </div>
                <div class="item-content">
                  <span class="item-label">Email</span>
                  <a href="mailto:{{ $info->email }}" class="item-value">{{ $info->email }}</a>
                </div>
              </li> 
            @endif
            
            @if($info?->phone)   
              <li class="contact-item">
                <div class="item-icon">
                  <i class="fas fa-phone-alt"></i>
                </div>
                <div class="item-content">
                  <span class="item-label">Phone</span>
                  <a href="tel:{{ $info->phone }}" class="item-value">{{ $info->phone }}</a>
                </div>
              </li> 
            @endif
            
            @if($info?->address) 
              <li class="contact-item">
                <div class="item-icon">
                  <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="item-content">
                  <span class="item-label">Address</span>
                  <span class="item-value">{{ $info->address }}</span>
                </div>
              </li> 
            @endif
          </ul>

          <!-- Social Links -->
          @if($info?->github || $info?->linkedin || $info?->twitter)
            <div class="social-section">
              <h3 class="social-title">
                <i class="fas fa-share-alt"></i>
                Connect With Me
              </h3>
              <div class="social-links">
                @if($info?->github)   
                  <a href="{{ $info->github }}" target="_blank" class="social-link github" title="GitHub">
                    <i class="fab fa-github"></i>
                    <span>GitHub</span>
                  </a> 
                @endif
                @if($info?->linkedin) 
                  <a href="{{ $info->linkedin }}" target="_blank" class="social-link linkedin" title="LinkedIn">
                    <i class="fab fa-linkedin-in"></i>
                    <span>LinkedIn</span>
                  </a> 
                @endif
                @if($info?->twitter)  
                  <a href="{{ $info->twitter }}" target="_blank" class="social-link twitter" title="Twitter">
                    <i class="fab fa-twitter"></i>
                    <span>Twitter</span>
                  </a> 
                @endif
              </div>
            </div>
          @endif
        </div>
      </div>

      <!-- Map Section -->
      @if($info?->map_embed) 
        <div class="map-card">
          <div class="map-header">
            <div class="map-icon">
              <i class="fas fa-map"></i>
            </div>
            <h3>Location</h3>
          </div>
          <div class="map-container">
            {!! $info->map_embed !!}
          </div>
        </div> 
      @endif
    </section>

    <!-- Contact Form Section -->
    <section class="contact-form-section">
      <div class="form-card">
        <div class="form-header">
          <div class="form-icon">
            <i class="fas fa-paper-plane"></i>
          </div>
          <h2>Send me a message</h2>
          <p>I'd love to hear from you. Send me a message and I'll respond as soon as possible.</p>
        </div>

        <form method="POST" action="{{ route('contact.send') }}" class="contact-form">
          @csrf
          
          <div class="form-row">
            <div class="form-group">
              <label for="name" class="form-label">
                <i class="fas fa-user"></i>
                Name
              </label>
              <input 
                type="text" 
                id="name" 
                name="name" 
                class="form-input" 
                value="{{ old('name') }}" 
                required
                placeholder="Your full name"
              >
            </div>

            <div class="form-group">
              <label for="email" class="form-label">
                <i class="fas fa-envelope"></i>
                Email
              </label>
              <input 
                type="email" 
                id="email" 
                name="email" 
                class="form-input" 
                value="{{ old('email') }}" 
                required
                placeholder="your.email@example.com"
              >
            </div>
          </div>

          <div class="form-group">
            <label for="subject" class="form-label">
              <i class="fas fa-tag"></i>
              Subject
            </label>
            <input 
              type="text" 
              id="subject" 
              name="subject" 
              class="form-input" 
              value="{{ old('subject') }}"
              placeholder="What's this about?"
            >
          </div>

          <div class="form-group">
            <label for="message" class="form-label">
              <i class="fas fa-comment-alt"></i>
              Message
            </label>
            <textarea 
              id="message" 
              name="message" 
              class="form-textarea" 
              rows="6" 
              required
              placeholder="Tell me about your project or question..."
            >{{ old('message') }}</textarea>
          </div>

          <button type="submit" class="submit-btn">
            <span class="btn-content">
              <i class="fas fa-paper-plane"></i>
              <span class="btn-text">Send Message</span>
            </span>
            <div class="btn-loader">
              <i class="fas fa-spinner"></i>
            </div>
          </button>
        </form>

        <!-- Success/Error Messages -->
        @if(session('success'))
          <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
          </div>
        @endif

        @if($errors->any()) 
          <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ $errors->first() }}
          </div> 
        @endif
      </div>
    </section>
  </div>

  <!-- Response Time Section -->
  <div class="response-info">
    <div class="response-card">
      <div class="response-stats">
        <div class="stat-item">
          <div class="stat-icon">
            <i class="fas fa-clock"></i>
          </div>
          <div class="stat-content">
            <span class="stat-number">24h</span>
            <span class="stat-label">Response Time</span>
          </div>
        </div>
        
        <div class="stat-item">
          <div class="stat-icon">
            <i class="fas fa-comments"></i>
          </div>
          <div class="stat-content">
            <span class="stat-number">100%</span>
            <span class="stat-label">Response Rate</span>
          </div>
        </div>
        
        <div class="stat-item">
          <div class="stat-icon">
            <i class="fas fa-handshake"></i>
          </div>
          <div class="stat-content">
            <span class="stat-number">Ready</span>
            <span class="stat-label">To Collaborate</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts') <script src="{{ asset('assets/js/contact.js') }}" defer></script> @endpush
