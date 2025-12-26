<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Gantt Chart: {{ $project->name }}</h2>
                <div class="flex space-x-2">
                    <button onclick="changeViewMode('Day')"
                        class="px-3 py-1 bg-gray-200 dark:bg-gray-600 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-500 text-sm">Day</button>
                    <button onclick="changeViewMode('Week')"
                        class="px-3 py-1 bg-gray-200 dark:bg-gray-600 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-500 text-sm">Week</button>
                    <button onclick="changeViewMode('Month')"
                        class="px-3 py-1 bg-gray-200 dark:bg-gray-600 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-500 text-sm">Month</button>
                </div>
            </div>

            <div id="gantt-chart" class="overflow-x-auto"></div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.css">
        <style>
            .bar-completed .bar {
                fill: #10B981;
            }

            .bar-running .bar {
                fill: #3B82F6;
            }

            .bar-todo .bar {
                fill: #9CA3AF;
            }

            .bar-default .bar {
                fill: #6B7280;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.min.js"></script>
        <script>
            let gantt;
            const projectId = {{ $projectId }};

            document.addEventListener('livewire:navigated', function () {
                initGantt();
            });

            // Initial load
            document.addEventListener('DOMContentLoaded', function () {
                initGantt();
            });

            function initGantt() {
                const container = document.getElementById('gantt-chart');
                if (!container) return;

                fetch(`/api/gantt/${projectId}`, {
                    headers: {
                        'Authorization': 'Bearer ' + '{{ auth()->user()->createToken("gantt")->plainTextToken }}', // Temporary token generation for demo
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(tasks => {
                        if (tasks.length === 0) {
                            container.innerHTML = '<p class="text-gray-500 dark:text-gray-400 text-center py-4">No tasks found for this project.</p>';
                            return;
                        }

                        gantt = new Gantt("#gantt-chart", tasks, {
                            header_height: 50,
                            column_width: 30,
                            step: 24,
                            view_modes: ['Quarter Day', 'Half Day', 'Day', 'Week', 'Month'],
                            bar_height: 20,
                            bar_corner_radius: 3,
                            arrow_curve: 5,
                            padding: 18,
                            view_mode: 'Week',
                            date_format: 'YYYY-MM-DD',
                            custom_popup_html: function (task) {
                                return `
                                    <div class="details-container p-2 bg-white dark:bg-gray-700 border dark:border-gray-600 rounded shadow-lg text-sm z-50">
                                        <h5 class="font-bold dark:text-white">${task.name}</h5>
                                        <p class="text-gray-600 dark:text-gray-300">Expected to finish by ${task.end}</p>
                                        <p class="text-gray-500 dark:text-gray-400">${task.progress}% completed</p>
                                    </div>
                                `;
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching Gantt data:', error));
            }

            function changeViewMode(mode) {
                if (gantt) {
                    gantt.change_view_mode(mode);
                }
            }
        </script>
    @endpush
</div>