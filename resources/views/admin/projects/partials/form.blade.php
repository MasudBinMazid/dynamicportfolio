@push('styles')
<style>
  .form-container {
    background: var(--card);
    border-radius: 16px;
    padding: 32px;
    box-shadow: var(--shadow);
    margin-bottom: 24px;
  }

  .form-grid {
    display: grid;
    gap: 24px;
    grid-template-columns: 1fr;
  }

  .form-row {
    display: grid;
    gap: 16px;
    grid-template-columns: 1fr 1fr;
  }

  .form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
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

  .form-label.required::after {
    content: '*';
    color: var(--danger);
    font-weight: bold;
  }

  .form-input {
    padding: 12px 16px;
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
    font-family: inherit;
    resize: vertical;
    min-height: 120px;
  }

  .form-textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
  }

  .file-input-wrapper {
    position: relative;
    display: inline-block;
    width: 100%;
  }

  .file-input {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
  }

  .file-input-label {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 20px;
    border: 2px dashed var(--border);
    border-radius: 12px;
    background: var(--secondary);
    color: var(--text-light);
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
    min-height: 80px;
  }

  .file-input-label:hover {
    border-color: var(--primary);
    background: rgba(79, 70, 229, 0.05);
    color: var(--primary);
  }

  .file-input-label.has-file {
    border-color: var(--success);
    background: rgba(16, 185, 129, 0.05);
    color: var(--success);
  }

  .image-preview {
    margin-top: 16px;
    padding: 16px;
    background: var(--secondary);
    border-radius: 12px;
    text-align: center;
  }

  .image-preview img {
    max-width: 240px;
    max-height: 160px;
    border-radius: 8px;
    box-shadow: var(--shadow);
    object-fit: cover;
  }

  .image-preview-label {
    font-size: 12px;
    color: var(--text-light);
    margin-bottom: 12px;
    display: block;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .checkbox-group {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: var(--secondary);
    border-radius: 12px;
    cursor: pointer;
    transition: var(--transition);
  }

  .checkbox-group:hover {
    background: rgba(79, 70, 229, 0.05);
  }

  .checkbox-input {
    width: 20px;
    height: 20px;
    border: 2px solid var(--border);
    border-radius: 6px;
    background: var(--card);
    cursor: pointer;
    position: relative;
    appearance: none;
    transition: var(--transition);
  }

  .checkbox-input:checked {
    background: var(--primary);
    border-color: var(--primary);
  }

  .checkbox-input:checked::after {
    content: '✓';
    color: white;
    font-size: 14px;
    font-weight: bold;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }

  .checkbox-label {
    font-weight: 500;
    color: var(--text);
    cursor: pointer;
    flex: 1;
  }

  .remove-image-group {
    background: rgba(239, 68, 68, 0.05);
    border: 1px solid rgba(239, 68, 68, 0.2);
  }

  .remove-image-group .checkbox-input:checked {
    background: var(--danger);
    border-color: var(--danger);
  }

  .tech-stack-hint {
    font-size: 12px;
    color: var(--text-light);
    font-style: italic;
    margin-top: 4px;
  }

  .url-input-group {
    position: relative;
  }

  .url-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-light);
    pointer-events: none;
  }

  .url-input {
    padding-left: 48px;
  }

  .form-section {
    background: var(--bg);
    padding: 24px;
    border-radius: 12px;
    border-left: 4px solid var(--primary);
  }

  .form-section-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--text);
    margin: 0 0 16px 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .image-upload-options {
    display: grid;
    gap: 16px;
    grid-template-columns: 1fr;
  }

  .divider {
    position: relative;
    text-align: center;
    margin: 16px 0;
  }

  .divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: var(--border);
  }

  .divider span {
    background: var(--card);
    padding: 0 16px;
    color: var(--text-light);
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  @media (max-width: 768px) {
    .form-row {
      grid-template-columns: 1fr;
    }
    
    .form-container {
      padding: 20px;
      border-radius: 12px;
    }
  }
</style>
@endpush

<div class="form-container">
  <div class="form-grid">
    <!-- Basic Information Section -->
    <div class="form-section">
      <h3 class="form-section-title">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
          <polyline points="14 2 14 8 20 8"></polyline>
          <line x1="16" y1="13" x2="8" y2="13"></line>
          <line x1="16" y1="17" x2="8" y2="17"></line>
          <polyline points="10 9 9 9 8 9"></polyline>
        </svg>
        Basic Information
      </h3>
      
      <div class="form-row">
        <div class="form-group">
          <label class="form-label required">Project Title</label>
          <input 
            type="text"
            name="title" 
            class="form-input"
            value="{{ old('title', $project->title ?? '') }}" 
            placeholder="Enter project title"
            required
          >
        </div>

        <div class="form-group">
          <label class="form-label">URL Slug</label>
          <input 
            type="text"
            name="slug" 
            class="form-input"
            value="{{ old('slug', $project->slug ?? '') }}" 
            placeholder="auto-generated if empty"
          >
        </div>
      </div>

      <div class="form-group full-width">
        <label class="form-label">Tech Stack</label>
        <input 
          type="text"
          name="tech_stack" 
          class="form-input"
          value="{{ old('tech_stack', $project->tech_stack ?? '') }}"
          placeholder="React, Node.js, MongoDB, etc."
        >
        <div class="tech-stack-hint">Separate technologies with commas</div>
      </div>

      <div class="form-group full-width">
        <label class="form-label">Project Description</label>
        <textarea 
          name="description" 
          class="form-textarea"
          placeholder="Describe your project, its features, and what makes it special..."
        >{{ old('description', $project->description ?? '') }}</textarea>
      </div>
    </div>

    <!-- Image Upload Section -->
    <div class="form-section">
      <h3 class="form-section-title">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
          <circle cx="8.5" cy="8.5" r="1.5"></circle>
          <polyline points="21 15 16 10 5 21"></polyline>
        </svg>
        Project Image
      </h3>

      <div class="image-upload-options">
        <div class="form-group">
          <label class="form-label">Upload Image</label>
          <div class="file-input-wrapper">
            <input 
              type="file" 
              name="image" 
              class="file-input"
              accept="image/*"
              id="imageUpload"
            >
            <label for="imageUpload" class="file-input-label">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="17 8 12 3 7 8"></polyline>
                <line x1="12" y1="3" x2="12" y2="15"></line>
              </svg>
              <span>Choose image file</span>
            </label>
          </div>
        </div>

        <div class="divider">
          <span>OR</span>
        </div>

        <div class="form-group">
          <label class="form-label">External Image URL</label>
          <input 
            type="url"
            name="image_path" 
            class="form-input"
            value="{{ old('image_path', $project->image_path ?? '') }}"
            placeholder="https://example.com/image.jpg or /uploads/image.jpg"
          >
        </div>
      </div>

      @isset($project)
        @if($project->image_url)
          <div class="image-preview">
            <div class="image-preview-label">Current Image</div>
            <img src="{{ $project->image_url }}" alt="Current project image">
            
            <div class="checkbox-group remove-image-group" style="margin-top: 16px;">
              <input 
                type="checkbox" 
                name="remove_image" 
                value="1"
                class="checkbox-input"
                id="removeImage"
              >
              <label for="removeImage" class="checkbox-label">
                Remove current image
              </label>
            </div>
          </div>
        @endif
      @endisset
    </div>

    <!-- Links Section -->
    <div class="form-section">
      <h3 class="form-section-title">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
          <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
        </svg>
        Project Links
      </h3>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Live Demo URL</label>
          <div class="url-input-group">
            <svg class="url-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            <input 
              type="url"
              name="live_url" 
              class="form-input url-input"
              value="{{ old('live_url', $project->live_url ?? '') }}"
              placeholder="https://your-project.com"
            >
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Repository URL</label>
          <div class="url-input-group">
            <svg class="url-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path>
            </svg>
            <input 
              type="url"
              name="repo_url" 
              class="form-input url-input"
              value="{{ old('repo_url', $project->repo_url ?? '') }}"
              placeholder="https://github.com/username/project"
            >
          </div>
        </div>
      </div>
    </div>

    <!-- Settings Section -->
    <div class="form-section">
      <h3 class="form-section-title">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="3"></circle>
          <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
        </svg>
        Project Settings
      </h3>

      <div class="checkbox-group">
        <input 
          type="checkbox" 
          name="featured" 
          value="1"
          class="checkbox-input"
          id="featured"
          {{ old('featured', $project->featured ?? false) ? 'checked' : '' }}
        >
        <label for="featured" class="checkbox-label">
          <strong>Featured Project</strong>
          <div style="font-size: 12px; color: var(--text-light); margin-top: 2px;">
            Featured projects are highlighted on your portfolio homepage
          </div>
        </label>
      </div>
    </div>
  </div>
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

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // File upload visual feedback
    const fileInput = document.getElementById('imageUpload');
    const fileLabel = fileInput?.nextElementSibling;
    
    if (fileInput && fileLabel) {
      fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
          fileLabel.classList.add('has-file');
          const fileName = this.files[0].name;
          const span = fileLabel.querySelector('span');
          if (span) {
            span.textContent = fileName;
          }
        } else {
          fileLabel.classList.remove('has-file');
          const span = fileLabel.querySelector('span');
          if (span) {
            span.textContent = 'Choose image file';
          }
        }
      });
    }

    // Auto-generate slug from title
    const titleInput = document.querySelector('input[name="title"]');
    const slugInput = document.querySelector('input[name="slug"]');
    
    if (titleInput && slugInput) {
      titleInput.addEventListener('input', function() {
        if (!slugInput.value || slugInput.value === slugInput.getAttribute('data-original')) {
          const slug = this.value
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
          slugInput.value = slug;
          slugInput.setAttribute('data-original', slug);
        }
      });
    }
  });
</script>
@endpush
