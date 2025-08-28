@extends('layouts.admin')
@section('title','Create Project')

@section('content')
  <form method="POST" action="{{ route('admin.projects.store') }}" class="form" enctype="multipart/form-data">
    @csrf
    @include('admin.projects.partials.form', ['project' => null])
    <button type="submit">Create</button>
  </form>
@endsection
