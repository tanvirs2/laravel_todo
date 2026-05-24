<div class="todo-item {{ $todo->is_recurring ? 'recurring' : '' }}">
    <form method="POST" action="/todos/{{ $todo->id }}/toggle" style="margin-top:3px">
        @csrf @method('PATCH')
        <input type="checkbox" onchange="this.form.submit()" {{ $todo->completed ? 'checked' : '' }}>
    </form>
    <div style="flex:1">
        <div class="todo-title {{ $todo->completed ? 'done' : '' }}">{{ $todo->title }}</div>
        @if($todo->description)
            <div style="font-size:.85rem;color:#666;margin-top:2px">{{ $todo->description }}</div>
        @endif
        <div class="todo-meta">
            <span class="badge badge-{{ $todo->priority }}">{{ ucfirst($todo->priority) }}</span>
            @if($todo->is_recurring)
                <span class="badge badge-recurring">🔁 Daily</span>
            @endif
            @if($todo->category)
                <span class="badge badge-cat">{{ $todo->category->name }}</span>
            @endif
            @if($todo->due_date)
                <span class="{{ !$todo->completed && $todo->due_date->isPast() ? 'overdue' : '' }}">
                    Due: {{ $todo->due_date->format('M j, Y') }}
                    @if(!$todo->completed && $todo->due_date->isPast()) (overdue) @endif
                </span>
            @endif
        </div>
    </div>
    <div class="todo-actions">
        <button class="btn-sm btn-primary" onclick="openEdit({{ $todo->id }}, '{{ addslashes($todo->title) }}', '{{ addslashes($todo->description) }}', '{{ $todo->priority }}', '{{ $todo->due_date?->format('Y-m-d') }}', {{ $todo->category_id ?? 'null' }}, {{ $todo->is_recurring ? 'true' : 'false' }})">Edit</button>
        <form method="POST" action="/todos/{{ $todo->id }}">
            @csrf @method('DELETE')
            <button type="submit" class="btn-sm btn-danger" onclick="return confirm('Delete this task?')">Delete</button>
        </form>
    </div>
</div>
