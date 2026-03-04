@extends('layouts.app')
@section('content')
<h1>Study: {{ $study->title }}</h1>
<p>{{ $study->description }}</p>
<h4>Criteria</h4>
<table class="table"><thead><tr><th>Name</th><th>Type</th><th>Weight</th><th>Ideal</th></tr></thead>
<tbody>
@foreach($study->criteria as $c)
<tr><td>{{ $c->name }}</td><td>{{ $c->type }}</td><td>{{ $c->weight }}</td><td>{{ $c->ideal ?? '-' }}</td></tr>
@endforeach
</tbody></table>
<h4>Alternatives</h4>
<table class="table"><thead><tr><th>Name</th><th>Values</th></tr></thead>
<tbody>
@foreach($study->alternatives as $a)
<tr><td>{{ $a->name }}</td><td>
<ul>
@foreach($a->values as $v)
<li>{{ $v->criteria->name ?? $v->criteria_id }}: {{ $v->value }}</li>
@endforeach
</ul>
</td></tr>
@endforeach
</tbody></table>
<button id="runBtn" class="btn btn-success">Run SPK</button>
<div id="result" class="mt-4"></div>
@endsection
@push('scripts')
<script>
document.getElementById('runBtn').addEventListener('click', async function(){
  this.disabled = true; this.innerText='Running...';
  const res = await fetch('/api/studies/{{ $study->id }}/run',{method:'POST',headers:{'Accept':'application/json'}});
  const data = await res.json();
  this.disabled = false; this.innerText='Run SPK';
  if(data.ok){
    const rows = data.results.map(r=>`<tr><td>${r.name}</td><td>${r.score}</td><td>${r.cf}</td><td>${r.sf}</td></tr>`).join('');
    document.getElementById('result').innerHTML = `<h4>Results</h4><table class="table"><thead><tr><th>Name</th><th>Score</th><th>CF</th><th>SF</th></tr></thead><tbody>${rows}</tbody></table>`;
  } else {
    document.getElementById('result').innerHTML = '<div class="alert alert-danger">Run failed</div>';
  }
});
</script>
@endpush
