@extends('layouts.app')
@section('title','Home | Masud')
@push('styles') <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}"> @endpush

@section('content')
  <h1>Hi, I’m Masud 👋</h1>
  <p>Final year CSE student & Laravel dev.</p>
@endsection

@push('scripts') <script src="{{ asset('assets/js/home.js') }}" defer></script> @endpush
