<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'assignee_id',
        'priority',
        'due_date',
        'project_id',
        'location',
        'latitude',
        'longitude',
        'attributes',
        'recurrence_pattern',
        'meeting_id',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'attributes' => 'array',
    ];

    protected static function booted()
    {
        static::saved(function ($task) {
            \App\Events\TaskUpdated::dispatch($task);
        });

        static::deleted(function ($task) {
            \App\Events\TaskUpdated::dispatch($task);
        });
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function timeLogs()
    {
        return $this->hasMany(TimeLog::class);
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class)->orderBy('created_at', 'desc');
    }

    public function subtasks()
    {
        return $this->hasMany(Subtask::class)->orderBy('created_at', 'asc');
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }

    public function dependencies()
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'task_id', 'depends_on_task_id')
            ->withPivot('type')
            ->withTimestamps();
    }

    public function dependents()
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'depends_on_task_id', 'task_id')
            ->withPivot('type')
            ->withTimestamps();
    }

    public function isBlocked()
    {
        // A task is blocked if any of its dependencies are NOT completed (status != 'done')
        // This is a simple logic, can be expanded based on 'type' (finish_to_start, etc.)
        return $this->dependencies()->where('status', '!=', 'done')->exists();
    }

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
}
