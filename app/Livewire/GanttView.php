<?php

namespace App\Livewire;

use Livewire\Component;

class GanttView extends Component
{
    public $projectId;
    public $project;

    public function mount($project)
    {
        $this->projectId = $project;
        $this->project = \App\Models\Project::findOrFail($project);
    }

    public function render()
    {
        return view('livewire.gantt-view')->layout('layouts.app');
    }
}
