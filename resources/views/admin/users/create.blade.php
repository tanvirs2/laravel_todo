@extends('layouts.app')

@section('title', 'Create User – Admin')

@section('content')
@push('styles')
<style>
    .page-header { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; }
    .back-link { color: #888; text-decoration: none; font-size: .9rem; }
    h1 { font-size: 1.6rem; }
    .card { background: #fff; border-radius: 8px; padding: 28px; max-width: 480px; box-shadow: 0 1px 3px rgba(0,0,0,.1); }
    .form-group { margin-bottom: 16px; }
    label { display: block; font-size: .85rem; font-weight: 500; margin-bottom: 4px; }
    input, select { width: 100%; padding: 9px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: .9rem; }
    input:focus, select:focus { outline: none; border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.1); }
    .btn-primary { background: #4f46e5; color: #fff; border: none; padding: 10px 24px; border-radius: 6px; cursor: pointer; font-size: .95rem; }
    .btn-primary:hover { background: #4338ca; }
    .error { color: #ef4444; font-size: .82rem; margin-top: 4px; }
    .hint { font-size: .78rem; color: #aaa; margin-top: 3px; }
</style>
@endpush

<div class="page-header">
    <a href="/admin/users" class="back-link">← Users</a>
    <h1>Create User</h1>
</div>

<div class="card">
    <form method="POST" action="/admin/users">
        @csrf
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus>
            @error('name') <p class="error">{{ $message }}</p> @enderror
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
            @error('email') <p class="error">{{ $message }}</p> @enderror
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
            <p class="hint">Minimum 8 characters</p>
            @error('password') <p class="error">{{ $message }}</p> @enderror
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" required>
        </div>
        <div class="form-group">
            <label>Role</label>
            <select name="role">
                <option value="user"  @selected(old('role','user')=='user')>User</option>
                <option value="admin" @selected(old('role')=='admin')>Admin</option>
            </select>
            @error('role') <p class="error">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="btn-primary">Create User</button>
    </form>
</div>
@endsection
