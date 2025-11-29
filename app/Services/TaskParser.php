<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Str;

class TaskParser
{
    public function parseAndCreateTasks(string $text, ?int $projectId = null)
    {
        // Split by new lines or specific delimiters
        // The text seems to have "1-...", "2-..." format.

        $lines = explode("\n", $text);
        $tasks = [];
        $currentTask = null;

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line))
                continue;

            // Check if line starts with a number followed by dash or dot
            if (preg_match('/^(\d+)[\-\.](.*)/', $line, $matches)) {
                // Save previous task if exists
                if ($currentTask) {
                    $tasks[] = $currentTask;
                }

                // Start new task
                $currentTask = [
                    'title' => trim($matches[2]),
                    'description' => '',
                    'status' => 'todo',
                ];
            } else {
                // Append to description of current task
                if ($currentTask) {
                    $currentTask['description'] .= $line . "\n";
                }
            }
        }

        // Add last task
        if ($currentTask) {
            $tasks[] = $currentTask;
        }

        // Save to DB
        foreach ($tasks as $taskData) {
            $fullTitle = $taskData['title'];
            $title = Str::limit($fullTitle, 100);
            $description = $taskData['description'];

            if ($title !== $fullTitle) {
                $description = $fullTitle . "\n\n" . $description;
            }

            Task::create([
                'title' => $title,
                'description' => trim($description),
                'status' => 'todo',
                'project_id' => $projectId,
            ]);
        }

        return count($tasks);
    }
}
