<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>@yield('title','TSF')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body { margin:0; font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; }
    .layout { display:flex; min-height:100vh; }
    .sidebar { width: 220px; background: #111827; color:#e5e7eb; padding:16px; }
    .sidebar a { display:block; color:#e5e7eb; text-decoration:none; padding:8px 10px; border-radius:6px; margin-bottom:6px; }
    .sidebar a:hover { background:#1f2937; }
    .content { flex:1; padding:20px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #e5e7eb; padding: 8px 10px; text-align: left; vertical-align: top; }
    th { background: #f3f4f6; }
    .row { display:flex; gap: 12px; align-items: center; }
    .btn { padding: 6px 10px; border: 1px solid #d1d5db; background: #fff; cursor: pointer; border-radius: 6px; }
    .btn.primary { background: #111827; color: #fff; border-color: #111827; }
    .btn.warn { background: #b91c1c; color: #fff; border-color: #991b1b; }
    select, input[type="text"], input[type="datetime-local"], textarea { padding: 6px 8px; border: 1px solid #d1d5db; border-radius: 6px; }
    .ok { background: #ecfdf5; color: #065f46; padding: 8px 12px; border: 1px solid #a7f3d0; border-radius: 6px; margin-bottom: 12px; }
    .err { background: #fef2f2; color: #991b1b; padding: 8px 12px; border: 1px solid #fecaca; border-radius: 6px; margin-bottom: 12px; }
  </style>
</head>
<body>
<div class="layout">
  <aside class="sidebar">
    <div style="font-weight:700; margin-bottom:14px;">TSF</div>
    <nav>
      <a href="{{ route('vehicles.index') }}">Vehicles</a>
      <a href="{{ route('drivers.index') }}">Drivers</a>
      <a href="{{ route('assignments.index') }}">Assignments</a>
    </nav>

    <div style="margin-top:20px; font-size:12px; color:#9ca3af;">
      @php
        $authUser = \App\Models\User::find(session('user_id'));
      @endphp
      @if($authUser)
        <div>Logged in: <strong>{{ $authUser->username }}</strong></div>
        <div>Role: {{ strtoupper($authUser->role) }}</div>
        <div>City: {{ $authUser->city?->name ?? 'ALL' }}</div>
        <form action="{{ route('logout') }}" method="POST" style="margin-top:10px;">
          @csrf
          <button class="btn" type="submit">Logout</button>
        </form>
      @endif
    </div>
  </aside>

  <main class="content">
    <h1 style="margin-top:0;">@yield('title','TSF')</h1>
    @if (session('success'))
      <div class="ok">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
      <div class="err">
        <strong>Validation error:</strong>
        <ul style="margin:6px 0 0 18px;">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @yield('content')
  </main>
</div>
</body>
</html>
