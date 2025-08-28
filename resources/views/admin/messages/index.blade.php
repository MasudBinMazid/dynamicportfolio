@extends('layouts.admin')
@section('title','Messages')

@section('content')
  <table class="table">
    <thead><tr><th>Name</th><th>Email</th><th>Subject</th><th>Date</th><th></th></tr></thead>
    <tbody>
    @foreach($messages as $m)
      <tr>
        <td>{{ $m->name }}</td>
        <td>{{ $m->email }}</td>
        <td>{{ $m->subject }}</td>
        <td>{{ $m->created_at->format('Y-m-d H:i') }}</td>
        <td><a href="{{ route('admin.messages.show',$m) }}">View</a></td>
      </tr>
    @endforeach
    </tbody>
  </table>
  {{ $messages->links() }}
@endsection
