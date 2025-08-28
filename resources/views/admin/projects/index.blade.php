@extends('layouts.admin')
@section('title','Projects')

@section('content')
  <a href="{{ route('admin.projects.create') }}" class="btn">+ New Project</a>
  <table class="table">
    <thead><tr><th>Title</th><th>Slug</th><th>Featured</th><th></th></tr></thead>
    <tbody>
    @foreach($projects as $p)
      <tr>
        <td>{{ $p->title }}</td>
        <td>{{ $p->slug }}</td>
        <td>{{ $p->featured ? 'Yes' : 'No' }}</td>
        <td>
          <a href="{{ route('admin.projects.edit',$p) }}">Edit</a>
          <form action="{{ route('admin.projects.destroy',$p) }}" method="POST" style="display:inline">
            @csrf @method('DELETE')
            <button onclick="return confirm('Delete?')">Delete</button>
          </form>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
  {{ $projects->links() }}
@endsection
