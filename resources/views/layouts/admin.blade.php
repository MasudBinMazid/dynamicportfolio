<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>@yield('title','Admin')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="{{ asset('assets/css/admin.css') }}" rel="stylesheet">
</head>
<body>
  <header class="admin-header">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('admin.projects.index') }}">Projects</a>
    <a href="{{ route('admin.contact_info.edit') }}">Contact Info</a>
    <a href="{{ route('admin.messages.index') }}">Messages</a>
    <form method="POST" action="{{ route('admin.logout') }}" style="display:inline">@csrf<button>Logout</button></form>
  </header>

  <main class="admin-container">
    @if(session('success')) <div class="alert success">{{ session('success') }}</div> @endif
    @yield('content')
  </main>
</body>
</html>
