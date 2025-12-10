<?php

namespace App\Livewire;

use App\Models\Task;
use Livewire\Component;
use Livewire\Attributes\Computed;

class KanbanBoard extends Component
{
    public $users;
    public $project;
    public $projectId;
    public $newTaskLocation = '';
    public $newTaskTitle = '';
    public $newTaskDescription = '';
    public $showCreateTaskModal = false;
    public $showTaskDetailModal = false;
    public $selectedTask = null;
    public $newComment = '';
    public $newSubtask = '';
    public $manualTimeDuration = '';

    // Shipping properties
    public $showShippingModal = false;
    public $shippingOrigin = '';
    public $shippingDestination = '';
    public $shippingWeight = 1.0;
    public $availableRates = [];
    public $selectedCarrier = '';

    public function getListeners()
    {
        return [
            "echo-private:projects.{$this->projectId},TaskUpdated" => '$refresh',
        ];
    }

    public function mount($project = null)
    {
        if ($project) {
            $this->projectId = $project;
            $this->project = \App\Models\Project::findOrFail($project);

            // Authorization check
            if (!auth()->user()->isSuperAdmin() && !$this->project->users()->where('user_id', auth()->id())->exists()) {
                abort(403);
            }

            $this->users = $this->project->users;
        } else {
            $this->users = \App\Models\User::all();
        }
    }

    #[Computed]
    public function tasks()
    {
        $query = Task::with(['assignee', 'timeLogs']);

        if ($this->projectId) {
            $query->where('project_id', $this->projectId);
        }

        return $query->get()->groupBy('status');
    }

    public function openTaskDetail($taskId)
    {
        $this->selectedTask = Task::with(['assignee', 'timeLogs.user', 'comments.user', 'subtasks', 'shipment'])->find($taskId);
        $this->showTaskDetailModal = true;
    }

    public function addComment()
    {
        if (empty($this->newComment)) {
            return;
        }

        $this->selectedTask->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $this->newComment,
        ]);

        $this->newComment = '';
        $this->selectedTask->load('comments.user');
    }

    public function addSubtask()
    {
        if (empty($this->newSubtask)) {
            return;
        }

        $this->selectedTask->subtasks()->create([
            'title' => $this->newSubtask,
            'is_completed' => false,
        ]);

        $this->newSubtask = '';
        $this->selectedTask->load('subtasks');
    }

    public function toggleSubtask($subtaskId)
    {
        $subtask = \App\Models\Subtask::find($subtaskId);
        if ($subtask) {
            $subtask->update(['is_completed' => !$subtask->is_completed]);
            $this->selectedTask->load('subtasks');
        }
    }

    public function updateTaskStatusFromModal($newStatus)
    {
        if ($this->selectedTask) {
            $this->selectedTask->update(['status' => $newStatus]);
            $this->selectedTask->refresh();
        }
    }

    public function updateTaskAssignee($userId)
    {
        if ($this->selectedTask) {
            $this->selectedTask->update(['assignee_id' => $userId ?: null]);
            $this->selectedTask->load('assignee');
        }
    }

    public function addManualTimeLog()
    {
        if (empty($this->manualTimeDuration) || !$this->selectedTask) {
            return;
        }

        // Parse duration (e.g., "1h 30m" or "90")
        $minutes = 0;
        if (preg_match('/(\d+)h/', $this->manualTimeDuration, $matches)) {
            $minutes += (int) $matches[1] * 60;
        }
        if (preg_match('/(\d+)m/', $this->manualTimeDuration, $matches)) {
            $minutes += (int) $matches[1];
        }
        if ($minutes === 0 && is_numeric($this->manualTimeDuration)) {
            $minutes = (int) $this->manualTimeDuration;
        }

        if ($minutes > 0) {
            $this->selectedTask->timeLogs()->create([
                'user_id' => auth()->id(),
                'start_time' => now()->subMinutes($minutes),
                'end_time' => now(),
                'duration' => $minutes * 60, // Store in seconds
            ]);

            $this->manualTimeDuration = '';
            $this->selectedTask->load('timeLogs.user');
        }
    }

    public function updateTaskStatus($taskId, $newStatus)
    {
        $task = Task::find($taskId);
        if ($task) {
            $task->update(['status' => $newStatus]);
        }
    }

    public function assignUser($taskId, $userId)
    {
        $task = Task::find($taskId);
        if ($task) {
            $task->update(['assignee_id' => $userId ?: null]);
        }
    }

    public function toggleTimer($taskId)
    {
        $task = Task::find($taskId);
        if (!$task)
            return;

        $activeLog = $task->timeLogs()->whereNull('end_time')->where('user_id', auth()->id())->first();

        if ($activeLog) {
            // Stop timer
            $activeLog->update([
                'end_time' => now(),
                'duration' => now()->diffInSeconds($activeLog->start_time)
            ]);
        } else {
            // Start timer
            $task->timeLogs()->create([
                'user_id' => auth()->id(),
                'start_time' => now(),
            ]);
        }
    }

    public function openCreateTaskModal()
    {
        $this->showCreateTaskModal = true;
    }

    public function generateDescription()
    {
        if (empty($this->newTaskTitle)) {
            return;
        }

        $aiService = new \App\Services\GeminiAIService();
        $this->newTaskDescription = $aiService->generateTaskDescription($this->newTaskTitle);
    }

    public function createTask()
    {
        $this->validate([
            'newTaskTitle' => 'required|min:3',
        ]);

        $taskData = [
            'title' => $this->newTaskTitle,
            'description' => $this->newTaskDescription,
            'status' => 'todo',
            'project_id' => $this->projectId,
        ];

        // If location is provided, geocode it
        if (!empty($this->newTaskLocation)) {
            $mapsService = new \App\Services\GoogleMapsService();
            $geocoded = $mapsService->geocode($this->newTaskLocation);

            $taskData['location'] = $geocoded['formatted_address'];
            $taskData['latitude'] = $geocoded['lat'];
            $taskData['longitude'] = $geocoded['lng'];
        }

        Task::create($taskData);

        $this->reset(['newTaskTitle', 'newTaskDescription', 'newTaskLocation', 'showCreateTaskModal']);
    }

    // Shipping Methods
    public function openShippingModal()
    {
        if (!$this->selectedTask) {
            return;
        }

        // Pre-fill with task location if available
        if ($this->selectedTask->location) {
            $this->shippingDestination = $this->selectedTask->location;
        }

        $this->showShippingModal = true;
    }

    public function calculateShippingRates()
    {
        $this->validate([
            'shippingOrigin' => 'required|string',
            'shippingDestination' => 'required|string',
            'shippingWeight' => 'required|numeric|min:0.1',
        ]);

        $carrierService = new \App\Services\CarrierService();
        $this->availableRates = $carrierService->calculateRates(
            $this->shippingOrigin,
            $this->shippingDestination,
            $this->shippingWeight
        );
    }

    public function createShipment()
    {
        if (empty($this->selectedCarrier)) {
            session()->flash('error', 'Please select a carrier');
            return;
        }

        $carrierService = new \App\Services\CarrierService();
        $shipmentData = $carrierService->createShipment(
            $this->selectedCarrier,
            $this->shippingOrigin,
            $this->shippingDestination
        );

        // Find the selected rate to get the cost
        $selectedRate = collect($this->availableRates)->firstWhere('carrier_code', $this->selectedCarrier);

        $this->selectedTask->shipment()->create([
            'carrier' => $shipmentData['carrier'],
            'tracking_number' => $shipmentData['tracking_number'],
            'origin' => $this->shippingOrigin,
            'destination' => $this->shippingDestination,
            'estimated_delivery' => $shipmentData['estimated_delivery'],
            'status' => $shipmentData['status'],
            'shipping_cost' => $selectedRate['cost'] ?? 0,
            'weight' => $this->shippingWeight,
        ]);

        $this->selectedTask->load('shipment');
        $this->reset(['showShippingModal', 'shippingOrigin', 'shippingDestination', 'shippingWeight', 'availableRates', 'selectedCarrier']);

        session()->flash('message', 'Shipment created successfully!');
    }

    public function updateShipmentStatus($newStatus)
    {
        if (!$this->selectedTask || !$this->selectedTask->shipment) {
            return;
        }

        $carrierService = new \App\Services\CarrierService();
        $carrierService->updateShipmentStatus(
            $this->selectedTask->shipment->tracking_number,
            $newStatus
        );

        $this->selectedTask->shipment->update([
            'status' => $newStatus,
        ]);

        if ($newStatus === \App\Models\Shipment::STATUS_DELIVERED) {
            $this->selectedTask->shipment->update([
                'actual_delivery' => now(),
            ]);
        }

        $this->selectedTask->load('shipment');
        session()->flash('message', 'Shipment status updated!');
    }

    public function render()
    {
        return view('livewire.kanban-board')
            ->layout('layouts.app');
    }
}
