<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Todo;
use App\Models\TodoLog;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        $query = Todo::with('category')->where('user_id', auth()->id());

        if ($request->status === 'pending') {
            $query->where('completed', false);
        } elseif ($request->status === 'done') {
            $query->where('completed', true);
        }

        if ($request->priority) {
            $query->where('priority', $request->priority);
        }

        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->type === 'recurring') {
            $query->where('is_recurring', true);
        }

        $todos = $query->orderBy('is_recurring', 'desc')->orderBy('created_at', 'desc')->get();

        // Resolve recurring completed state from today's logs
        $today = today()->toDateString();
        $doneToday = TodoLog::whereDate('completed_date', $today)
            ->whereIn('todo_id', $todos->where('is_recurring', true)->pluck('id'))
            ->pluck('todo_id')
            ->flip();

        $todos = $todos->map(function ($todo) use ($doneToday) {
            if ($todo->is_recurring) {
                $todo->completed = $doneToday->has($todo->id);
            }
            return $todo;
        });

        if ($request->status === 'pending') {
            $todos = $todos->filter(fn($t) => !$t->completed)->values();
        } elseif ($request->status === 'done') {
            $todos = $todos->filter(fn($t) => $t->completed)->values();
        }

        $categories = Category::where('user_id', auth()->id())->orderBy('name')->get();

        return view('todos.index', compact('todos', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|max:255',
            'priority'    => 'required|in:low,medium,high',
            'due_date'    => 'nullable|date',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        Todo::create([
            ...$request->only('title', 'description', 'priority', 'due_date', 'category_id'),
            'user_id'      => auth()->id(),
            'is_recurring' => $request->boolean('is_recurring'),
        ]);

        return back()->with('success', 'Task added!');
    }

    public function update(Request $request, Todo $todo)
    {
        abort_if($todo->user_id !== auth()->id(), 403);

        $request->validate([
            'title'       => 'required|max:255',
            'priority'    => 'required|in:low,medium,high',
            'due_date'    => 'nullable|date',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $todo->update([
            ...$request->only('title', 'description', 'priority', 'due_date', 'category_id'),
            'is_recurring' => $request->boolean('is_recurring'),
        ]);

        return back()->with('success', 'Task updated!');
    }

    public function toggle(Todo $todo)
    {
        abort_if($todo->user_id !== auth()->id(), 403);

        if ($todo->is_recurring) {
            $existing = TodoLog::where('todo_id', $todo->id)
                ->whereDate('completed_date', today())
                ->first();

            $existing ? $existing->delete()
                      : TodoLog::create(['todo_id' => $todo->id, 'completed_date' => today()]);
        } else {
            $todo->update(['completed' => !$todo->completed]);
        }

        return back();
    }

    public function destroy(Todo $todo)
    {
        abort_if($todo->user_id !== auth()->id(), 403);
        $todo->delete();

        return back()->with('success', 'Task deleted!');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100|unique:categories,name,NULL,id,user_id,' . auth()->id(),
        ]);

        Category::create(['name' => $request->name, 'user_id' => auth()->id()]);

        return back()->with('success', 'Category created!');
    }

    public function destroyCategory(Category $category)
    {
        abort_if($category->user_id !== auth()->id(), 403);
        $category->delete();

        return back()->with('success', 'Category deleted!');
    }

    public function report(Request $request)
    {
        $days = max(7, min(90, (int) $request->get('days', 30)));
        $from = today()->subDays($days - 1);

        $recurringTodos = Todo::where('user_id', auth()->id())
            ->where('is_recurring', true)
            ->with('category')
            ->orderBy('title')
            ->get();

        $logs = TodoLog::whereBetween('completed_date', [$from, today()])
            ->whereIn('todo_id', $recurringTodos->pluck('id'))
            ->get()
            ->groupBy(fn($l) => $l->completed_date->toDateString())
            ->map(fn($group) => $group->pluck('todo_id')->flip());

        $dates = collect();
        for ($d = $from->copy(); $d->lte(today()); $d->addDay()) {
            $dates->push($d->copy());
        }

        $stats = $recurringTodos->map(function ($todo) use ($logs, $dates) {
            $completed = $dates->filter(fn($d) => isset($logs[$d->toDateString()]) && $logs[$d->toDateString()]->has($todo->id))->count();
            return [
                'todo'      => $todo,
                'completed' => $completed,
                'total'     => $dates->count(),
                'rate'      => $dates->count() ? round($completed / $dates->count() * 100) : 0,
            ];
        });

        return view('todos.report', compact('recurringTodos', 'logs', 'dates', 'stats', 'days'));
    }
}
