<?php

namespace App\Livewire;

use Livewire\Component;

class WikiManager extends Component
{
    use \Livewire\WithFileUploads;

    public $projectId;
    public $project;
    public $showCreateModal = false;
    public $showViewModal = false;
    public $selectedDocument = null;
    public $isEditing = false;

    // Form Fields
    public $title = '';
    public $content = '';
    public $file;

    public function mount($project = null)
    {
        if ($project) {
            $this->projectId = $project;
            $this->project = \App\Models\Project::findOrFail($project);
        }
    }

    public function render()
    {
        $documents = \App\Models\Document::query();

        if ($this->projectId) {
            $documents->where('project_id', $this->projectId);
        } else {
            // If no project selected, show documents for user's projects
            $documents->whereIn('project_id', auth()->user()->projects->pluck('id'));
        }

        return view('livewire.wiki-manager', [
            'documents' => $documents->orderBy('created_at', 'desc')->get(),
            'projects' => auth()->user()->projects,
        ])->layout('layouts.app');
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showCreateModal = true;
    }

    public function createDocument()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'projectId' => 'required',
            'file' => 'nullable|file|max:10240', // 10MB Max
        ]);

        $filePath = null;
        if ($this->file) {
            $filePath = $this->file->store('documents', 'public');
        }

        \App\Models\Document::create([
            'title' => $this->title,
            'content' => $this->content,
            'project_id' => $this->projectId,
            'file_path' => $filePath,
            'created_by' => auth()->id(),
        ]);

        $this->showCreateModal = false;
        $this->resetForm();
        session()->flash('message', 'Document created successfully.');
    }

    public function viewDocument($documentId)
    {
        $this->selectedDocument = \App\Models\Document::with('creator')->find($documentId);
        $this->title = $this->selectedDocument->title;
        $this->content = $this->selectedDocument->content;
        $this->projectId = $this->selectedDocument->project_id;

        $this->showViewModal = true;
        $this->isEditing = false;
    }

    public function editDocument()
    {
        $this->isEditing = true;
    }

    public function updateDocument()
    {
        if (!$this->selectedDocument)
            return;

        $this->validate([
            'title' => 'required|string|max:255',
            'file' => 'nullable|file|max:10240',
        ]);

        $updateData = [
            'title' => $this->title,
            'content' => $this->content,
        ];

        if ($this->file) {
            $updateData['file_path'] = $this->file->store('documents', 'public');
        }

        $this->selectedDocument->update($updateData);

        $this->isEditing = false;
        session()->flash('message', 'Document updated successfully.');
    }

    public function deleteDocument()
    {
        if (!$this->selectedDocument)
            return;

        $this->selectedDocument->delete();
        $this->showViewModal = false;
        $this->selectedDocument = null;
        session()->flash('message', 'Document deleted.');
    }

    private function resetForm()
    {
        $this->reset(['title', 'content', 'file', 'selectedDocument']);
    }
}
