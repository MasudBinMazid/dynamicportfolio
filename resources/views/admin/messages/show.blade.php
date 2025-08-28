@extends('layouts.admin')
@section('title','Message')

@section('content')
  <h3>{{ $message->subject ?? '(No subject)' }}</h3>
  <p><strong>From:</strong> {{ $message->name }} &lt;{{ $message->email }}&gt;</p>
  <pre>{{ $message->message }}</pre>

  <form action="{{ route('admin.messages.destroy',$message) }}" method="POST">
    @csrf @method('DELETE')
    <button onclick="return confirm('Delete this message?')">Delete</button>
  </form>
@endsection
