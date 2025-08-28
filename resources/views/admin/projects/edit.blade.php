@extends('layouts.admin')
@section('title','Edit Project')

@section('content')
  <form method="POST" action="{{ route('admin.projects.update',$project) }}" class="form" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.projects.partials.form', ['project' => $project])
    <button type="submit">Update</button>
  </form>
@endsection
