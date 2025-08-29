<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>@yield('title','Admin') - Portfolio Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="{{ asset('assets/css/admin.css') }}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  @stack('styles')
</head>
<body>
  <header class="admin-header">
    <div class="header-brand">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
        <line x1="12" y1="22.08" x2="12" y2="12"></line>
      </svg>
      <span>Portfolio Admin</span>
    </div>
    
    <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="3" y1="6" x2="21" y2="6"></line>
        <line x1="3" y1="12" x2="21" y2="12"></line>
        <line x1="3" y1="18" x2="21" y2="18"></line>
      </svg>
    </button>
    
    <nav class="header-nav" id="headerNav">
      <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="7" height="7"></rect>
          <rect x="14" y="3" width="7" height="7"></rect>
          <rect x="14" y="14" width="7" height="7"></rect>
          <rect x="3" y="14" width="7" height="7"></rect>
        </svg>
        Dashboard
      </a>
      <a href="{{ route('admin.projects.index') }}" class="{{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
          <polyline points="2 17 12 22 22 17"></polyline>
          <polyline points="2 12 12 17 22 12"></polyline>
        </svg>
        Projects
      </a>
      <a href="{{ route('admin.contact_info.edit') }}" class="{{ request()->routeIs('admin.contact_info.*') ? 'active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
          <polyline points="22,6 12,13 2,6"></polyline>
        </svg>
        Contact Info
      </a>
      <a href="{{ route('admin.messages.index') }}" class="{{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
        Messages
      </a>
      <a href="{{ route('admin.chat.index') }}" class="{{ request()->routeIs('admin.chat.*') ? 'active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14 9a2 2 0 0 1-2 2H6l-4 4V4c0-1.1.9-2 2-2h8a2 2 0 0 1 2 2v5Z"></path>
          <path d="M18 9h2a2 2 0 0 1 2 2v11l-4-4h-6a2 2 0 0 1-2-2v-1"></path>
        </svg>
        Live Chat
      </a>
      
      <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
        @csrf
        <button type="submit" class="logout-btn">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
            <polyline points="16 17 21 12 16 7"></polyline>
            <line x1="21" y1="12" x2="9" y2="12"></line>
          </svg>
          Logout
        </button>
      </form>
    </nav>
  </header>

  <main class="admin-container">
    @if(session('success')) 
      <div class="alert success">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
          <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
        {{ session('success') }}
      </div> 
    @endif
    
    @yield('content')
  </main>
  
  @stack('scripts')
  
  <script>
    function toggleMobileMenu() {
      const headerNav = document.getElementById('headerNav');
      headerNav.classList.toggle('mobile-open');
    }
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
      const headerNav = document.getElementById('headerNav');
      const mobileToggle = document.querySelector('.mobile-menu-toggle');
      
      if (!headerNav.contains(event.target) && !mobileToggle.contains(event.target)) {
        headerNav.classList.remove('mobile-open');
      }
    });
    
    // Close mobile menu when window is resized to desktop
    window.addEventListener('resize', function() {
      if (window.innerWidth > 768) {
        document.getElementById('headerNav').classList.remove('mobile-open');
      }
    });
  </script>
  
  <style>
    .header-brand {
      display: flex;
      align-items: center;
      gap: 12px;
      font-weight: 600;
      font-size: 18px;
      color: var(--primary);
    }
    
    .mobile-menu-toggle {
      display: none;
      background: none;
      border: none;
      color: var(--text);
      cursor: pointer;
      padding: 8px;
      border-radius: 8px;
      transition: var(--transition);
    }
    
    .mobile-menu-toggle:hover {
      background: var(--secondary);
    }
    
    .header-nav {
      display: flex;
      align-items: center;
      gap: 8px;
      flex: 1;
      justify-content: center;
    }
    
    .header-nav a {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 8px 16px;
      border-radius: 8px;
      transition: var(--transition);
      position: relative;
      text-decoration: none;
      color: var(--text);
      font-weight: 500;
    }
    
    .header-nav a:hover {
      background: var(--secondary);
    }
    
    .header-nav a.active {
      background: var(--primary);
      color: white;
    }
    
    .header-nav a.active svg {
      stroke: white;
    }
    
    .logout-form {
      display: inline;
    }
    
    .logout-btn {
      display: flex;
      align-items: center;
      gap: 8px;
      background: none;
      border: none;
      color: var(--text);
      font-weight: 500;
      padding: 8px 16px;
      border-radius: 8px;
      cursor: pointer;
      transition: var(--transition);
    }
    
    .logout-btn:hover {
      background: var(--secondary);
    }
    
    .alert {
      display: flex;
      align-items: center;
      gap: 12px;
      animation: slideInDown 0.4s ease;
    }
    
    @keyframes slideInDown {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    @media (max-width: 768px) {
      .admin-header {
        flex-wrap: wrap;
        position: relative;
      }
      
      .mobile-menu-toggle {
        display: block;
        order: 2;
      }
      
      .header-brand {
        order: 1;
        flex: 1;
      }
      
      .header-nav {
        order: 3;
        width: 100%;
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid var(--border);
        display: none;
      }
      
      .header-nav.mobile-open {
        display: flex;
      }
      
      .header-nav a, .header-nav .logout-btn {
        width: 100%;
        justify-content: flex-start;
        padding: 12px 16px;
      }
      
      .header-nav .logout-form {
        width: 100%;
      }
    }
    
    @media (max-width: 480px) {
      .admin-header {
        padding: 12px 16px;
      }
      
      .header-brand span {
        font-size: 16px;
      }
      
      .admin-container {
        padding: 0 16px;
        margin: 16px auto;
      }
    }
  </style>
</body>
</html>
