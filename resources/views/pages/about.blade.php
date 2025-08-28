@extends('layouts.app')
@section('title','About | Masud')
@push('styles') 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('assets/css/about.css') }}"> 
@endpush

@section('content')
<div class="about-container">
  <!-- Hero Section -->
  <section class="about-hero">
    <div class="profile-section">
      <img src="{{ asset('assets/image/Profile.jpg') }}" alt="Masud Rahman" class="about-profile-img">
      <div class="hero-content">
        <h1 class="about-title"> About <span class="highlight">Masud Rana Mamun</span></h1>
        <p class="about-subtitle">Aspiring Data Analyst & PHP Laravel Developer</p>
        <div class="stats-grid">
          <div class="stat-item">
            <span class="stat-number">1+</span>
            <span class="stat-label">Years Experience</span>
          </div>
          <div class="stat-item">
            <span class="stat-number">3+</span>
            <span class="stat-label">Projects Completed</span>
          </div>
          <div class="stat-item">
            <span class="stat-number">10+</span>
            <span class="stat-label">Technologies</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- About Me Section -->
  <section class="about-content">
    <div class="content-card">
      <h2 class="section-title">Who I Am</h2>
      <p class="about-text">
        I'm a passionate data analyst with a strong foundation in PHP Laravel development. My journey in technology began with web development, where I honed my skills in creating robust, scalable applications using PHP and Laravel framework. Over time, I discovered my fascination with data analytics and have been transitioning into the field of data science.
      </p>
      <p class="about-text">
        My unique combination of web development expertise and growing data analysis skills allows me to build comprehensive solutions that not only handle data processing but also present insights through intuitive web interfaces. I believe in the power of data-driven decision making and strive to bridge the gap between technical implementation and business intelligence.
      </p>
    </div>
  </section>

  <!-- Education Section -->
  <section class="education-section">
    <h2 class="section-title">Education Background</h2>
    <div class="education-timeline">
      
      <!-- University -->
      <div class="education-item">
        <div class="education-icon">
          <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="education-content">
          <h3 class="degree-title">B.Sc. in Computer Science & Engineering</h3>
          <p class="institution">Daffodil International University</p>
          <div class="education-details">
            <span class="duration">2022 - Now</span>
            <span class="gpa">CGPA: 3.6/4.00</span>
          </div>
          <p class="education-desc">
            Focused on software engineering, database systems, and Web engineering. Completed capstone projects for event booking platform, Fake URL Detecting platform using Laravel and Python.
          </p>
        </div>
      </div>

      <!-- College -->
      <div class="education-item">
        <div class="education-icon">
          <i class="fas fa-school"></i>
        </div>
        <div class="education-content">
          <h3 class="degree-title">Higher Secondary Certificate (HSC)</h3>
          <p class="institution">Cantonment Public School & College, Rangpur</p>
          <div class="education-details">
            <span class="duration">2017 - 2019</span>
            <span class="gpa">GPA: 5.00/5.00</span>
          </div>
          <p class="education-desc">
            Science Group with focus on Mathematics, Physics, and Chemistry. Developed strong analytical and problem-solving skills that laid the foundation for my technical career.
          </p>
        </div>
      </div>

      <!-- School -->
      <div class="education-item">
        <div class="education-icon">
          <i class="fas fa-book"></i>
        </div>
        <div class="education-content">
          <h3 class="degree-title">Secondary School Certificate (SSC)</h3>
          <p class="institution">Barai Bari High School</p>
          <div class="education-details">
            <span class="duration">2015 - 2017</span>
            <span class="gpa">GPA: 5.00 (Golden A+)</span>
          </div>
          <p class="education-desc">
            Science Group with excellent performance in Mathematics and General Science. Participated in various science fairs and programming competitions.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Experience Highlights -->
  <section class="experience-section">
    <h2 class="section-title">Professional Journey</h2>
    <div class="experience-grid">
      <div class="experience-card">
        <h3>Laravel Development</h3>
        <p>Built scalable web applications with complex database relationships, authentication systems, and API integrations. Specialized in creating admin dashboards and content management systems.</p>
      </div>
      <div class="experience-card">
        <h3>Data Analysis Projects</h3>
        <p>Analyzed business data to identify trends, created automated reports, and developed data visualization dashboards. Experience with customer behavior analysis and sales forecasting.</p>
      </div>
      <div class="experience-card">
        <h3>Full-Stack Solutions</h3>
        <p>Designed and implemented end-to-end solutions combining data processing backends with user-friendly web interfaces. Integration of analytics tools with Laravel applications.</p>
      </div>
    </div>
  </section>

  <!-- Goals & Vision -->
  <section class="vision-section">
    <div class="vision-card">
      <h2 class="section-title">Goals & Vision</h2>
      <p class="vision-text">
        My goal is to become a proficient data analyst who can leverage both technical development skills and analytical thinking to solve complex business problems. I envision myself working on projects that involve big data processing, machine learning implementation, and creating actionable insights that drive business growth.
      </p>
      <p class="vision-text">
        I'm committed to continuous learning and staying updated with the latest trends in both web development and data science. My unique background allows me to understand the full lifecycle of data-driven applications, from collection and processing to presentation and decision-making.
      </p>
    </div>
  </section>
</div>
@endsection

@push('scripts') <script src="{{ asset('assets/js/about.js') }}" defer></script> @endpush
