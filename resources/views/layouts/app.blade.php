<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Todo App')</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, sans-serif; background: #f5f5f5; color: #333; }
        .navbar { background: #4f46e5; color: #fff; padding: 0 24px; display: flex; align-items: center; height: 52px; gap: 16px; }
        .navbar-brand { font-weight: 700; font-size: 1.1rem; text-decoration: none; color: #fff; margin-right: auto; }
        .navbar a { color: rgba(255,255,255,.85); text-decoration: none; font-size: .9rem; padding: 6px 10px; border-radius: 6px; }
        .navbar a:hover { background: rgba(255,255,255,.15); color: #fff; }
        .navbar a.active { background: rgba(255,255,255,.2); color: #fff; }
        .navbar form { margin: 0; }
        .navbar button { background: rgba(255,255,255,.15); border: none; color: #fff; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: .9rem; }
        .navbar button:hover { background: rgba(255,255,255,.25); }
        .badge-role { font-size: .7rem; padding: 2px 7px; border-radius: 10px; font-weight: 600; background: rgba(255,255,255,.25); margin-left: 4px; }
        .container { max-width: 900px; margin: 36px auto; padding: 0 16px; }
        .flash-success { padding: 10px 16px; border-radius: 6px; margin-bottom: 16px; background: #d4edda; color: #155724; }
        .flash-error   { padding: 10px 16px; border-radius: 6px; margin-bottom: 16px; background: #f8d7da; color: #721c24; }
    </style>
    @stack('styles')
</head>
<body>
<nav class="navbar">
    <a href="/todos" class="navbar-brand">✅ Todo App</a>
    <a href="/todos" class="{{ request()->is('todos*') ? 'active' : '' }}">My Tasks</a>
    <a href="/report" class="{{ request()->is('report*') ? 'active' : '' }}">Report</a>
    @if(auth()->user()->isAdmin())
        <a href="/admin/users" class="{{ request()->is('admin*') ? 'active' : '' }}">Admin</a>
    @endif
    <span style="color:rgba(255,255,255,.6);font-size:.85rem">
        {{ auth()->user()->name }}
        <span class="badge-role">{{ auth()->user()->role }}</span>
    </span>
    <form method="POST" action="/logout">
        @csrf
        <button type="submit">Logout</button>
    </form>
</nav>

<div class="container">
    @if(session('success'))
        <div class="flash-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash-error">{{ session('error') }}</div>
    @endif

    @yield('content')
</div>

@stack('scripts')
</body>
</html>
