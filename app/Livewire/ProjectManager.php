<?php

namespace App\Livewire;

use App\Models\Project;
use App\Services\TaskParser;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ProjectManager extends Component
{
    public $name = '';
    public $description = '';
    public $importText = '';
    public $selectedProjectId = null;
    public $showCreateModal = false;
    public $showImportModal = false;
    public $showMembersModal = false;
    public $editingProject = null;
    public $selectedMemberToAdd = '';

    public function openMembersModal($projectId)
    {
        $this->selectedProjectId = $projectId;
        $this->editingProject = Project::find($projectId);
        $this->selectedMemberToAdd = '';
        $this->showMembersModal = true;
    }

    #[Computed]
    public function availableUsers()
    {
        if (!$this->editingProject) {
            return collect();
        }

        $existingMemberIds = $this->editingProject->users->pluck('id')->toArray();

        return \App\Models\User::whereNotIn('id', $existingMemberIds)
            ->orderBy('name')
            ->get();
    }

    public function addMember()
    {
        $this->validate([
            'selectedMemberToAdd' => 'required|exists:users,id',
        ]);

        $user = \App\Models\User::find($this->selectedMemberToAdd);

        if ($this->editingProject->users()->where('user_id', $user->id)->exists()) {
            $this->addError('selectedMemberToAdd', 'User is already a member of this project.');
            return;
        }

        $this->editingProject->users()->attach($user->id, ['role' => 'member']);
        $this->selectedMemberToAdd = '';
        $this->editingProject->load('users'); // Refresh relationship
    }

    public function removeMember($userId)
    {
        // Prevent removing yourself if you are the only admin? For now just allow removal.
        // Maybe check if user is creator?
        if ($this->editingProject->created_by == $userId) {
            // Cannot remove creator
            return;
        }

        $this->editingProject->users()->detach($userId);
        $this->editingProject->load('users');
    }

    #[Computed]
    public function projects()
    {
        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            return Project::withCount([
                'tasks',
                'tasks as completed_tasks_count' => function ($query) {
                    $query->where('status', 'done');
                }
            ])->with('users')->get();
        }
        return $user->projects()->withCount([
            'tasks',
            'tasks as completed_tasks_count' => function ($query) {
                $query->where('status', 'done');
            }
        ])->with('users')->get();
    }

    public function createProject()
    {
        $this->validate([
            'name' => 'required|min:3',
        ]);

        $project = Project::create([
            'name' => $this->name,
            'description' => $this->description,
            'created_by' => Auth::id(),
        ]);

        // Add creator as admin
        $project->users()->attach(Auth::id(), ['role' => 'admin']);

        $this->reset(['name', 'description', 'showCreateModal']);
        $this->dispatch('project-created');
    }

    public function openImportModal($projectId)
    {
        $this->selectedProjectId = $projectId;
        $this->showImportModal = true;
    }

    public function importTasks()
    {
        $this->validate([
            'importText' => 'required',
            'selectedProjectId' => 'required|exists:projects,id',
        ]);

        $parser = new TaskParser();
        $parser->parseAndCreateTasks($this->importText, $this->selectedProjectId);

        $this->reset(['importText', 'selectedProjectId', 'showImportModal']);
        $this->dispatch('tasks-imported');
    }

    public function deleteProject($projectId)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        Project::find($projectId)->delete();
    }

    public function render()
    {
        return view('livewire.project-manager')->layout('layouts.app');
    }
}
