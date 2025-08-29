@extends('layouts.app')

@section('title', 'Assignments')

@section('content')
<table>
  <thead>
    <tr>
      <th style="width:80px;">ID</th>
      <th style="width:180px;">Vehicle</th>
      <th style="width:220px;">Driver</th>
      <th style="width:180px;">Assigned At</th>
      <th style="width:180px;">Released At</th>
      <th>Notes</th>
    </tr>
  </thead>
  <tbody>
  @forelse ($assignments as $a)
    <tr>
      <td>#{{ $a->id }}</td>
      <td>{{ $a->vehicle?->license_number ?? '—' }}</td>
      <td>{{ $a->driver?->name ?? '—' }} {{ $a->driver ? '(' . $a->driver->license_number . ')' : '' }}</td>
      <td>{{ optional($a->assigned_at)->format('Y-m-d H:i') }}</td>
      <td>{{ optional($a->released_at)->format('Y-m-d H:i') ?? '—' }}</td>
      <td>{{ $a->notes ?? '—' }}</td>
    </tr>
  @empty
    <tr><td colspan="6"><em>No assignments found.</em></td></tr>
  @endforelse
  </tbody>
</table>

<div style="margin-top:12px;">
  {{ $assignments->links() }}
</div>
@endsection
