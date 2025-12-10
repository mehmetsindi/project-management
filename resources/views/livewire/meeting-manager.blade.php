<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Meetings</h2>
                <button wire:click="openCreateModal"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Schedule Meeting
                </button>
            </div>

            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($meetings as $meeting)
                    <div class="bg-white dark:bg-gray-700 border dark:border-gray-600 rounded-lg shadow-sm hover:shadow-md transition-shadow cursor-pointer"
                        wire:click="openDetailModal({{ $meeting->id }})">
                        <div class="p-4">
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $meeting->title }}</h3>
                                <span
                                    class="text-xs text-gray-500 dark:text-gray-400">{{ $meeting->start_time->format('M d, H:i') }}</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1 line-clamp-2">
                                {{ $meeting->description }}</p>
                            <div class="mt-3 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $meeting->location ?? 'Online' }}
                            </div>
                            <div class="mt-2 flex -space-x-2 overflow-hidden">
                                @foreach($meeting->attendees->take(3) as $attendee)
                                    <img class="inline-block h-6 w-6 rounded-full ring-2 ring-white dark:ring-gray-600"
                                        src="{{ $attendee->profile_photo_url }}" alt="{{ $attendee->name }}">
                                @endforeach
                                @if($meeting->attendees->count() > 3)
                                    <span
                                        class="inline-block h-6 w-6 rounded-full ring-2 ring-white dark:ring-gray-600 bg-gray-100 dark:bg-gray-600 flex items-center justify-center text-xs text-gray-500 dark:text-gray-300">+{{ $meeting->attendees->count() - 3 }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $meetings->links() }}
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    @if($showCreateModal)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                    wire:click="$set('showCreateModal', false)"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Schedule Meeting</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                                <input type="text" wire:model="title"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            @if(!$projectId)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Project</label>
                                    <select wire:model="projectId"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">Select Project</option>
                                        @foreach($projects as $proj)
                                            <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('projectId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            @endif
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                <textarea wire:model="description"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start
                                        Time</label>
                                    <div class="flex gap-2">
                                        <input type="datetime-local" wire:model="start_time"
                                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <button type="button" wire:click="setInstantMeeting"
                                            class="mt-1 px-2 py-1 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded text-xs hover:bg-green-200 dark:hover:bg-green-800 whitespace-nowrap"
                                            title="Set to Now">
                                            Now
                                        </button>
                                    </div>
                                    @error('start_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">End
                                        Time</label>
                                    <input type="datetime-local" wire:model="end_time"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('end_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                                <input type="text" wire:model="location"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    placeholder="Room 1 or Zoom Link">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Attendees</label>
                                <select multiple wire:model="selectedAttendees"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm h-32">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="createMeeting"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Schedule
                        </button>
                        <button type="button" wire:click="$set('showCreateModal', false)"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Detail/Edit Modal -->
    @if($showDetailModal && $selectedMeeting)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                    wire:click="$set('showDetailModal', false)"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $selectedMeeting->title }}</h3>
                            <button wire:click="$set('showDetailModal', false)" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Main Content -->
                            <div class="md:col-span-2 space-y-6">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 font-bold mb-1">Agenda</label>
                                    <textarea wire:model="agenda" rows="6"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        placeholder="Meeting agenda..."></textarea>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 font-bold mb-1">Minutes
                                        /
                                        Notes</label>
                                    <textarea wire:model="minutes" rows="6"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        placeholder="Meeting minutes..."></textarea>
                                </div>

                                <!-- Action Items -->
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 font-bold mb-2">Action
                                        Items</label>
                                    <div class="flex space-x-2 mb-2">
                                        <input type="text" wire:model="newActionItemTitle"
                                            class="flex-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            placeholder="New action item...">
                                        <button wire:click="createActionItem"
                                            class="bg-indigo-600 text-white px-3 py-2 rounded-md text-sm hover:bg-indigo-700">Add</button>
                                    </div>
                                    <ul
                                        class="divide-y divide-gray-200 dark:divide-gray-700 border dark:border-gray-600 rounded-md">
                                        @foreach($selectedMeeting->actionItems as $task)
                                            <li class="px-4 py-3 flex justify-between items-center bg-gray-50 dark:bg-gray-700">
                                                <span class="text-sm text-gray-900 dark:text-white">{{ $task->title }}</span>
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    {{ $task->status }}
                                                </span>
                                            </li>
                                        @endforeach
                                        @if($selectedMeeting->actionItems->isEmpty())
                                            <li class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 italic">No action
                                                items yet.</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            <!-- Sidebar -->
                            <div class="space-y-4 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Time</label>
                                    <div class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $selectedMeeting->start_time->format('M d, Y') }}<br>
                                        {{ $selectedMeeting->start_time->format('H:i') }} -
                                        {{ $selectedMeeting->end_time->format('H:i') }}
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Location</label>
                                    <div class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $selectedMeeting->location ?? 'Online' }}
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Attendees</label>
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach($selectedMeeting->attendees as $attendee)
                                            <div
                                                class="flex items-center space-x-2 bg-white dark:bg-gray-600 px-2 py-1 rounded border dark:border-gray-500">
                                                <img class="h-6 w-6 rounded-full" src="{{ $attendee->profile_photo_url }}"
                                                    alt="">
                                                <span
                                                    class="text-xs text-gray-700 dark:text-gray-200">{{ $attendee->name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="updateMeeting"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Save Changes
                        </button>
                        <button type="button" wire:click="$set('showDetailModal', false)"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Close
                        </button>
                        <button type="button" wire:click="deleteMeeting"
                            wire:confirm="Are you sure you want to delete this meeting?"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>