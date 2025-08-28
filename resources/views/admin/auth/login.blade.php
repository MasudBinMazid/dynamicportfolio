<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="{{ asset('assets/css/admin.css') }}" rel="stylesheet">
</head>
<body class="admin-login">
  <div class="card">
    <h1>Admin Login</h1>
    @if(session('error')) <div class="alert">{{ session('error') }}</div> @endif
    @if($errors->any()) <div class="alert">{{ $errors->first() }}</div> @endif
    <form method="POST" action="{{ route('admin.login.post') }}">
      @csrf
      <label>Email</label>
      <input type="email" name="email" value="{{ old('email','admin@masud.dev') }}" required>
      <label>Password</label>
      <input type="password" name="password" required>
      <label><input type="checkbox" name="remember"> Remember me</label>
      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>
