<x-layouts.app title="Task Details">

    <div class="max-w-3xl mx-auto">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('tasks.index') }}"
                   class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-2xl font-semibold text-gray-800">Task Details</h1>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('tasks.edit', $task->task_id) }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm
                          font-medium px-4 py-2 rounded-lg transition-colors">
                    Edit
                </a>
                <form action="{{ route('tasks.destroy', $task->task_id) }}"
                      method="POST" onsubmit="return confirm('Delete this task?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="bg-red-50 hover:bg-red-100 text-red-600 text-sm
                                   font-medium px-4 py-2 rounded-lg transition-colors">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        {{-- Main card --}}
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6 mb-5">

            {{-- Badges --}}
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <span class="text-xs font-medium px-2.5 py-1 rounded-full
                    {{ $task->priority === 'high'   ? 'bg-red-100 text-red-700' :
                      ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' :
                                                      'bg-gray-100 text-gray-600') }}">
                    {{ ucfirst($task->priority) }} priority
                </span>

                <span class="text-xs font-medium px-2.5 py-1 rounded-full
                    {{ $task->status === 'done'        ? 'bg-green-100 text-green-700' :
                      ($task->status === 'in_progress' ? 'bg-amber-100 text-amber-700' :
                      ($task->status === 'cancelled'   ? 'bg-gray-100 text-gray-500' :
                                                         'bg-blue-100 text-blue-700')) }}">
                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                </span>

                <span class="text-xs font-medium px-2.5 py-1 rounded-full
                             bg-indigo-50 text-indigo-600">
                    {{ ucfirst($task->period) }}
                </span>

                @if ($task->is_overdue)
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full
                                 bg-red-100 text-red-600">
                        Overdue
                    </span>
                @endif

                @if ($task->is_running)
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full
                                 bg-green-100 text-green-700 animate-pulse">
                        Timer running
                    </span>
                @endif
            </div>

            <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $task->title }}</h2>

            @if ($task->description)
                <p class="text-gray-500 text-sm leading-relaxed mb-4">
                    {{ $task->description }}
                </p>
            @endif

            {{-- Goal link --}}
            @if ($task->goal)
                <div class="bg-indigo-50 rounded-lg px-4 py-3 mb-4">
                    <p class="text-xs text-indigo-400 mb-0.5">Linked Goal</p>
                    <a href="{{ route('goals.show', $task->goal->goal_id) }}"
                       class="text-sm font-medium text-indigo-600 hover:underline">
                        {{ $task->goal->title }}
                    </a>
                </div>
            @endif

            {{-- Due date + Total time --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-400 mb-1">Due date</p>
                    <p class="text-sm font-medium
                              {{ $task->is_overdue ? 'text-red-500' : 'text-gray-700' }}">
                        {{ $task->due_date ? $task->due_date->format('M d, Y') : '—' }}
                    </p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-400 mb-1">Total time spent</p>
                    <p class="text-sm font-medium text-gray-700">
                        {{ $task->total_minutes > 0 ? $task->formatted_time : '—' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Timer controls --}}
        @if ($task->status !== 'done' && $task->status !== 'cancelled')
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6 mb-5">
                <h3 class="text-base font-semibold text-gray-800 mb-4">Time Tracker</h3>

                @if ($task->is_running)
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2 text-green-600">
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                            <span class="text-sm font-medium">Timer is running...</span>
                        </div>
                        <form action="{{ route('tasks.timer.stop', $task->task_id) }}"
                              method="POST">
                            @csrf
                            <button type="submit"
                                    class="bg-red-500 hover:bg-red-600 text-white text-sm
                                           font-medium px-5 py-2 rounded-lg transition-colors">
                                Stop Timer
                            </button>
                        </form>
                    </div>
                @else
                    <form action="{{ route('tasks.timer.start', $task->task_id) }}"
                          method="POST">
                        @csrf
                        <button type="submit"
                                class="bg-green-500 hover:bg-green-600 text-white text-sm
                                       font-medium px-5 py-2 rounded-lg transition-colors">
                            Start Timer
                        </button>
                    </form>
                @endif
            </div>
        @endif

        {{-- Time logs --}}
        @if ($timeLogs->isNotEmpty())
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6">
                <h3 class="text-base font-semibold text-gray-800 mb-4">Time Logs</h3>

                @foreach ($timeLogs as $log)
                    <div class="flex items-center justify-between py-3
                                border-b border-gray-50 last:border-0">
                        <div>
                            <p class="text-sm text-gray-700">
                                {{ $log->start_time->format('M d, Y — H:i') }}
                                <span class="text-gray-400">→</span>
                                {{ $log->end_time->format('H:i') }}
                            </p>
                        </div>
                        <span class="text-sm font-medium text-indigo-600">
                            {{ $log->formatted_duration }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

</x-layouts.app>