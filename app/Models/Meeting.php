<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'title',
        'description',
        'start_time',
        'end_time',
        'project_id',
        'location',
        'agenda',
        'minutes',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'meeting_user')
            ->withPivot('status')
            ->withTimestamps();
    }

    // Action Items are just tasks linked to this meeting (we need to add meeting_id to tasks first)
    // For now, let's assume we will add a meeting_id column to tasks in the next step
    public function actionItems()
    {
        return $this->hasMany(Task::class);
    }
}
