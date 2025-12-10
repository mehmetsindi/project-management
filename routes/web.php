<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/projects', \App\Livewire\ProjectManager::class)->name('projects');
    Route::get('/kanban/{project?}', \App\Livewire\KanbanBoard::class)->name('kanban');
    Route::get('/users', \App\Livewire\UserManager::class)->name('users');
    Route::get('/meetings/{project?}', \App\Livewire\MeetingManager::class)->name('meetings');
    Route::get('/gantt/{project}', \App\Livewire\GanttView::class)->name('gantt');
    Route::get('/wiki/{project?}', \App\Livewire\WikiManager::class)->name('wiki');
    Route::get('/budget/{project}', \App\Livewire\BudgetManager::class)->name('budget');
    Route::get('/client/dashboard', \App\Livewire\ClientDashboard::class)->name('client.dashboard');
});
