<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TodoLog extends Model
{
    protected $fillable = ['todo_id', 'completed_date'];

    protected $casts = ['completed_date' => 'date'];

    public function todo()
    {
        return $this->belongsTo(Todo::class);
    }
}
