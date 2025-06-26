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
      <ul>
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

  <footer>
    <p>&copy; 2025 Masud. All rights reserved.</p>
  </footer>

  <script src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>
