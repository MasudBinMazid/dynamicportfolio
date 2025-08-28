@extends('layouts.app')
@section('title','Skills | Masud')
@push('styles') 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('assets/css/skills.css') }}"> 
@endpush

@section('content')
<div class="skills-container">
  <!-- Page Header -->
  <section class="skills-header">
    <h1 class="skills-title">My <span class="highlight">Skills</span></h1>
    <p class="skills-subtitle">Technologies and tools I work with to build amazing projects</p>
  </section>

  <!-- Skills Grid -->
  <section class="skills-grid">
    
    <!-- Data Analysis -->
    <div class="skill-category" data-category="data">
      <h2 class="category-title">
        <i class="fas fa-chart-bar category-icon"></i>
        Data Analysis
      </h2>
      <div class="skills-list">
        <div class="skill-item" data-skill="excel">
          <div class="skill-icon">
            <i class="fas fa-file-excel"></i>
          </div>
          <div class="skill-info">
            <h3 class="skill-name">MS Excel</h3>
            <div class="skill-progress">
              <div class="progress-bar" data-progress="90"></div>
            </div>
            <span class="skill-level">Advanced</span>
          </div>
        </div>

        <div class="skill-item" data-skill="powerbi">
          <div class="skill-icon">
            <i class="fas fa-chart-pie"></i>
          </div>
          <div class="skill-info">
            <h3 class="skill-name">Power BI</h3>
            <div class="skill-progress">
              <div class="progress-bar" data-progress="85"></div>
            </div>
            <span class="skill-level">Advanced</span>
          </div>
        </div>

        <div class="skill-item" data-skill="python">
          <div class="skill-icon">
            <i class="fab fa-python"></i>
          </div>
          <div class="skill-info">
            <h3 class="skill-name">Python</h3>
            <div class="skill-progress">
              <div class="progress-bar" data-progress="80"></div>
            </div>
            <span class="skill-level">Intermediate</span>
          </div>
        </div>

        <div class="skill-item" data-skill="matplotlib">
          <div class="skill-icon">
            <i class="fas fa-chart-line"></i>
          </div>
          <div class="skill-info">
            <h3 class="skill-name">Matplotlib</h3>
            <div class="skill-progress">
              <div class="progress-bar" data-progress="75"></div>
            </div>
            <span class="skill-level">Intermediate</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Backend Development -->
    <div class="skill-category" data-category="backend">
      <h2 class="category-title">
        <i class="fas fa-server category-icon"></i>
        Backend Development
      </h2>
      <div class="skills-list">
        <div class="skill-item" data-skill="laravel">
          <div class="skill-icon">
            <i class="fab fa-laravel"></i>
          </div>
          <div class="skill-info">
            <h3 class="skill-name">Laravel</h3>
            <div class="skill-progress">
              <div class="progress-bar" data-progress="88"></div>
            </div>
            <span class="skill-level">Advanced</span>
          </div>
        </div>

        <div class="skill-item" data-skill="php">
          <div class="skill-icon">
            <i class="fab fa-php"></i>
          </div>
          <div class="skill-info">
            <h3 class="skill-name">PHP</h3>
            <div class="skill-progress">
              <div class="progress-bar" data-progress="85"></div>
            </div>
            <span class="skill-level">Advanced</span>
          </div>
        </div>

        <div class="skill-item" data-skill="mysql">
          <div class="skill-icon">
            <i class="fas fa-database"></i>
          </div>
          <div class="skill-info">
            <h3 class="skill-name">MySQL</h3>
            <div class="skill-progress">
              <div class="progress-bar" data-progress="82"></div>
            </div>
            <span class="skill-level">Advanced</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Frontend Development -->
    <div class="skill-category" data-category="frontend">
      <h2 class="category-title">
        <i class="fas fa-code category-icon"></i>
        Frontend Development
      </h2>
      <div class="skills-list">
        <div class="skill-item" data-skill="html">
          <div class="skill-icon">
            <i class="fab fa-html5"></i>
          </div>
          <div class="skill-info">
            <h3 class="skill-name">HTML5</h3>
            <div class="skill-progress">
              <div class="progress-bar" data-progress="95"></div>
            </div>
            <span class="skill-level">Expert</span>
          </div>
        </div>

        <div class="skill-item" data-skill="css">
          <div class="skill-icon">
            <i class="fab fa-css3-alt"></i>
          </div>
          <div class="skill-info">
            <h3 class="skill-name">CSS3</h3>
            <div class="skill-progress">
              <div class="progress-bar" data-progress="90"></div>
            </div>
            <span class="skill-level">Advanced</span>
          </div>
        </div>

        <div class="skill-item" data-skill="javascript">
          <div class="skill-icon">
            <i class="fab fa-js-square"></i>
          </div>
          <div class="skill-info">
            <h3 class="skill-name">JavaScript</h3>
            <div class="skill-progress">
              <div class="progress-bar" data-progress="78"></div>
            </div>
            <span class="skill-level">Intermediate</span>
          </div>
        </div>
      </div>
    </div>

  </section>

  <!-- Skills Summary -->
  <section class="skills-summary">
    <div class="summary-cards">
      <div class="summary-card">
        <div class="card-icon">
          <i class="fas fa-chart-bar"></i>
        </div>
        <div class="card-content">
          <h3 class="card-number">4</h3>
          <p class="card-label">Data Analysis Tools</p>
        </div>
      </div>
      
      <div class="summary-card">
        <div class="card-icon">
          <i class="fas fa-server"></i>
        </div>
        <div class="card-content">
          <h3 class="card-number">3</h3>
          <p class="card-label">Backend Technologies</p>
        </div>
      </div>
      
      <div class="summary-card">
        <div class="card-icon">
          <i class="fas fa-code"></i>
        </div>
        <div class="card-content">
          <h3 class="card-number">3</h3>
          <p class="card-label">Frontend Languages</p>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection

@push('scripts') <script src="{{ asset('assets/js/skills.js') }}" defer></script> @endpush
