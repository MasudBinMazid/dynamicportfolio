@extends('layouts.app')
@section('title','Skills | Masud')
@push('styles') <link rel="stylesheet" href="{{ asset('assets/css/skills.css') }}"> @endpush

@section('content')
  <h1>Skills</h1>
  <ul class="skills">
    <li>Laravel</li><li>PHP</li><li>MySQL/SQLite</li><li>JS</li>
  </ul>
@endsection

@push('scripts') <script src="{{ asset('assets/js/skills.js') }}" defer></script> @endpush
