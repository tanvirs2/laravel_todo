<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = ['title', 'description', 'priority', 'due_date', 'completed', 'category_id', 'is_recurring'];

    protected $casts = [
        'completed'    => 'boolean',
        'is_recurring' => 'boolean',
        'due_date'     => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function logs()
    {
        return $this->hasMany(TodoLog::class);
    }
}
