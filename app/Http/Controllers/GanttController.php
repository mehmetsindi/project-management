<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GanttController extends Controller
{
    public function getData($projectId)
    {
        $project = \App\Models\Project::findOrFail($projectId);

        $tasks = $project->tasks()
            ->with(['dependencies', 'assignee'])
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'name' => $task->title,
                    'start' => $task->created_at->format('Y-m-d'), // Ideally we should have a start_date column, using created_at as fallback
                    'end' => $task->due_date ? $task->due_date->format('Y-m-d') : $task->created_at->addDays(1)->format('Y-m-d'),
                    'progress' => $task->status === 'done' ? 100 : 0, // Simplified progress
                    'dependencies' => $task->dependencies->pluck('id')->implode(','),
                    'custom_class' => $this->getTaskClass($task->status),
                ];
            });

        return response()->json($tasks);
    }

    private function getTaskClass($status)
    {
        return match ($status) {
            'done' => 'bar-completed',
            'in_progress' => 'bar-running',
            'todo' => 'bar-todo',
            default => 'bar-default',
        };
    }
}
