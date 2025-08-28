@extends('layouts.app')
@section('title','Contact | Masud')
@push('styles') <link rel="stylesheet" href="{{ asset('assets/css/contact.css') }}"> @endpush

@section('content')
  <h1>Contact</h1>

  <div class="contact-layout">
    <section class="static">
      <ul>
        @if($info?->email)   <li><strong>Email:</strong> {{ $info->email }}</li> @endif
        @if($info?->phone)   <li><strong>Phone:</strong> {{ $info->phone }}</li> @endif
        @if($info?->address) <li><strong>Address:</strong> {{ $info->address }}</li> @endif
        <li class="socials">
          @if($info?->github)   <a target="_blank" href="{{ $info->github }}">GitHub</a> @endif
          @if($info?->linkedin) <a target="_blank" href="{{ $info->linkedin }}">LinkedIn</a> @endif
          @if($info?->twitter)  <a target="_blank" href="{{ $info->twitter }}">Twitter</a> @endif
        </li>
      </ul>
      @if($info?->map_embed) <div class="map">{!! $info->map_embed !!}</div> @endif
    </section>

    <section class="form">
      <h2>Send me a message</h2>
      <form method="POST" action="{{ route('contact.send') }}">
        @csrf
        <label>Name <input name="name" value="{{ old('name') }}" required></label>
        <label>Email <input type="email" name="email" value="{{ old('email') }}" required></label>
        <label>Subject <input name="subject" value="{{ old('subject') }}"></label>
        <label>Message <textarea name="message" rows="5" required>{{ old('message') }}</textarea></label>
        <button type="submit">Send</button>
      </form>
      @if($errors->any()) <div class="alert">{{ $errors->first() }}</div> @endif
    </section>
  </div>
@endsection

@push('scripts') <script src="{{ asset('assets/js/contact.js') }}" defer></script> @endpush
