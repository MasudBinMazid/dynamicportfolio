@extends('layouts.admin')
@section('title','Contact Info')

@push('styles')
<style>
  .page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 32px;
    flex-wrap: wrap;
    gap: 16px;
  }

  .page-title {
    display: flex;
    align-items: center;
    gap: 16px;
    margin: 0;
  }

  .page-title h1 {
    font-size: 28px;
    font-weight: 700;
    color: var(--text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-light);
    font-size: 14px;
  }

  .breadcrumb a {
    color: var(--text-light);
    text-decoration: none;
    transition: var(--transition);
  }

  .breadcrumb a:hover {
    color: var(--primary);
  }

  .form-wrapper {
    max-width: 900px;
    margin: 0 auto;
  }

  .form-intro {
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.05) 0%, rgba(79, 70, 229, 0.1) 100%);
    border: 1px solid rgba(79, 70, 229, 0.2);
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 32px;
  }

  .form-intro-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 18px;
    font-weight: 600;
    color: var(--primary);
    margin: 0 0 12px 0;
  }

  .form-intro-text {
    color: var(--text);
    margin: 0;
    line-height: 1.6;
  }

  .form-container {
    background: var(--card);
    border-radius: 16px;
    box-shadow: var(--shadow);
    overflow: hidden;
    margin-bottom: 24px;
  }

  .form-sections {
    display: grid;
    gap: 0;
  }

  .form-section {
    padding: 32px;
    border-bottom: 1px solid var(--border);
  }

  .form-section:last-child {
    border-bottom: none;
  }

  .section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid var(--secondary);
  }

  .section-title {
    font-size: 20px;
    font-weight: 600;
    color: var(--text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .section-description {
    color: var(--text-light);
    font-size: 14px;
    margin-top: 4px;
  }

  .form-grid {
    display: grid;
    gap: 24px;
    grid-template-columns: 1fr;
  }

  .form-row {
    display: grid;
    gap: 20px;
    grid-template-columns: 1fr 1fr;
  }

  .form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
    position: relative;
  }

  .form-group.full-width {
    grid-column: 1 / -1;
  }

  .form-label {
    font-weight: 600;
    color: var(--text);
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .form-input {
    padding: 14px 16px;
    border: 2px solid var(--border);
    border-radius: 12px;
    font-size: 16px;
    transition: var(--transition);
    background: var(--card);
    font-family: inherit;
  }

  .form-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    transform: translateY(-1px);
  }

  .form-input::placeholder {
    color: var(--text-light);
    font-style: italic;
  }

  .form-textarea {
    padding: 16px;
    border: 2px solid var(--border);
    border-radius: 12px;
    font-size: 16px;
    transition: var(--transition);
    background: var(--card);
    font-family: 'Monaco', 'Menlo', monospace;
    resize: vertical;
    min-height: 120px;
  }

  .form-textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
  }

  .input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-light);
    pointer-events: none;
    z-index: 1;
  }

  .input-with-icon {
    padding-left: 48px;
  }

  .input-helper {
    font-size: 12px;
    color: var(--text-light);
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .form-actions {
    background: var(--bg);
    padding: 32px;
    text-align: center;
  }

  .save-button {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 16px 32px;
    background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: var(--transition);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
  }

  .save-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
  }

  .section-basic {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.02) 0%, rgba(59, 130, 246, 0.05) 100%);
  }

  .section-social {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.02) 0%, rgba(16, 185, 129, 0.05) 100%);
  }

  .section-professional {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.02) 0%, rgba(245, 158, 11, 0.05) 100%);
  }

  .section-location {
    background: linear-gradient(135deg, rgba(139, 92, 246, 0.02) 0%, rgba(139, 92, 246, 0.05) 100%);
  }

  .preview-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--primary);
    text-decoration: none;
    margin-top: 8px;
    padding: 4px 8px;
    border-radius: 6px;
    background: rgba(79, 70, 229, 0.1);
    transition: var(--transition);
  }

  .preview-link:hover {
    background: rgba(79, 70, 229, 0.2);
    transform: translateY(-1px);
  }

  @media (max-width: 768px) {
    .form-row {
      grid-template-columns: 1fr;
    }
    
    .form-section {
      padding: 24px 20px;
    }
    
    .form-actions {
      padding: 24px 20px;
    }

    .page-header {
      flex-direction: column;
      align-items: flex-start;
    }
  }

  /* Loading animation for save button */
  .save-button.loading {
    pointer-events: none;
    opacity: 0.8;
  }

  .save-button.loading::after {
    content: '';
    width: 16px;
    height: 16px;
    border: 2px solid transparent;
    border-top: 2px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
  }

  @keyframes spin {
    to { transform: rotate(360deg); }
  }

  /* Form validation styles */
  .form-input.error {
    border-color: var(--danger);
    box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
  }

  .error-message {
    color: var(--danger);
    font-size: 12px;
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
  }
</style>
@endpush

@section('content')
  <div class="page-header">
    <div class="page-title">
      <div>
        <div class="breadcrumb">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"></polyline>
          </svg>
          <span>Contact Information</span>
        </div>
        <h1>
          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
            <polyline points="22,6 12,13 2,6"></polyline>
          </svg>
          Contact Information
        </h1>
      </div>
    </div>
  </div>

  <div class="form-wrapper">
    <div class="form-intro">
      <h2 class="form-intro-title">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"></circle>
          <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
          <line x1="12" y1="17" x2="12.01" y2="17"></line>
        </svg>
        Keep Your Contact Information Updated
      </h2>
      <p class="form-intro-text">
        This information will be displayed on your portfolio's contact page and used throughout your site. 
        Make sure to keep it current so potential clients and employers can reach you easily.
      </p>
    </div>

    <form method="POST" action="{{ route('admin.contact_info.update') }}" class="modern-form">
      @csrf
      <div class="form-container">
        <div class="form-sections">
          
          <!-- Basic Contact Information -->
          <div class="form-section section-basic">
            <div class="section-header">
              <h3 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                  <circle cx="12" cy="7" r="4"></circle>
                </svg>
                Basic Contact Details
              </h3>
            </div>
            <div class="section-description">
              Essential contact information that visitors will use to reach you directly.
            </div>
            
            <div class="form-grid">
              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Email Address</label>
                  <div style="position: relative;">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                      <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    <input 
                      type="email"
                      name="email" 
                      class="form-input input-with-icon"
                      value="{{ old('email',$info->email ?? '') }}"
                      placeholder="your@email.com"
                    >
                  </div>
                  <div class="input-helper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="12" cy="12" r="10"></circle>
                      <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                      <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                    Primary email for contact forms and inquiries
                  </div>
                </div>

                <div class="form-group">
                  <label class="form-label">Phone Number</label>
                  <div style="position: relative;">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                    <input 
                      type="tel"
                      name="phone" 
                      class="form-input input-with-icon"
                      value="{{ old('phone',$info->phone ?? '') }}"
                      placeholder="+1 (555) 123-4567"
                    >
                  </div>
                  <div class="input-helper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="12" cy="12" r="10"></circle>
                      <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                      <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                    Include country code for international visibility
                  </div>
                </div>
              </div>

              <div class="form-group full-width">
                <label class="form-label">Address</label>
                <div style="position: relative;">
                  <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                  </svg>
                  <input 
                    type="text"
                    name="address" 
                    class="form-input input-with-icon"
                    value="{{ old('address',$info->address ?? '') }}"
                    placeholder="City, State/Province, Country"
                  >
                </div>
                <div class="input-helper">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                  </svg>
                  General location (no need for exact address for privacy)
                </div>
              </div>
            </div>
          </div>

          <!-- Social Media Links -->
          <div class="form-section section-social">
            <div class="section-header">
              <h3 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                </svg>
                Social Media Profiles
              </h3>
            </div>
            <div class="section-description">
              Connect your professional social media profiles to build credibility and expand your network.
            </div>

            <div class="form-grid">
              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">GitHub Profile</label>
                  <div style="position: relative;">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path>
                    </svg>
                    <input 
                      type="url"
                      name="github" 
                      class="form-input input-with-icon"
                      value="{{ old('github',$info->github ?? '') }}"
                      placeholder="https://github.com/yourusername"
                    >
                  </div>
                  @if(old('github',$info->github ?? ''))
                    <a href="{{ old('github',$info->github ?? '') }}" target="_blank" class="preview-link">
                      <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                        <polyline points="15 3 21 3 21 9"></polyline>
                        <line x1="10" y1="14" x2="21" y2="3"></line>
                      </svg>
                      View Profile
                    </a>
                  @endif
                </div>

                <div class="form-group">
                  <label class="form-label">LinkedIn Profile</label>
                  <div style="position: relative;">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                      <rect x="2" y="9" width="4" height="12"></rect>
                      <circle cx="4" cy="4" r="2"></circle>
                    </svg>
                    <input 
                      type="url"
                      name="linkedin" 
                      class="form-input input-with-icon"
                      value="{{ old('linkedin',$info->linkedin ?? '') }}"
                      placeholder="https://linkedin.com/in/yourusername"
                    >
                  </div>
                  @if(old('linkedin',$info->linkedin ?? ''))
                    <a href="{{ old('linkedin',$info->linkedin ?? '') }}" target="_blank" class="preview-link">
                      <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                        <polyline points="15 3 21 3 21 9"></polyline>
                        <line x1="10" y1="14" x2="21" y2="3"></line>
                      </svg>
                      View Profile
                    </a>
                  @endif
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">Twitter Profile</label>
                <div style="position: relative;">
                  <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path>
                  </svg>
                  <input 
                    type="url"
                    name="twitter" 
                    class="form-input input-with-icon"
                    value="{{ old('twitter',$info->twitter ?? '') }}"
                    placeholder="https://twitter.com/yourusername"
                  >
                </div>
                @if(old('twitter',$info->twitter ?? ''))
                  <a href="{{ old('twitter',$info->twitter ?? '') }}" target="_blank" class="preview-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                      <polyline points="15 3 21 3 21 9"></polyline>
                      <line x1="10" y1="14" x2="21" y2="3"></line>
                    </svg>
                    View Profile
                  </a>
                @endif
              </div>
            </div>
          </div>

          <!-- Professional Resources -->
          <div class="form-section section-professional">
            <div class="section-header">
              <h3 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2v1M2 13h20l-10 7"></path>
                </svg>
                Professional Resources
              </h3>
            </div>
            <div class="section-description">
              Share your CV/Resume and other professional documents with potential employers and clients.
            </div>

            <div class="form-group">
              <label class="form-label">CV/Resume URL</label>
              <div style="position: relative;">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                  <polyline points="14 2 14 8 20 8"></polyline>
                  <line x1="16" y1="13" x2="8" y2="13"></line>
                  <line x1="16" y1="17" x2="8" y2="17"></line>
                  <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                <input 
                  type="url"
                  name="cv_url" 
                  class="form-input input-with-icon"
                  value="{{ old('cv_url',$info->cv_url ?? '') }}"
                  placeholder="https://drive.google.com/file/d/your-cv-link"
                >
              </div>
              <div class="input-helper">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"></circle>
                  <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                  <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                Link to Google Drive, Dropbox, or your website hosting your CV
              </div>
              @if(old('cv_url',$info->cv_url ?? ''))
                <a href="{{ old('cv_url',$info->cv_url ?? '') }}" target="_blank" class="preview-link">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                    <polyline points="15 3 21 3 21 9"></polyline>
                    <line x1="10" y1="14" x2="21" y2="3"></line>
                  </svg>
                  View CV/Resume
                </a>
              @endif
            </div>
          </div>

          <!-- Location & Map -->
          <div class="form-section section-location">
            <div class="section-header">
              <h3 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="10" r="3"></circle>
                  <path d="M12 21.7C17.3 17 20 13 20 10a8 8 0 1 0-16 0c0 3 2.7 7 8 11.7z"></path>
                </svg>
                Location & Map
              </h3>
            </div>
            <div class="section-description">
              Add a map embed to show your location on the contact page (optional but helpful for local clients).
            </div>

            <div class="form-group">
              <label class="form-label">Google Maps Embed Code</label>
              <textarea 
                name="map_embed" 
                class="form-textarea"
                rows="6"
                placeholder='<iframe src="https://www.google.com/maps/embed?pb=..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'
              >{{ old('map_embed',$info->map_embed ?? '') }}</textarea>
              <div class="input-helper">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"></circle>
                  <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                  <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                Get embed code from Google Maps → Share → Embed a map
              </div>
            </div>
          </div>
        </div>

        <div class="form-actions">
          <button type="submit" class="save-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
              <polyline points="17 21 17 13 7 13 7 21"></polyline>
              <polyline points="7 3 7 8 15 8"></polyline>
            </svg>
            Save Contact Information
          </button>
        </div>
      </div>
    </form>
  </div>

  @if($errors->any())
    <div class="alert" style="margin-top: 16px;">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"></circle>
        <line x1="15" y1="9" x2="9" y2="15"></line>
        <line x1="9" y1="9" x2="15" y2="15"></line>
      </svg>
      {{ $errors->first() }}
    </div>
  @endif
@endsection
