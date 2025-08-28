@extends('layouts.admin')

@section('title','Admin Dashboard')

@section('content')
  <h2>Quick Stats</h2>
  <ul>
    <li>Total Projects: {{ $stats['projects'] }}</li>
    <li>Featured Projects: {{ $stats['featured'] }}</li>
    <li>Contact Messages: {{ $stats['messages'] }}</li>
  </ul>
@endsection
