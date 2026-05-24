@extends('layouts.app')

@section('title', 'My Todos')

@push('styles')
<style>
    .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
    h1 { font-size: 1.8rem; }
    h2 { font-size: 1rem; margin-bottom: 12px; color: #555; }
    .card { background: #fff; border-radius: 8px; padding: 20px; margin-bottom: 16px; box-shadow: 0 1px 3px rgba(0,0,0,.1); }
    .form-row { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 10px; }
    input[type=text], input[type=date], select, textarea { padding: 8px 12px; border: 1px solid #ccc; border-radius: 6px; font-size: .9rem; }
    input[type=text], textarea { flex: 1; min-width: 180px; }
    textarea { resize: vertical; height: 60px; }
    .checkbox-label { display: flex; align-items: center; gap: 6px; font-size: .85rem; cursor: pointer; white-space: nowrap; }
    button { padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; font-size: .9rem; }
    .btn-primary { background: #4f46e5; color: #fff; }
    .btn-primary:hover { background: #4338ca; }
    .btn-danger { background: #ef4444; color: #fff; }
    .btn-sm { padding: 4px 10px; font-size: .8rem; }
    .filters { display: flex; gap: 8px; margin-bottom: 16px; flex-wrap: wrap; align-items: center; }
    .filters a { padding: 6px 12px; border-radius: 20px; text-decoration: none; background: #e5e7eb; color: #333; font-size: .85rem; }
    .filters a.active { background: #4f46e5; color: #fff; }
    .filters span { color: #aaa; font-size: .8rem; }
    .todo-item { display: flex; align-items: flex-start; gap: 12px; padding: 14px 0; border-bottom: 1px solid #f0f0f0; }
    .todo-item:last-child { border-bottom: none; }
    .todo-title { font-weight: 500; }
    .todo-title.done { text-decoration: line-through; color: #999; }
    .todo-meta { font-size: .8rem; color: #888; margin-top: 4px; display: flex; flex-wrap: wrap; gap: 6px; align-items: center; }
    .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: .75rem; font-weight: 600; }
    .badge-low { background: #d1fae5; color: #065f46; }
    .badge-medium { background: #fef3c7; color: #92400e; }
    .badge-high { background: #fee2e2; color: #991b1b; }
    .badge-cat { background: #ede9fe; color: #5b21b6; }
    .badge-recurring { background: #dbeafe; color: #1e40af; }
    .overdue { color: #ef4444; font-weight: 600; }
    .todo-actions { margin-left: auto; display: flex; gap: 6px; align-items: center; }
    .section-label { font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #aaa; padding: 8px 0 4px; }
    .modal-bg { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 100; }
    .modal-bg.open { display: flex; align-items: center; justify-content: center; }
    .modal { background: #fff; border-radius: 10px; padding: 24px; width: 100%; max-width: 480px; }
    .modal h2 { margin-bottom: 16px; font-size: 1.1rem; color: #333; }
    .form-group { margin-bottom: 12px; display: flex; flex-direction: column; gap: 4px; }
    label { font-size: .85rem; font-weight: 500; }
    .modal-actions { display: flex; gap: 8px; justify-content: flex-end; margin-top: 16px; }
    .cat-list { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px; }
    .cat-tag { display: flex; align-items: center; gap: 6px; background: #ede9fe; color: #5b21b6; padding: 4px 10px; border-radius: 20px; font-size: .8rem; }
    .cat-tag form { margin: 0; }
    .cat-tag button { background: none; color: #7c3aed; font-size: .75rem; padding: 0; cursor: pointer; }
    .cat-tag button:hover { color: #ef4444; }
    .error { color: #ef4444; font-size: .8rem; margin-top: 4px; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1>My Todos</h1>
</div>

{{-- Add task form --}}
<div class="card">
    <h2>Add Task</h2>
    <form method="POST" action="/todos">
        @csrf
        <div class="form-row">
            <input type="text" name="title" placeholder="Task title..." value="{{ old('title') }}" required>
            <select name="priority">
                <option value="low"    @selected(old('priority')=='low')>Low</option>
                <option value="medium" @selected(old('priority','medium')=='medium')>Medium</option>
                <option value="high"   @selected(old('priority')=='high')>High</option>
            </select>
            <select name="category_id">
                <option value="">No category</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(old('category_id')==$cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
            <input type="date" name="due_date" value="{{ old('due_date') }}">
        </div>
        <div class="form-row" style="align-items:flex-start">
            <textarea name="description" placeholder="Optional description...">{{ old('description') }}</textarea>
            <div style="display:flex;flex-direction:column;gap:8px;justify-content:flex-end">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_recurring" value="1" {{ old('is_recurring') ? 'checked' : '' }}>
                    Repeats daily
                </label>
                <button type="submit" class="btn-primary">Add Task</button>
            </div>
        </div>
        @error('title') <p class="error">{{ $message }}</p> @enderror
    </form>
</div>

{{-- Manage categories --}}
<div class="card">
    <h2>Categories</h2>
    <form method="POST" action="/categories" style="display:flex;gap:8px;margin-bottom:10px">
        @csrf
        <input type="text" name="name" placeholder="New category name..." value="{{ old('name') }}" style="flex:1">
        <button type="submit" class="btn-primary">Add</button>
    </form>
    @error('name') <p class="error">{{ $message }}</p> @enderror
    <div class="cat-list">
        @forelse($categories as $cat)
            <div class="cat-tag">
                {{ $cat->name }}
                <form method="POST" action="/categories/{{ $cat->id }}">
                    @csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('Delete category?')" title="Remove">✕</button>
                </form>
            </div>
        @empty
            <span style="color:#aaa;font-size:.85rem">No categories yet.</span>
        @endforelse
    </div>
</div>

{{-- Filters --}}
<div class="filters">
    <a href="/todos" class="{{ !request()->anyFilled(['status','priority','category','type']) ? 'active' : '' }}">All</a>
    <a href="/todos?status=pending" class="{{ request('status')=='pending' ? 'active' : '' }}">Pending</a>
    <a href="/todos?status=done"    class="{{ request('status')=='done'    ? 'active' : '' }}">Done</a>
    <a href="/todos?type=recurring" class="{{ request('type')=='recurring' ? 'active' : '' }}">🔁 Daily</a>
    <span>|</span>
    <a href="/todos?priority=high"   class="{{ request('priority')=='high'   ? 'active' : '' }}">High</a>
    <a href="/todos?priority=medium" class="{{ request('priority')=='medium' ? 'active' : '' }}">Medium</a>
    <a href="/todos?priority=low"    class="{{ request('priority')=='low'    ? 'active' : '' }}">Low</a>
    @if($categories->count())
        <span>|</span>
        @foreach($categories as $cat)
            <a href="/todos?category={{ $cat->id }}" class="{{ request('category')==$cat->id ? 'active' : '' }}">{{ $cat->name }}</a>
        @endforeach
    @endif
</div>

{{-- Todo list --}}
@php
    $recurring = $todos->where('is_recurring', true);
    $regular   = $todos->where('is_recurring', false);
@endphp

<div class="card">
    @if($recurring->count())
        <div class="section-label">🔁 Daily Tasks</div>
        @foreach($recurring as $todo)
            @include('todos._item', ['todo' => $todo])
        @endforeach
    @endif

    @if($regular->count())
        @if($recurring->count())
            <div class="section-label" style="margin-top:12px">Tasks</div>
        @endif
        @foreach($regular as $todo)
            @include('todos._item', ['todo' => $todo])
        @endforeach
    @endif

    @if($todos->isEmpty())
        <p style="color:#999;text-align:center;padding:24px 0">No tasks yet. Add one above!</p>
    @endif
</div>

{{-- Edit modal --}}
<div class="modal-bg" id="editModal">
    <div class="modal">
        <h2>Edit Task</h2>
        <form method="POST" id="editForm">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" id="editTitle" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" id="editDescription"></textarea>
            </div>
            <div class="form-group">
                <label>Priority</label>
                <select name="priority" id="editPriority">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category_id" id="editCategory">
                    <option value="">No category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Due Date</label>
                <input type="date" name="due_date" id="editDueDate">
            </div>
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_recurring" id="editRecurring" value="1">
                    Repeats daily
                </label>
            </div>
            <div class="modal-actions">
                <button type="button" onclick="closeEdit()">Cancel</button>
                <button type="submit" class="btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openEdit(id, title, desc, priority, dueDate, categoryId, isRecurring) {
    document.getElementById('editForm').action = '/todos/' + id;
    document.getElementById('editTitle').value = title;
    document.getElementById('editDescription').value = desc;
    document.getElementById('editPriority').value = priority;
    document.getElementById('editDueDate').value = dueDate || '';
    document.getElementById('editCategory').value = categoryId || '';
    document.getElementById('editRecurring').checked = !!isRecurring;
    document.getElementById('editModal').classList.add('open');
}
function closeEdit() {
    document.getElementById('editModal').classList.remove('open');
}
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEdit();
});
</script>
@endpush
