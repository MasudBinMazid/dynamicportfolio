@extends('layouts.app')
@section('title','About | Masud')
@push('styles') <link rel="stylesheet" href="{{ asset('assets/css/about.css') }}"> @endpush

@section('content')
  <h1>About Me</h1>
  <p>Short bio, interests, experience…</p>
@endsection

@push('scripts') <script src="{{ asset('assets/js/about.js') }}" defer></script> @endpush
