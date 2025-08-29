<!doctype html>
<html>
  <body style="font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; margin: 24px;">
    <h1>Sign in</h1>

    @if ($errors->any())
      <div style="color:#b91c1c; margin-bottom: 10px;">
        {{ $errors->first() }}
      </div>
    @endif

    <form action="{{ route('login.submit') }}" method="POST" style="display:flex; gap:8px; flex-direction:column; width:280px;">
      @csrf
      <label>
        Username
        <input type="text" name="username" value="{{ old('username') }}" />
      </label>
      <label>
        Password
        <input type="password" name="password" />
      </label>
      <button type="submit">Login</button>
    </form>
  </body>
</html>
