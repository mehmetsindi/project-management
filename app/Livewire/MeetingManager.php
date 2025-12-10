<?php

namespace App\Livewire;

use Livewire\Component;

class MeetingManager extends Component
{
    use \Livewire\WithPagination;

    public $projectId;
    public $project;
    public $projects = []; // Added projects list
    public $showCreateModal = false;
    public $showDetailModal = false;
    public $selectedMeeting = null;

    // Form Fields
    public $title = '';
    public $description = '';
    public $start_time = '';
    public $end_time = '';
    public $location = '';
    public $agenda = '';
    public $minutes = '';
    public $selectedAttendees = [];

    // Action Item Fields
    public $newActionItemTitle = '';

    public function mount($project = null)
    {
        if ($project) {
            $this->projectId = $project;
            $this->project = \App\Models\Project::findOrFail($project);
        } else {
            // Load projects based on user role
            if (auth()->user()->isSuperAdmin()) {
                $this->projects = \App\Models\Project::all();
            } else {
                $this->projects = auth()->user()->projects;
            }
        }
    }

    public function render()
    {
        $meetings = \App\Models\Meeting::query();

        if ($this->projectId) {
            $meetings->where('project_id', $this->projectId);
        } else {
            // If no project selected, show meetings for user's projects
            $meetings->whereIn('project_id', auth()->user()->projects->pluck('id'));
        }

        return view('livewire.meeting-manager', [
            'meetings' => $meetings->orderBy('start_time', 'desc')->paginate(10),
            'users' => $this->project ? $this->project->users : \App\Models\User::all(),
        ])->layout('layouts.app');
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function setInstantMeeting()
    {
        $now = now();
        $this->start_time = $now->format('Y-m-d\TH:i');
        $this->end_time = $now->addHour()->format('Y-m-d\TH:i');
    }

    public function createMeeting()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'projectId' => 'required',
        ]);

        $meeting = \App\Models\Meeting::create([
            'title' => $this->title,
            'description' => $this->description,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'project_id' => $this->projectId,
            'location' => $this->location,
            'agenda' => $this->agenda,
        ]);

        $meeting->attendees()->sync($this->selectedAttendees);

        $this->showCreateModal = false;
        $this->resetForm();
        session()->flash('message', 'Meeting scheduled successfully.');
    }

    public function openDetailModal($meetingId)
    {
        $this->selectedMeeting = \App\Models\Meeting::with(['attendees', 'actionItems.assignee'])->find($meetingId);
        $this->title = $this->selectedMeeting->title;
        $this->description = $this->selectedMeeting->description;
        $this->start_time = $this->selectedMeeting->start_time->format('Y-m-d\TH:i');
        $this->end_time = $this->selectedMeeting->end_time->format('Y-m-d\TH:i');
        $this->location = $this->selectedMeeting->location;
        $this->agenda = $this->selectedMeeting->agenda;
        $this->minutes = $this->selectedMeeting->minutes;
        $this->selectedAttendees = $this->selectedMeeting->attendees->pluck('id')->toArray();

        $this->showDetailModal = true;
    }

    public function updateMeeting()
    {
        if (!$this->selectedMeeting)
            return;

        $this->selectedMeeting->update([
            'title' => $this->title,
            'description' => $this->description,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'location' => $this->location,
            'agenda' => $this->agenda,
            'minutes' => $this->minutes,
        ]);

        $this->selectedMeeting->attendees()->sync($this->selectedAttendees);

        session()->flash('message', 'Meeting updated.');
        $this->showDetailModal = false;
    }

    public function deleteMeeting()
    {
        if (!$this->selectedMeeting)
            return;

        $this->selectedMeeting->delete();
        $this->showDetailModal = false;
        $this->selectedMeeting = null;
        session()->flash('message', 'Meeting deleted successfully.');
    }

    public function createActionItem()
    {
        if (empty($this->newActionItemTitle) || !$this->selectedMeeting)
            return;

        \App\Models\Task::create([
            'title' => $this->newActionItemTitle,
            'project_id' => $this->selectedMeeting->project_id,
            'meeting_id' => $this->selectedMeeting->id,
            'status' => 'todo',
            'description' => 'Action item from meeting: ' . $this->selectedMeeting->title,
        ]);

        $this->newActionItemTitle = '';
        $this->selectedMeeting->load('actionItems.assignee');
    }

    private function resetForm()
    {
        $this->reset(['title', 'description', 'start_time', 'end_time', 'location', 'agenda', 'minutes', 'selectedAttendees']);
    }
}
