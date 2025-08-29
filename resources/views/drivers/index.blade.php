@extends('layouts.app')

@section('title', 'Drivers')

@section('content')
<table>
  <thead>
    <tr>
      <th style="width:80px;">ID</th>
      <th style="width:220px;">Name</th>
      <th style="width:160px;">License</th>
      <th style="width:140px;">Phone</th>
      <th style="width:140px;">Status</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
  @forelse ($drivers as $driver)
    <tr>
      <td>#{{ $driver->id }}</td>
      <td>{{ $driver->name }}</td>
      <td>{{ $driver->license_number }}</td>
      <td>{{ $driver->phone ?? '—' }}</td>
      <td>{{ $driver->status }}</td>
      <td class="row">
        {{-- Edit inline (minimal) --}}
        <form action="{{ route('drivers.update', $driver) }}" method="POST" class="row" style="gap:6px;">
          @csrf
          @method('PUT')
          <input type="text" name="name" value="{{ $driver->name }}" placeholder="name" />
          <input type="text" name="phone" value="{{ $driver->phone }}" placeholder="phone" />
          <input type="text" name="license_number" value="{{ $driver->license_number }}" placeholder="license" />
          <select name="status">
            <option value="active" {{ $driver->status==='active'?'selected':'' }}>active</option>
            <option value="inactive" {{ $driver->status==='inactive'?'selected':'' }}>inactive</option>
            <option value="suspended" {{ $driver->status==='suspended'?'selected':'' }}>suspended</option>
          </select>
          <button class="btn" type="submit">Save</button>
        </form>

        <form action="{{ route('drivers.destroy', $driver) }}" method="POST" onsubmit="return confirm('Delete driver?');">
          @csrf
          @method('DELETE')
          <button class="btn warn" type="submit">Delete</button>
        </form>
      </td>
    </tr>
  @empty
    <tr><td colspan="6"><em>No drivers found.</em></td></tr>
  @endforelse
  </tbody>
</table>

{{-- Create new (minimal form) --}}
<h3 style="margin-top:18px;">Create Driver</h3>
<form action="{{ route('drivers.store') }}" method="POST" class="row" style="gap:10px;">
  @csrf
  <input type="text" name="name" placeholder="Name" required />
  <input type="text" name="phone" placeholder="Phone" />
  <input type="text" name="license_number" placeholder="License number" required />
  <input type="date" name="license_expiry" />
  <select name="status">
    <option value="active">active</option>
    <option value="inactive">inactive</option>
    <option value="suspended">suspended</option>
  </select>
  <button class="btn primary" type="submit">Add</button>
</form>

<div style="margin-top:12px;">
  {{ $drivers->links() }}
</div>
@endsection
