<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – Todo App</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, sans-serif; background: #f5f5f5; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { background: #fff; border-radius: 12px; padding: 36px; width: 100%; max-width: 380px; box-shadow: 0 4px 24px rgba(0,0,0,.1); }
        h1 { font-size: 1.5rem; margin-bottom: 6px; color: #4f46e5; }
        p { font-size: .9rem; color: #888; margin-bottom: 24px; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-size: .85rem; font-weight: 500; margin-bottom: 4px; }
        input { width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: .95rem; }
        input:focus { outline: none; border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.1); }
        .checkbox-row { display: flex; align-items: center; gap: 8px; font-size: .85rem; margin-bottom: 20px; }
        button { width: 100%; padding: 10px; background: #4f46e5; color: #fff; border: none; border-radius: 6px; font-size: 1rem; cursor: pointer; }
        button:hover { background: #4338ca; }
        .error { color: #ef4444; font-size: .82rem; margin-top: 4px; }
    </style>
</head>
<body>
<div class="card">
    <h1>✅ Todo App</h1>
    <p>Sign in to your account</p>

    <form method="POST" action="/login">
        @csrf
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" autofocus required>
            @error('email') <p class="error">{{ $message }}</p> @enderror
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
            @error('password') <p class="error">{{ $message }}</p> @enderror
        </div>
        <div class="checkbox-row">
            <input type="checkbox" name="remember" id="remember" style="width:auto">
            <label for="remember" style="margin:0">Remember me</label>
        </div>
        <button type="submit">Sign In</button>
    </form>
</div>
</body>
</html>
