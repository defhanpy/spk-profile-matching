@extends('layouts.app')
@section('content')
<h1>Studies</h1>
<a href="/studies/create" class="btn btn-primary mb-3">Create Study</a>
<table class="table table-striped">
<thead><tr><th>ID</th><th>Title</th><th>Alternatives</th><th>Actions</th></tr></thead>
<tbody>
@foreach($studies as $s)
<tr>
<td>{{ $s->id }}</td>
<td>{{ $s->title }}</td>
<td>{{ $s->alternatives_count }}</td>
<td><a class="btn btn-sm btn-outline-primary" href="/studies/{{ $s->id }}">View</a></td>
</tr>
@endforeach
</tbody>
</table>
@endsection
