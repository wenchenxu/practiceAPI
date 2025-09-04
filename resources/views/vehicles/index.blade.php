@extends('layouts.app')

@section('title', 'Vehicles')

@section('content')

<div class="flex items-center justify-between mb-4">
  <h1 class="text-xl font-semibold">Vehicles</h1>

  @can('create', \App\Models\Vehicle::class)
    <a href="{{ route('vehicles.create') }}"
       class="inline-flex items-center px-3 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
      + Add Vehicle
    </a>
  @endcan
</div>

<table>
  <thead>
    <tr>
      <th style="width: 120px;">ID</th>
      <th style="width: 180px;">License</th>
      <th style="width: 160px;">City</th>
      <th>Current Driver</th>
      <th style="width: 420px;">Assign Driver</th>
      <th style="width: 220px;">Release</th>
    </tr>
  </thead>
  <tbody>
  @forelse ($vehicles as $vehicle)
    @php
      $assignment = $vehicle->currentAssignment;
      $currentDriver = optional($assignment)->driver;
    @endphp
    <tr>
      <td>#{{ $vehicle->id }}</td>
      <td>{{ $vehicle->license_number }}</td>
      <td>{{ $vehicle->city?->name ?? '—' }}</td>
      <td>
        @if ($currentDriver)
          <div>
            <div><strong>{{ $currentDriver->name }}</strong> ({{ $currentDriver->license_number }})</div>
            <div>Assigned at: {{ optional(optional($assignment)->assigned_at)->format('Y-m-d H:i') }}</div>
            @if (!empty($assignment->notes))
              <div>Notes: {{ $assignment->notes }}</div>
            @endif
          </div>
        @else
          <em>Unassigned</em>
        @endif
      </td>

      {{-- Assign form (only when unassigned) --}}
      <td>
        @if (!$currentDriver)
          @can('create', \App\Models\Assignment::class)
          <form class="row" action="{{ route('vehicles.assign', $vehicle) }}" method="POST">
            @csrf
            <div>
              <label for="driver-{{ $vehicle->id }}" style="display:block; font-size:12px; color:#6b7280;">Driver</label>
              <select id="driver-{{ $vehicle->id }}" name="driver_id" required>
                <option value="">-- choose --</option>
                @foreach ($drivers as $d)
                  <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->license_number }})</option>
                @endforeach
              </select>
            </div>
            <div>
              <label for="when-{{ $vehicle->id }}" style="display:block; font-size:12px; color:#6b7280;">When</label>
              <input id="when-{{ $vehicle->id }}" type="datetime-local" name="assigned_at">
            </div>
            <div>
              <label for="notes-{{ $vehicle->id }}" style="display:block; font-size:12px; color:#6b7280;">Notes</label>
              <input id="notes-{{ $vehicle->id }}" type="text" name="notes" placeholder="optional">
            </div>
            <button class="btn primary" type="submit">Assign</button>
          </form>
          @endcan
        @else
          <em>N/A</em>
        @endif
      </td>

      {{-- Release form (only when assigned) --}}
      <td>
        @if ($currentDriver)
          @can('update', \App\Models\Assignment::class)
          <form class="row" action="{{ route('vehicles.release', $vehicle) }}" method="POST">
            @csrf
            <div>
              <label for="rel-when-{{ $vehicle->id }}" style="display:block; font-size:12px; color:#6b7280;">Released at</label>
              <input id="rel-when-{{ $vehicle->id }}" type="datetime-local" name="released_at">
            </div>
            <button class="btn warn" type="submit">Release</button>
          </form>
          @endcan
        @else
          <em>N/A</em>
        @endif
      </td>
    </tr>
  @empty
    <tr><td colspan="5"><em>No vehicles found.</em></td></tr>
  @endforelse
  </tbody>
</table>

<div style="margin-top:12px;">
  {{ $vehicles->links() }}
</div>
@endsection
