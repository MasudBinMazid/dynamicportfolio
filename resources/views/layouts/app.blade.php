<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>@yield('title','Masud Portfolio')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
  @stack('styles')
</head>
<body>
  <nav class="navbar">
    <a href="{{ route('home') }}">Home</a>
    <a href="{{ route('about') }}">About</a>
    <a href="{{ route('skills') }}">Skills</a>
    <a href="{{ route('projects') }}">Projects</a>
    <a href="{{ route('contact') }}">Contact</a>
    @if(!empty($contactInfo?->cv_url))
      <a class="btn btn-cv" href="{{ $contactInfo->cv_url }}" target="_blank" rel="noopener">Download CV</a>
    @endif
  </nav>

  <main class="container">
    @if(session('success')) <div class="alert success">{{ session('success') }}</div> @endif
    @yield('content')
  </main>

  <footer class="footer">© {{ date('Y') }} Masud</footer>

  @stack('scripts')
</body>
</html>
