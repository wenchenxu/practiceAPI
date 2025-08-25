<!doctype html>
<html>
  <body>
    <h1>Vehicles</h1>
    <ul>
      @forelse ($vehicles as $v)
        <li>#{{ $v->id }} — {{ $v->make ?? 'Unknown' }} {{ $v->model ?? '' }}</li>
      @empty
        <li>No vehicles found.</li>
      @endforelse
    </ul>
  </body>
</html>
