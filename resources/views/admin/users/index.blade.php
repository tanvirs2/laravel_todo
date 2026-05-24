@extends('layouts.app')

@section('title', 'Users – Admin')

@section('content')
@push('styles')
<style>
    .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
    h1 { font-size: 1.6rem; }
    .btn-primary { background: #4f46e5; color: #fff; padding: 8px 18px; border-radius: 6px; text-decoration: none; font-size: .9rem; }
    .card { background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,.1); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; font-size: .9rem; }
    th { background: #f9fafb; text-align: left; padding: 12px 16px; font-size: .8rem; text-transform: uppercase; letter-spacing: .05em; color: #888; border-bottom: 1px solid #f0f0f0; }
    td { padding: 12px 16px; border-bottom: 1px solid #f9f9f9; vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    .badge { display: inline-block; padding: 2px 10px; border-radius: 10px; font-size: .75rem; font-weight: 600; }
    .badge-admin { background: #ede9fe; color: #5b21b6; }
    .badge-user  { background: #e0f2fe; color: #0369a1; }
    .btn-danger { background: #ef4444; color: #fff; border: none; padding: 5px 12px; border-radius: 6px; cursor: pointer; font-size: .82rem; }
    .btn-danger:hover { background: #dc2626; }
    .you { font-size: .75rem; color: #aaa; margin-left: 6px; }
</style>
@endpush

<div class="page-header">
    <h1>Users</h1>
    <a href="/admin/users/create" class="btn-primary">+ New User</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Tasks</th>
                <th>Categories</th>
                <th>Joined</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>
                    {{ $user->name }}
                    @if($user->id === auth()->id())
                        <span class="you">(you)</span>
                    @endif
                </td>
                <td>{{ $user->email }}</td>
                <td><span class="badge badge-{{ $user->role }}">{{ ucfirst($user->role) }}</span></td>
                <td>{{ $user->todos_count }}</td>
                <td>{{ $user->categories_count }}</td>
                <td>{{ $user->created_at->format('M j, Y') }}</td>
                <td>
                    @if($user->id !== auth()->id())
                        <form method="POST" action="/admin/users/{{ $user->id }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger" onclick="return confirm('Delete {{ $user->name }}? Their tasks will also be deleted.')">Delete</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
