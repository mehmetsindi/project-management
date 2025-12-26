<div x-data="{ view: localStorage.getItem('taskView') || 'board' }"
    x-init="$watch('view', value => localStorage.setItem('taskView', value))" class="p-6">
    @if($this->project)
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white font-heading">
                {{ $this->project->name }}
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400 ml-2">Board</span>
            </h2>
            <div class="flex gap-3 items-center">
                <!-- View Toggle -->
                <div class="bg-gray-200 dark:bg-gray-700 p-1 rounded-lg flex items-center">
                    <button @click="view = 'board'"
                        :class="{ 'bg-white dark:bg-gray-600 shadow text-indigo-600 dark:text-white': view === 'board', 'text-gray-500 dark:text-gray-400': view !== 'board' }"
                        class="p-2 rounded-md transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </button>
                    <button @click="view = 'list'"
                        :class="{ 'bg-white dark:bg-gray-600 shadow text-indigo-600 dark:text-white': view === 'list', 'text-gray-500 dark:text-gray-400': view !== 'list' }"
                        class="p-2 rounded-md transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <button wire:click="openCreateTaskModal"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-semibold shadow-lg shadow-indigo-600/30 transition-all hover:scale-105">
                    + New Task
                </button>
                <a href="{{ route('projects') }}"
                    class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors">
                    ← Back
                </a>
            </div>
        </div>
    @endif

    @php
        $statuses = ['todo', 'in-progress', 'done'];
        $statusLabels = [
            'todo' => 'To Do',
            'in-progress' => 'In Progress',
            'done' => 'Done'
        ];
        $statusColors = [
            'todo' => 'border-orange-500',
            'in-progress' => 'border-blue-500',
            'done' => 'border-green-500'
        ];
        $statusBadges = [
            'todo' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
            'in-progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'done' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
        ];
    @endphp

    <!-- Board View -->
    <div x-show="view === 'board'" class="flex flex-col md:flex-row gap-4 overflow-x-auto h-full pb-4">
        @foreach($statuses as $status)
            <div
                class="flex-1 min-w-[300px] " ondrop="drop(event, '{{ $status }}')" ondragover="allowDrop(event)">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border-t-4 {{ $statusColors[$status] }} p-4 h-full flex flex-col">
                        <h3 class="font-bold text-lg mb-4 text-gray-800 dark:text-white">{{ $statusLabels[$status] }}</h3>
                        <div class="space-y-3 flex-1 overflow-y-auto">
                            @if(isset($this->tasks[$status]))
                                @foreach($this->tasks[$status] as $task)
                                    @php
                                        $totalTime = $task->timeLogs->sum('duration');
                                    @endphp
                                    <div draggable="true" ondragstart="drag(event, {{ $task->id }})" 
                                        class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow hover:shadow-lg transition-shadow cursor-move border border-gray-200 dark:border-gray-600"
                                        wire:click="openTaskDetail({{ $task->id }})">
                                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">{{ $task->title }}</h4>
                                        @if($task->description)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">{{ $task->description }}</p>
                                        @endif
                                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                            <div class="flex items-center gap-2">
                                                @if($task->assignee)
                                                    <div class="flex items-center gap-1">
                                                        <div class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-bold text-xs">
                                                            {{ substr($task->assignee->name, 0, 1) }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            @if($totalTime > 0)
                                                <span class="font-mono">{{ gmdate('H:i:s', $totalTime) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
        @endforeach
    </div>

    <!-- List View -->
    <div x-show="view === 'list'" class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Task</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Assignee</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Time</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($statuses as $status)
                    @if(isset($this->tasks[$status]))
                        @foreach($this->tasks[$status] as $task)
                            @php
                                $totalTime = $task->timeLogs->sum('duration');
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors cursor-pointer" wire:click="openTaskDetail({{ $task->id }})">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $task->title }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 line-clamp-1">{{ $task->description }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusBadges[$status] }}">
                                        {{ $statusLabels[$status] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        @if($task->assignee)
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-700 dark:text-indigo-300 font-bold text-xs">
                                                    {{ substr($task->assignee->name, 0, 1) }}
                                                </div>
                                                {{ $task->assignee->name }}
                                            </div>
                                        @else
                                            <span class="text-gray-500 italic">Unassigned</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-mono text-sm text-gray-500 dark:text-gray-400">
                                    {{ $totalTime > 0 ? gmdate('H:i:s', $totalTime) : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Details</button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Create Task Modal -->
    <x-dialog-modal wire:model="showCreateTaskModal">
        <x-slot name="title">
            {{ __('Create New Task') }}
        </x-slot>

        <x-slot name="content">
            <div class="mt-4">
                <x-label for="newTaskTitle" value="{{ __('Task Title') }}" />
                <x-input id="newTaskTitle" type="text" class="mt-1 block w-full" wire:model="newTaskTitle" />
                <x-input-error for="newTaskTitle" class="mt-2" />
            </div>

            <div class="mt-4">
                <div class="flex justify-between items-center mb-1">
                    <x-label for="newTaskDescription" value="{{ __('Description') }}" />
                    <button type="button" wire:click="generateDescription" class="text-xs text-indigo-400 hover:text-indigo-300 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        AI Generate
                    </button>
                </div>
                <textarea id="newTaskDescription" rows="4"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    wire:model="newTaskDescription"></textarea>
            </div>

            <div class="mt-4">
                <div class="flex items-center gap-1 mb-1">
                    <x-label for="newTaskLocation" value="{{ __('Location (Optional)') }}" />
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
                <x-input id="newTaskLocation" type="text" class="mt-1 block w-full" wire:model="newTaskLocation" placeholder="e.g., Istanbul, Turkey" />
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Location will be geocoded automatically</p>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showCreateTaskModal', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click="createTask" wire:loading.attr="disabled">
                {{ __('Create Task') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Task Detail Modal -->
    @if($selectedTask)
        <x-dialog-modal wire:model="showTaskDetailModal" maxWidth="4xl">
            <x-slot name="title">
                <div class="flex items-center justify-between">
                    <span>{{ $selectedTask->title }}</span>
                    <span class="text-sm text-gray-500 font-normal">#{{ $selectedTask->id }}</span>
                </div>
            </x-slot>

            <x-slot name="content">
                <div class="grid grid-cols-3 gap-6">
                    <!-- Main Content (2/3) -->
                    <div class="col-span-2 space-y-6">
                        <!-- Description -->
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Description</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $selectedTask->description ?: 'No description provided.' }}</p>
                        </div>

                        @if($selectedTask->location)
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    Location
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $selectedTask->location }}</p>
                            </div>
                        @endif

                        <!-- Subtasks -->
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Subtasks</h4>
                            <div class="space-y-2">
                                @forelse($selectedTask->subtasks as $subtask)
                                    <div class="flex items-center gap-3 p-2 bg-gray-50 dark:bg-gray-700 rounded">
                                        <input type="checkbox" wire:click="toggleSubtask({{ $subtask->id }})" {{ $subtask->is_completed ? 'checked' : '' }} class="rounded border-gray-300 dark:border-gray-600">
                                        <span class="text-sm {{ $subtask->is_completed ? 'line-through text-gray-400' : 'text-gray-700 dark:text-gray-300' }}">{{ $subtask->title }}</span>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 dark:text-gray-400">No subtasks yet.</p>
                                @endforelse
                            </div>
                            <div class="mt-3 flex gap-2">
                                <input type="text" wire:model="newSubtask" placeholder="Add a subtask..." class="flex-1 text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500">
                                <button wire:click="addSubtask" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-500 text-white text-sm rounded">Add</button>
                            </div>
                        </div>

                        <!-- Comments -->
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Comments</h4>
                            <div class="space-y-3 max-h-64 overflow-y-auto mb-3">
                                @forelse($selectedTask->comments as $comment)
                                    <div class="flex gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-bold text-sm flex-shrink-0">
                                            {{ substr($comment->user->name, 0, 1) }}
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="font-medium text-sm text-gray-900 dark:text-gray-100">{{ $comment->user->name }}</span>
                                                <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $comment->comment }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 dark:text-gray-400">No comments yet.</p>
                                @endforelse
                            </div>
                            <div class="flex gap-2">
                                <textarea wire:model="newComment" placeholder="Add a comment..." rows="2" class="flex-1 text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                <button wire:click="addComment" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm rounded self-end">Post</button>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar (1/3) -->
                    <div class="space-y-6">
                        <!-- Status (Editable) -->
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2 text-sm">Status</h4>
                            <select wire:change="updateTaskStatusFromModal($event.target.value)" class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="todo" {{ $selectedTask->status === 'todo' ? 'selected' : '' }}>To Do</option>
                                <option value="in-progress" {{ $selectedTask->status === 'in-progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="done" {{ $selectedTask->status === 'done' ? 'selected' : '' }}>Done</option>
                            </select>
                        </div>

                        <!-- Assignee (Editable) -->
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2 text-sm">Assigned To</h4>
                            <select wire:change="updateTaskAssignee($event.target.value)" class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Unassigned</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $selectedTask->assignee_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Time Tracking -->
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2 text-sm">Time Tracking</h4>
                            <div class="space-y-2">
                                @php
                                    $totalTime = $selectedTask->timeLogs->sum('duration');
                                    $groupedLogs = $selectedTask->timeLogs->groupBy('user_id');
                                @endphp
                                <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded">
                                    <div class="text-xs text-gray-600 dark:text-gray-400 mb-1">Total Time</div>
                                    <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ gmdate('H:i:s', $totalTime) }}</div>
                                </div>

                                <!-- Add Manual Time -->
                                <div class="flex gap-2 mt-2">
                                    <input type="text" wire:model="manualTimeDuration" placeholder="e.g. 1h 30m" class="flex-1 text-xs border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 rounded">
                                    <button wire:click="addManualTimeLog" class="px-2 py-1 bg-gray-700 hover:bg-gray-600 text-white text-xs rounded">Add</button>
                                </div>

                                @if($groupedLogs->count() > 0)
                                    <div class="text-xs text-gray-600 dark:text-gray-400 mb-1 mt-2">By User:</div>
                                    @foreach($groupedLogs as $userId => $logs)
                                        @php
                                            $userTime = $logs->sum('duration');
                                            $user = $logs->first()->user;
                                        @endphp
                                        <div class="flex justify-between items-center text-sm p-2 bg-gray-50 dark:bg-gray-700 rounded">
                                            <span class="text-gray-700 dark:text-gray-300">{{ $user->name }}</span>
                                            <span class="font-mono text-gray-600 dark:text-gray-400">{{ gmdate('H:i:s', $userTime) }}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <!-- Created Date -->
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2 text-sm">Created</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $selectedTask->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('showTaskDetailModal', false)" wire:loading.attr="disabled">
                    {{ __('Close') }}
                </x-secondary-button>
            </x-slot>
        </x-dialog-modal>
    @endif

    <script>
        function allowDrop(ev) {
            ev.preventDefault();
        }

        function drag(ev, taskId) {
            ev.dataTransfer.setData("taskId", taskId);
        }

        function drop(ev, status) {
            ev.preventDefault();
            var taskId = ev.dataTransfer.getData("taskId");
            @this.updateTaskStatus(taskId, status);
        }
    </script>
</div>