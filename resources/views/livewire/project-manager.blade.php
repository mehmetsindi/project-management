<div x-data="{ view: localStorage.getItem('projectView') || 'grid' }" x-init="$watch('view', value => localStorage.setItem('projectView', value))">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="font-bold text-2xl text-gray-800 dark:text-white leading-tight font-heading">
                    {{ __('Projects') }}
                </h2>
                <div class="flex items-center gap-4">
                    <!-- View Toggle -->
                    <div class="bg-gray-200 dark:bg-gray-700 p-1 rounded-lg flex items-center">
                        <button @click="view = 'grid'" :class="{ 'bg-white dark:bg-gray-600 shadow text-indigo-600 dark:text-white': view === 'grid', 'text-gray-500 dark:text-gray-400': view !== 'grid' }" class="p-2 rounded-md transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        </button>
                        <button @click="view = 'list'" :class="{ 'bg-white dark:bg-gray-600 shadow text-indigo-600 dark:text-white': view === 'list', 'text-gray-500 dark:text-gray-400': view !== 'list' }" class="p-2 rounded-md transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        </button>
                    </div>

                    <button wire:click="$set('showCreateModal', true)" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-semibold shadow-lg shadow-indigo-600/30 transition-all hover:scale-105">
                        {{ __('Create Project') }}
                    </button>
                </div>
            </div>

            <!-- Grid View -->
            <div x-show="view === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($this->projects as $project)
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden shadow-xl rounded-xl p-6 hover:border-indigo-500/50 transition-all group">
                        <div class="flex justify-between items-start">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $project->name }}</h3>
                            @if (Auth::user()->isSuperAdmin())
                                <button wire:click="deleteProject({{ $project->id }})" class="text-gray-400 hover:text-red-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                        <p class="mt-3 text-gray-600 dark:text-gray-400 text-sm leading-relaxed line-clamp-2">{{ $project->description }}</p>

                        <!-- Progress Bar -->
                        <div class="mt-4">
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-gray-500 dark:text-gray-400">Progress</span>
                                <span class="text-indigo-600 dark:text-indigo-400 font-semibold">
                                    {{ $project->tasks_count > 0 ? round(($project->completed_tasks_count / $project->tasks_count) * 100) : 0 }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500" style="width: {{ $project->tasks_count > 0 ? ($project->completed_tasks_count / $project->tasks_count) * 100 : 0 }}%"></div>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-700 pt-4">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                <span>{{ $project->tasks_count }} Tasks</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                <span>{{ $project->users->count() }} Members</span>
                            </div>
                        </div>

                        <div class="mt-6 flex gap-3">
                            <a href="{{ route('kanban', ['project' => $project->id]) }}"
                                class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest transition-all shadow-lg shadow-indigo-600/20">
                                {{ __('Open Board') }}
                            </a>
                            <a href="{{ route('gantt', ['project' => $project->id]) }}"
                                class="inline-flex justify-center items-center px-3 py-2 bg-green-600 hover:bg-green-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest transition-all shadow-lg shadow-green-600/20" title="Gantt View">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                            </a>
                            <a href="{{ route('budget', ['project' => $project->id]) }}"
                                class="inline-flex justify-center items-center px-3 py-2 bg-yellow-600 hover:bg-yellow-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest transition-all shadow-lg shadow-yellow-600/20" title="Budget">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </a>
                            <a href="{{ route('wiki', ['project' => $project->id]) }}"
                                class="inline-flex justify-center items-center px-3 py-2 bg-blue-600 hover:bg-blue-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest transition-all shadow-lg shadow-blue-600/20" title="Wiki">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                            </a>
                            <button wire:click="openImportModal({{ $project->id }})" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-white rounded-lg transition-colors" title="Import Tasks">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                            </button>
                            <button wire:click="openMembersModal({{ $project->id }})" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-white rounded-lg transition-colors" title="Manage Members">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- List View -->
            <div x-show="view === 'list'" class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Project</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Progress</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stats</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($this->projects as $project)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $project->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 line-clamp-1">{{ $project->description }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 w-1/4">
                                    <div class="flex items-center">
                                        <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-2">
                                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $project->tasks_count > 0 ? ($project->completed_tasks_count / $project->tasks_count) * 100 : 0 }}%"></div>
                                        </div>
                                        <span class="text-xs font-medium text-gray-600 dark:text-gray-400">
                                            {{ $project->tasks_count > 0 ? round(($project->completed_tasks_count / $project->tasks_count) * 100) : 0 }}%
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex gap-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                            {{ $project->tasks_count }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                            {{ $project->users->count() }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('kanban', ['project' => $project->id]) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Open</a>
                                        <a href="{{ route('gantt', ['project' => $project->id]) }}" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">Gantt</a>
                                        <a href="{{ route('budget', ['project' => $project->id]) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">Budget</a>
                                        <a href="{{ route('wiki', ['project' => $project->id]) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Wiki</a>
                                        <button wire:click="openImportModal({{ $project->id }})" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300">Import</button>
                                        <button wire:click="openMembersModal({{ $project->id }})" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300">Members</button>
                                        @if (Auth::user()->isSuperAdmin())
                                            <button wire:click="deleteProject({{ $project->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create Project Modal -->
        <x-dialog-modal wire:model="showCreateModal">
            <x-slot name="title">
                {{ __('Create New Project') }}
            </x-slot>

            <x-slot name="content">
                <div class="mt-4">
                    <x-label for="name" value="{{ __('Project Name') }}" />
                    <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" />
                    <x-input-error for="name" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-label for="description" value="{{ __('Description') }}" />
                    <textarea id="description"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        wire:model="description"></textarea>
                    <x-input-error for="description" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('showCreateModal', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-button class="ms-3" wire:click="createProject" wire:loading.attr="disabled">
                    {{ __('Create') }}
                </x-button>
            </x-slot>
        </x-dialog-modal>

        <!-- Import Tasks Modal -->
        <x-dialog-modal wire:model="showImportModal">
            <x-slot name="title">
                {{ __('Import Tasks') }}
            </x-slot>

            <x-slot name="content">
                <div class="mt-4">
                    <x-label for="importText" value="{{ __('Paste Tasks (Format: 1-Task Title...)') }}" />
                    <textarea id="importText" rows="10"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        wire:model="importText"></textarea>
                    <x-input-error for="importText" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('showImportModal', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-button class="ms-3" wire:click="importTasks" wire:loading.attr="disabled">
                    {{ __('Import') }}
                </x-button>
            </x-slot>
        </x-dialog-modal>

        <!-- Manage Members Modal -->
        <x-dialog-modal wire:model="showMembersModal">
            <x-slot name="title">
                {{ __('Manage Members') }}
            </x-slot>

            <x-slot name="content">
                <div class="mt-4">
                    <x-label value="{{ __('Add New Member') }}" />
                    <div class="flex gap-2 mt-2">
                        <div class="flex-1">
                            <select wire:model="selectedMemberToAdd"
                                class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">{{ __('Select a user to add...') }}</option>
                                @foreach($this->availableUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            <x-input-error for="selectedMemberToAdd" class="mt-2" />
                        </div>
                        <x-button wire:click="addMember" wire:loading.attr="disabled">
                            {{ __('Add') }}
                        </x-button>
                    </div>
                    @if($this->availableUsers->isEmpty())
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 italic">{{ __('All users are already members of this project.') }}</p>
                    @endif
                </div>

                <div class="mt-6">
                    <h4 class="font-medium text-gray-900 dark:text-gray-200 mb-3">Current Members</h4>
                    <div class="space-y-3">
                        @if($editingProject)
                            @foreach($editingProject->users as $user)
                                <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-bold">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                    @if($user->id !== $editingProject->created_by)
                                        <button wire:click="removeMember({{ $user->id }})" class="text-red-500 hover:text-red-700 text-sm">Remove</button>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Owner</span>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('showMembersModal', false)" wire:loading.attr="disabled">
                    {{ __('Close') }}
                </x-secondary-button>
            </x-slot>
        </x-dialog-modal>
    </div>
</div>