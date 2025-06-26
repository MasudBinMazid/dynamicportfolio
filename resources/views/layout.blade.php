<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title')</title>
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
</head>
<body>
  <header>
  <nav class="navbar">
    <div class="navbar-brand">
      <a href="{{ url('/') }}">Masud<span>Portfolio</span></a>
    </div>
    <div class="navbar-toggle" id="menu-toggle">&#9776;</div>
    <ul class="navbar-links" id="nav-links">
      <li><a href="{{ url('/') }}">Home</a></li>
      <li><a href="{{ url('/about') }}">About</a></li>
      <li><a href="{{ url('/blog') }}">Blog</a></li>
      <li><a href="{{ url('/subscribe') }}">Subscribe</a></li>
      <li><a href="{{ url('/projects') }}">Projects</a></li>
      <li><a href="{{ url('/contact') }}">Contact</a></li>
    </ul>
  </nav>
</header>

  <main>
    @yield('content')
  </main>

<footer class="footer">
  <div class="footer-container">
    <div class="footer-brand">
      <h3>Masud<span>Portfolio</span></h3>
      <p>Data-driven insights, clean design, and powerful storytelling.</p>
    </div>

  <div class="footer-bottom">
    <p>&copy; 2025 Masud. All rights reserved.</p>
  </div>
</footer>


  <script src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>

