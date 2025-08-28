<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>@yield('title','Masud Portfolio')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
  
  <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
  @stack('styles')
</head>
<body>
  <nav class="navbar">
    <div class="navbar-brand">
      <a href="{{ route('home') }}">Masud</a>
    </div>
    <div class="navbar-nav">
      <a href="{{ route('home') }}">Home</a>
      <a href="{{ route('about') }}">About</a>
      <a href="{{ route('skills') }}">Skills</a>
      <a href="{{ route('projects') }}">Projects</a>
      <a href="{{ route('contact') }}">Contact</a>
    </div>
    @if(!empty($contactInfo?->cv_url))
      <a class="btn btn-cv" href="{{ $contactInfo->cv_url }}" target="_blank" rel="noopener">Download CV</a>
    @endif
  </nav>

  <main class="container">
    @if(session('success')) <div class="alert success">{{ session('success') }}</div> @endif
    @yield('content')
  </main>

  <footer class="footer">© {{ date('Y') }} Masud</footer>

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
      const navLinks = document.querySelectorAll('.navbar-nav a');
      
      navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath || 
           (currentPath === '/' && link.getAttribute('href').includes('home'))) {
          link.classList.add('active');
        }
      });
    });
  </script>

  @stack('scripts')
</body>
</html>
