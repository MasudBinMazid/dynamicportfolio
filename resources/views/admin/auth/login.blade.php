<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Login | Masud Portfolio</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <link href="{{ asset('assets/css/admin-login.css') }}" rel="stylesheet">
</head>
<body class="admin-login">
  <!-- Background Elements -->
  <div class="login-background">
    <div class="floating-shapes">
      <div class="shape shape-1"></div>
      <div class="shape shape-2"></div>
      <div class="shape shape-3"></div>
    </div>
    
    <!-- Animated Particles -->
    <div class="particles-container">
      <div class="particle particle-1"></div>
      <div class="particle particle-2"></div>
      <div class="particle particle-3"></div>
      <div class="particle particle-4"></div>
      <div class="particle particle-5"></div>
    </div>
  </div>

  <!-- Login Container -->
  <div class="login-container">
    <div class="login-card">
      <!-- Logo/Header -->
      <div class="login-header">
        <div class="logo">
          <i class="fas fa-shield-alt"></i>
        </div>
        <h1>Admin Portal</h1>
        <p>Welcome back! Please sign in to your account</p>
      </div>

      <!-- Alert Messages -->
      @if(session('error'))
        <div class="alert alert-error">
          <i class="fas fa-exclamation-circle"></i>
          {{ session('error') }}
        </div>
      @endif
      
      @if($errors->any())
        <div class="alert alert-error">
          <i class="fas fa-exclamation-circle"></i>
          {{ $errors->first() }}
        </div>
      @endif

      <!-- Login Form -->
      <form method="POST" action="{{ route('admin.login.post') }}" class="login-form">
        @csrf
        
        <div class="form-group">
          <label for="email" class="form-label">
            <i class="fas fa-envelope"></i>
            Email Address
          </label>
          <input 
            type="email" 
            id="email" 
            name="email" 
            class="form-input" 
            value="{{ old('email','admin@masud.dev') }}" 
            required
            placeholder="Enter your email address"
          >
        </div>

        <div class="form-group">
          <label for="password" class="form-label">
            <i class="fas fa-lock"></i>
            Password
          </label>
          <div class="password-input-container">
            <input 
              type="password" 
              id="password" 
              name="password" 
              class="form-input" 
              required
              placeholder="Enter your password"
            >
            <button type="button" class="password-toggle" onclick="togglePassword()">
              <i class="fas fa-eye" id="password-eye"></i>
            </button>
          </div>
        </div>

        <div class="form-options">
          <label class="checkbox-container">
            <input type="checkbox" name="remember">
            <span class="checkmark"></span>
            Remember me
          </label>
        </div>

        <button type="submit" class="login-btn">
          <span class="btn-content">
            <i class="fas fa-sign-in-alt"></i>
            <span class="btn-text">Sign In</span>
          </span>
          <div class="btn-loader">
            <i class="fas fa-spinner"></i>
          </div>
        </button>
      </form>

      <!-- Footer -->
      <div class="login-footer">
        <p>&copy; {{ date('Y') }} Masud Portfolio. All rights reserved.</p>
      </div>
    </div>

    <!-- Side Info Panel -->
    <div class="info-panel">
      <div class="info-content">
        <h2>Portfolio Management</h2>
        <p>Manage your projects, skills, and contact information with ease.</p>
        
        <div class="features-list">
          <div class="feature-item">
            <i class="fas fa-project-diagram"></i>
            <span>Project Management</span>
          </div>
          <div class="feature-item">
            <i class="fas fa-cogs"></i>
            <span>Skills Administration</span>
          </div>
          <div class="feature-item">
            <i class="fas fa-envelope"></i>
            <span>Contact Management</span>
          </div>
          <div class="feature-item">
            <i class="fas fa-chart-line"></i>
            <span>Analytics Dashboard</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Password toggle functionality
    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const passwordEye = document.getElementById('password-eye');
      
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordEye.className = 'fas fa-eye-slash';
      } else {
        passwordInput.type = 'password';
        passwordEye.className = 'fas fa-eye';
      }
    }

    // Form submission loading state
    document.querySelector('.login-form').addEventListener('submit', function(e) {
      const button = document.querySelector('.login-btn');
      button.classList.add('loading');
    });

    // Input focus animations
    document.querySelectorAll('.form-input').forEach(input => {
      input.addEventListener('focus', function() {
        this.parentElement.classList.add('focused');
      });
      
      input.addEventListener('blur', function() {
        if (!this.value) {
          this.parentElement.classList.remove('focused');
        }
      });
    });
  </script>
</body>
</html>
