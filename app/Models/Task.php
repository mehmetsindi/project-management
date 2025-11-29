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
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

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
}
