<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Client Dashboard</h2>
            <p class="text-gray-600">Welcome back, {{ auth()->user()->name }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse ($projects as $project)
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $project->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $project->description }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                {{ $project->tasks->count() }} Active Tasks
                            </span>
                        </div>

                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Recent Updates</h4>
                            <div class="space-y-3">
                                @forelse ($project->tasks as $task)
                                                    <div class="flex items-center justify-between text-sm">
                                                        <div class="flex items-center gap-2">
                                                            <span
                                                                class="w-2 h-2 rounded-full 
                                                                        {{ $task->status === 'completed' ? 'bg-green-500' :
                                    ($task->status === 'in_progress' ? 'bg-blue-500' : 'bg-gray-400') }}">
                                                            </span>
                                                            <span class="text-gray-700">{{ $task->title }}</span>
                                                        </div>
                                                        <span class="text-gray-500 text-xs">{{ $task->updated_at->diffForHumans() }}</span>
                                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 italic">No recent activity.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
                            <a href="{{ route('kanban', ['project' => $project->id]) }}"
                                class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                View Project Board &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 text-center">
                    <p class="text-gray-500">You are not assigned to any projects yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>