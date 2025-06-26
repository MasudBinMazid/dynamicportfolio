@extends('layout')
@section('title', 'Contact')
@section('content')
<h2 class="c-cls">Contact Me</h2>
<form>
  <input type="text" placeholder="Enter Your Name" required>
  <input type="email" placeholder="Enter Your Email" required>
  <textarea placeholder="Write Here Your Message to Masud"></textarea>
  <button class="c-btn" type="submit">Send</button>
</form>
@endsection
