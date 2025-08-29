<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>@yield('title', 'TSF')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; margin: 24px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #e5e7eb; padding: 8px 10px; text-align: left; vertical-align: top; }
    th { background: #f3f4f6; }
    .row { display: flex; gap: 12px; align-items: center; }
    .ok { background: #ecfdf5; color: #065f46; padding: 8px 12px; border: 1px solid #a7f3d0; border-radius: 6px; margin-bottom: 12px; }
    .err { background: #fef2f2; color: #991b1b; padding: 8px 12px; border: 1px solid #fecaca; border-radius: 6px; margin-bottom: 12px; }
    .btn { padding: 6px 10px; border: 1px solid #d1d5db; background: #fff; cursor: pointer; border-radius: 6px; }
    .btn.primary { background: #111827; color: #fff; border-color: #111827; }
    .btn.warn { background: #b91c1c; color: #fff; border-color: #991b1b; }
    select, input[type="text"], input[type="datetime-local"], textarea { padding: 6px 8px; border: 1px solid #d1d5db; border-radius: 6px; }
  </style>
</head>
<body>
  <h1 style="margin-top:0;">@yield('title', 'TSF')</h1>

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
</body>
</html>
