@extends('layouts.admin')
@section('title','Contact Info')

@section('content')
  <form method="POST" action="{{ route('admin.contact_info.update') }}" class="form">
    @csrf
    <div class="grid">
      <label>Email <input name="email" value="{{ old('email',$info->email ?? '') }}"></label>
      <label>Phone <input name="phone" value="{{ old('phone',$info->phone ?? '') }}"></label>
      <label>Address <input name="address" value="{{ old('address',$info->address ?? '') }}"></label>
      <label>GitHub <input name="github" value="{{ old('github',$info->github ?? '') }}"></label>
      <label>LinkedIn <input name="linkedin" value="{{ old('linkedin',$info->linkedin ?? '') }}"></label>
      <label>Twitter <input name="twitter" value="{{ old('twitter',$info->twitter ?? '') }}"></label>
      <label>CV URL <input name="cv_url" value="{{ old('cv_url',$info->cv_url ?? '') }}"></label>
      <label>Map Embed
        <textarea name="map_embed" rows="4">{{ old('map_embed',$info->map_embed ?? '') }}</textarea>
      </label>
    </div>
    <button type="submit">Save</button>
  </form>
@endsection
