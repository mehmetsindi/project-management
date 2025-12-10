<?php

namespace App\Livewire;

use Livewire\Component;

class ClientDashboard extends Component
{
    public function render()
    {
        $projects = auth()->user()->projects()->with([
            'tasks' => function ($query) {
                $query->where('status', '!=', 'archived')->latest()->take(5);
            }
        ])->get();

        return view('livewire.client-dashboard', [
            'projects' => $projects,
        ])->layout('layouts.app');
    }
}
