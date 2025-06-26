@extends('layout')
@section('title', 'Contact')
@section('content')
<h2>Contact Me</h2>
<form>
  <input type="text" placeholder="Name" required>
  <input type="email" placeholder="Email" required>
  <textarea placeholder="Message"></textarea>
  <button type="submit">Send</button>
</form>
@endsection
