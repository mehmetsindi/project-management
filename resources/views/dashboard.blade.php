<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-gray-700">
                <div class="p-6 lg:p-8 bg-gray-800 border-b border-gray-700">
                    <h1 class="mt-8 text-2xl font-medium text-white">
                        Welcome to Sindi!
                    </h1>

                    <p class="mt-6 text-gray-400 leading-relaxed">
                        Sindi is your premium project management tool. Manage your projects, track time, and
                        collaborate with your team in style.
                    </p>
                </div>

                <div class="bg-gray-800 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
                    <div>
                        <div class="flex items-center">
                            <div class="h-8 w-8 bg-indigo-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                            <h2 class="ml-3 text-xl font-semibold text-white">
                                <a href="{{ route('projects') }}">Projects</a>
                            </h2>
                        </div>

                        <p class="mt-4 text-gray-400 text-sm leading-relaxed">
                            Manage your projects, assign tasks, and track progress on the Kanban board.
                        </p>

                        <p class="mt-4 text-sm">
                            <a href="{{ route('projects') }}"
                                class="inline-flex items-center font-semibold text-indigo-400 hover:text-indigo-300">
                                Go to Projects
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    class="ml-1 w-5 h-5 fill-current">
                                    <path fill-rule="evenodd"
                                        d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.5a.75.75 0 010 1.08l-3.5 3.5a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                        </p>
                    </div>

                    <div>
                        <div class="flex items-center">
                            <div class="h-8 w-8 bg-purple-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="ml-3 text-xl font-semibold text-white">
                                Time Tracking
                            </h2>
                        </div>

                        <p class="mt-4 text-gray-400 text-sm leading-relaxed">
                            Track time spent on tasks directly from the Kanban board. View reports and analyze
                            productivity.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>