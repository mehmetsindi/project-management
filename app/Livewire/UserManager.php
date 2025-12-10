<?php

namespace App\Livewire;

use Livewire\Component;

class UserManager extends Component
{
    use \Livewire\WithPagination;

    public $showEditModal = false;
    public $editingUser = null;
    public $selectedRole = '';
    public $selectedDepartment = '';
    public $selectedTeams = [];

    public $name = '';
    public $email = '';
    public $password = '';

    public function render()
    {
        return view('livewire.user-manager', [
            'users' => \App\Models\User::with(['roles', 'department', 'teams'])->paginate(10),
            'roles' => \Spatie\Permission\Models\Role::all(),
            'departments' => \App\Models\Department::all(),
            'teams' => \App\Models\Team::all(),
        ])->layout('layouts.app');
    }

    public function editUser($userId)
    {
        $this->editingUser = \App\Models\User::with(['roles', 'teams'])->find($userId);
        $this->name = $this->editingUser->name;
        $this->email = $this->editingUser->email;
        $this->selectedRole = $this->editingUser->roles->first()?->name ?? '';
        $this->selectedDepartment = $this->editingUser->department_id;
        $this->selectedTeams = $this->editingUser->teams->pluck('id')->toArray();
        $this->showEditModal = true;
    }

    public function createUser()
    {
        $this->reset(['editingUser', 'name', 'email', 'password', 'selectedRole', 'selectedDepartment', 'selectedTeams']);
        $this->showEditModal = true;
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($this->editingUser ? $this->editingUser->id : 'NULL'),
            'selectedRole' => 'required',
        ];

        if (!$this->editingUser) {
            $rules['password'] = 'required|string|min:8';
        }

        $this->validate($rules);

        if ($this->editingUser) {
            $this->editingUser->update([
                'name' => $this->name,
                'email' => $this->email,
                'department_id' => $this->selectedDepartment ?: null,
            ]);
            $user = $this->editingUser;
            $message = 'User updated successfully.';
        } else {
            $user = \App\Models\User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
                'department_id' => $this->selectedDepartment ?: null,
            ]);
            $message = 'User created successfully.';
        }

        $user->syncRoles([$this->selectedRole]);
        $user->teams()->sync($this->selectedTeams);

        $this->showEditModal = false;
        $this->reset(['editingUser', 'name', 'email', 'password', 'selectedRole', 'selectedDepartment', 'selectedTeams']);

        session()->flash('message', $message);
    }

    public function deleteUser($userId)
    {
        $user = \App\Models\User::find($userId);
        if ($user) {
            $user->delete();
            session()->flash('message', 'User deleted successfully.');
        }
    }

    public function cancel()
    {
        $this->showEditModal = false;
        $this->reset(['editingUser', 'name', 'email', 'selectedRole', 'selectedDepartment', 'selectedTeams']);
    }
}
