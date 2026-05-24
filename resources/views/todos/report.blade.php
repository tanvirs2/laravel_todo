@extends('layouts.app')

@section('title', 'Report – Todo App')

@push('styles')
<style>
    h1 { font-size: 1.8rem; margin-bottom: 20px; }
    h2 { font-size: 1rem; margin-bottom: 16px; color: #555; }
    .day-filter { display: flex; gap: 8px; margin-bottom: 20px; }
    .day-filter a { padding: 6px 14px; border-radius: 20px; text-decoration: none; background: #e5e7eb; color: #333; font-size: .85rem; }
    .day-filter a.active { background: #4f46e5; color: #fff; }
    .card { background: #fff; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,.1); overflow-x: auto; }
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px; margin-bottom: 20px; }
    .stat-card { background: #fff; border-radius: 8px; padding: 16px; box-shadow: 0 1px 3px rgba(0,0,0,.1); }
    .stat-title { font-size: .8rem; color: #888; margin-bottom: 4px; }
    .stat-value { font-size: 1.4rem; font-weight: 700; color: #4f46e5; }
    .stat-sub { font-size: .75rem; color: #aaa; margin-top: 2px; }
    .rate-bar { height: 6px; background: #e5e7eb; border-radius: 3px; margin-top: 8px; }
    .rate-fill { height: 100%; border-radius: 3px; background: #4f46e5; }
    .rate-fill.good { background: #10b981; }
    .rate-fill.mid  { background: #f59e0b; }
    .rate-fill.low  { background: #ef4444; }
    table { border-collapse: collapse; width: 100%; font-size: .8rem; }
    th, td { padding: 6px 8px; text-align: center; border: 1px solid #f0f0f0; white-space: nowrap; }
    th { background: #f9fafb; font-weight: 600; }
    td.task-name { text-align: left; font-weight: 500; max-width: 200px; overflow: hidden; text-overflow: ellipsis; }
    td.today-col { background: #eff6ff; }
    .dot-done { display: inline-block; width: 18px; height: 18px; border-radius: 50%; background: #10b981; color: #fff; font-size: .7rem; line-height: 18px; }
    .dot-miss { display: inline-block; width: 18px; height: 18px; border-radius: 50%; background: #f3f4f6; color: #ccc; font-size: .7rem; line-height: 18px; }
    .badge-cat { display: inline-block; padding: 1px 6px; border-radius: 8px; font-size: .7rem; background: #ede9fe; color: #5b21b6; margin-left: 4px; }
    .empty { text-align: center; padding: 40px; color: #aaa; }
</style>
@endpush

@section('content')
<h1>📊 Daily Task Report</h1>

<div class="day-filter">
    <a href="/report?days=7"  class="{{ $days==7  ? 'active' : '' }}">Last 7 days</a>
    <a href="/report?days=14" class="{{ $days==14 ? 'active' : '' }}">Last 14 days</a>
    <a href="/report?days=30" class="{{ $days==30 ? 'active' : '' }}">Last 30 days</a>
    <a href="/report?days=90" class="{{ $days==90 ? 'active' : '' }}">Last 90 days</a>
</div>

@if($recurringTodos->isEmpty())
    <div class="card"><p class="empty">No daily tasks yet. Mark tasks as "Repeats daily" to track them here.</p></div>
@else
    <div class="stats-grid">
        @foreach($stats as $s)
        <div class="stat-card">
            <div class="stat-title">
                {{ $s['todo']->title }}
                @if($s['todo']->category)
                    <span class="badge-cat">{{ $s['todo']->category->name }}</span>
                @endif
            </div>
            <div class="stat-value">{{ $s['rate'] }}%</div>
            <div class="stat-sub">{{ $s['completed'] }} / {{ $s['total'] }} days completed</div>
            <div class="rate-bar">
                <div class="rate-fill {{ $s['rate'] >= 80 ? 'good' : ($s['rate'] >= 50 ? 'mid' : 'low') }}" style="width:{{ $s['rate'] }}%"></div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="card">
        <h2>Completion Grid</h2>
        <table>
            <thead>
                <tr>
                    <th style="text-align:left">Task</th>
                    @foreach($dates as $d)
                        <th class="{{ $d->isToday() ? 'today-col' : '' }}">
                            {{ $d->format('M j') }}<br>
                            <span style="font-weight:400;color:#aaa">{{ $d->format('D') }}</span>
                        </th>
                    @endforeach
                    <th>Rate</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stats as $s)
                <tr>
                    <td class="task-name">
                        {{ $s['todo']->title }}
                        @if($s['todo']->category)
                            <span class="badge-cat">{{ $s['todo']->category->name }}</span>
                        @endif
                    </td>
                    @foreach($dates as $d)
                        @php $done = isset($logs[$d->toDateString()]) && $logs[$d->toDateString()]->has($s['todo']->id); @endphp
                        <td class="{{ $d->isToday() ? 'today-col' : '' }}">
                            @if($done)
                                <span class="dot-done">✓</span>
                            @else
                                <span class="dot-miss">–</span>
                            @endif
                        </td>
                    @endforeach
                    <td><strong>{{ $s['rate'] }}%</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
