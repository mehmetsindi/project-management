<?php

namespace App\Livewire;

use Livewire\Component;

class BudgetManager extends Component
{
    public $projectId;
    public $project;
    public $showExpenseModal = false;
    public $showBudgetModal = false;

    // Budget Form
    public $budgetAmount;
    public $currency = 'USD';

    // Expense Form
    public $description;
    public $amount;
    public $date;
    public $category;

    public function mount($project)
    {
        $this->projectId = $project;
        $this->project = \App\Models\Project::findOrFail($project);
        $this->budgetAmount = $this->project->budget;
        $this->currency = $this->project->currency;
        $this->date = now()->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.budget-manager', [
            'expenses' => $this->project->expenses()->orderBy('date', 'desc')->get(),
            'totalSpent' => $this->project->expenses()->sum('amount'),
        ])->layout('layouts.app');
    }

    public function updateBudget()
    {
        $this->validate([
            'budgetAmount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
        ]);

        $this->project->update([
            'budget' => $this->budgetAmount,
            'currency' => $this->currency,
        ]);

        $this->showBudgetModal = false;
        session()->flash('message', 'Budget updated successfully.');
    }

    public function addExpense()
    {
        $this->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'category' => 'nullable|string|max:50',
        ]);

        $this->project->expenses()->create([
            'description' => $this->description,
            'amount' => $this->amount,
            'date' => $this->date,
            'category' => $this->category,
            'user_id' => auth()->id(),
        ]);

        $this->showExpenseModal = false;
        $this->reset(['description', 'amount', 'category']);
        $this->date = now()->format('Y-m-d');
        session()->flash('message', 'Expense added successfully.');
    }

    public function deleteExpense($expenseId)
    {
        $expense = \App\Models\Expense::find($expenseId);
        if ($expense && $expense->project_id == $this->projectId) {
            $expense->delete();
            session()->flash('message', 'Expense deleted.');
        }
    }
}
