@extends('layout')

@section('title', 'About Me')

@section('content')
<div class="about-container">

 
  <section class="about-intro">
    <h2 class="ab1">Who I'm?</h2>
    <p>I’m a <strong>Data Analyst</strong> passionate about turning raw data into valuable insights. With experience in Python, SQL, Excel, and data visualization tools, I focus on delivering clear and actionable stories from complex datasets.</p>
  </section>

 
  <section class="about-skills">
    <h3>My Skills</h3>
    <ul class="skills-list">
      <li><span>📊 Data Visualization</span></li>
      <li><span>🐍 Python (Pandas, Matplotlib, Seaborn)</span></li>
      <li><span>🧮 SQL Queries & Joins</span></li>
      <li><span>📈 Excel Dashboards & Pivot Tables</span></li>
      <li><span>📚 Data Cleaning & Wrangling</span></li>
      <li><span>🧠 Insightful Data Storytelling</span></li>
    </ul>
  </section>

  
  <section class="about-timeline">
    <h3>My Journey</h3>
    <ul class="timeline">
      <li><strong>2022</strong> - Started working with Excel and SQL in academic projects.</li>
      <li><strong>2023</strong> - Completed certification in Python for Data Analysis.</li>
      <li><strong>2024</strong> - Built multiple dashboards and case studies with real datasets.</li>
      <li><strong>2025</strong> - Currently working on advanced ML projects and blogging insights.</li>
    </ul>
  </section>


  <section class="about-fun">
    <h3>Fun Facts</h3>
    <p>I love using data to tell stories. <br> I read data blogs every week. <br> I enjoy collaborating with teams remotely across the world.</p>
  </section>

</div>
@endsection
