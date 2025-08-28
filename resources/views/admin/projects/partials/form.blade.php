<div class="grid">
  <label>Title
    <input name="title" value="{{ old('title', $project->title ?? '') }}" required>
  </label>

  <label>Slug
    <input name="slug" value="{{ old('slug', $project->slug ?? '') }}" placeholder="auto if empty">
  </label>

  <label>Tech stack (comma separated)
    <input name="tech_stack" value="{{ old('tech_stack', $project->tech_stack ?? '') }}">
  </label>

  {{-- Upload OR paste an external URL/path --}}
  <label>Upload image
    <input type="file" name="image" accept="image/*">
  </label>

  <label>OR Image path (external URL or /uploads/..)
    <input name="image_path" value="{{ old('image_path', $project->image_path ?? '') }}">
  </label>

  @isset($project)
    @if($project->image_url)
      <div>
        <small>Current image:</small><br>
        <img src="{{ $project->image_url }}" alt="preview" style="max-width:240px;border:1px solid #eee;border-radius:8px">
      </div>
      <label>
        <input type="checkbox" name="remove_image" value="1"> Remove current stored image
      </label>
    @endif
  @endisset

  <label>Live URL
    <input name="live_url" value="{{ old('live_url', $project->live_url ?? '') }}">
  </label>

  <label>Repo URL
    <input name="repo_url" value="{{ old('repo_url', $project->repo_url ?? '') }}">
  </label>

  <label>Description
    <textarea name="description" rows="6">{{ old('description', $project->description ?? '') }}</textarea>
  </label>

  <label><input type="checkbox" name="featured" value="1"
    {{ old('featured', $project->featured ?? false) ? 'checked' : '' }}> Featured</label>
</div>

@if($errors->any())
  <div class="alert">{{ $errors->first() }}</div>
@endif
