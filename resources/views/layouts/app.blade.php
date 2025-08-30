<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>@yield('title','Masud Portfolio')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
  
  <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/chat.css') }}" rel="stylesheet">
  @stack('styles')
</head>
<body>
  <nav class="navbar">
    <div class="navbar-left">
      <div class="navbar-brand">
        <a href="{{ route('home') }}">Portfolio-Masud</a>
      </div>
      <!-- Download CV for Mobile - shows only on mobile -->
      @if(!empty($contactInfo?->cv_url))
        <a class="btn btn-cv mobile-cv" href="{{ $contactInfo->cv_url }}" target="_blank" rel="noopener">Download CV</a>
      @endif
    </div>
    
    <!-- Desktop Navigation -->
    <div class="navbar-nav desktop-nav">
      <a href="{{ route('home') }}">Home</a>
      <a href="{{ route('about') }}">About</a>
      <a href="{{ route('skills') }}">Skills</a>
      <a href="{{ route('projects') }}">Projects</a>
      <a href="{{ route('contact') }}">Contact</a>
    </div>
    
    <div class="navbar-right">
      <!-- Download CV for Desktop - shows only on desktop -->
      @if(!empty($contactInfo?->cv_url))
        <a class="btn btn-cv desktop-cv" href="{{ $contactInfo->cv_url }}" target="_blank" rel="noopener">Download CV</a>
      @endif
      
      <!-- Mobile Menu Button -->
      <button class="mobile-menu-btn" id="mobileMenuBtn">
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
      </button>
    </div>
  </nav>

  <!-- Mobile Sidebar -->
  <div class="mobile-sidebar" id="mobileSidebar">
    <div class="mobile-sidebar-content">
      <div class="sidebar-header">
        <h3>Menu</h3>
        <button class="sidebar-close" id="sidebarClose">&times;</button>
      </div>
      <nav class="sidebar-nav">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('about') }}">About</a>
        <a href="{{ route('skills') }}">Skills</a>
        <a href="{{ route('projects') }}">Projects</a>
        <a href="{{ route('contact') }}">Contact</a>
      </nav>
    </div>
  </div>
  
  <!-- Mobile Sidebar Overlay -->
  <div class="mobile-sidebar-overlay" id="mobileSidebarOverlay"></div>

  <main class="container">
    @if(session('success')) <div class="alert success">{{ session('success') }}</div> @endif
    @yield('content')
  </main>

  <footer class="footer">© {{ date('Y') }} Masud</footer>

  <!-- Chat Widget -->
  @include('components.chat-widget')

  <script>
    // Add scrolled class to navbar when scrolling
    window.addEventListener('scroll', function() {
      const navbar = document.querySelector('.navbar');
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });

    // Add active class to current page nav link
    document.addEventListener('DOMContentLoaded', function() {
      const currentPath = window.location.pathname;
      const navLinks = document.querySelectorAll('.navbar-nav a, .sidebar-nav a');
      
      navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath || 
           (currentPath === '/' && link.getAttribute('href').includes('home'))) {
          link.classList.add('active');
        }
      });

      // Mobile sidebar functionality
      const mobileMenuBtn = document.getElementById('mobileMenuBtn');
      const mobileSidebar = document.getElementById('mobileSidebar');
      const mobileSidebarOverlay = document.getElementById('mobileSidebarOverlay');
      const sidebarClose = document.getElementById('sidebarClose');

      // Open sidebar
      mobileMenuBtn.addEventListener('click', function() {
        mobileSidebar.classList.add('active');
        mobileSidebarOverlay.classList.add('active');
        mobileMenuBtn.classList.add('active');
        document.body.style.overflow = 'hidden';
      });

      // Close sidebar
      function closeSidebar() {
        mobileSidebar.classList.remove('active');
        mobileSidebarOverlay.classList.remove('active');
        mobileMenuBtn.classList.remove('active');
        document.body.style.overflow = '';
      }

      sidebarClose.addEventListener('click', closeSidebar);
      mobileSidebarOverlay.addEventListener('click', closeSidebar);

      // Close sidebar when clicking on nav links
      const sidebarNavLinks = document.querySelectorAll('.sidebar-nav a');
      sidebarNavLinks.forEach(link => {
        link.addEventListener('click', closeSidebar);
      });

      // Handle escape key
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && mobileSidebar.classList.contains('active')) {
          closeSidebar();
        }
      });
    });
  </script>

  @stack('scripts')
</body>
</html>
