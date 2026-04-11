<x-layouts.app title="My Tasks">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">My Tasks</h1>
        <a href="{{ route('tasks.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm
                  font-medium px-4 py-2 rounded-lg transition-colors">
            + New Task
        </a>
    </div>

    {{-- Period filter --}}
    <div class="flex gap-2 mb-6">
        @foreach (['all' => 'All', 'day' => 'Today', 'week' => 'This Week', 'month' => 'This Month', 'year' => 'This Year'] as $value => $label)
            <a href="{{ route('tasks.index', ['period' => $value]) }}"
               class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors
                      {{ $period === $value
                          ? 'bg-indigo-600 text-white'
                          : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Tasks list --}}
    @forelse ($tasks as $task)
        <div class="bg-white border border-gray-100 rounded-xl p-5 mb-3 shadow-sm">
            <div class="flex items-start justify-between">
                <div class="flex-1">

                    {{-- Status dot + Title --}}
                    <div class="flex items-center gap-2 mb-1">
                        <span class="w-2 h-2 rounded-full flex-shrink-0
                            {{ $task->status === 'done'        ? 'bg-green-400' :
                              ($task->status === 'in_progress' ? 'bg-amber-400' :
                              ($task->status === 'cancelled'   ? 'bg-gray-300' :
                                                                 'bg-gray-200')) }}">
                        </span>
                        <h2 class="text-sm font-semibold
                                   {{ $task->status === 'done' ? 'line-through text-gray-400' : 'text-gray-800' }}">
                            {{ $task->title }}
                        </h2>

                        @if ($task->is_running)
                            <span class="text-xs bg-green-100 text-green-700
                                         font-medium px-2 py-0.5 rounded-full animate-pulse">
                                Running
                            </span>
                        @endif
                    </div>

                    {{-- Goal link --}}
                    @if ($task->goal)
                        <p class="text-xs text-gray-400 mb-2">
                            Goal: <span class="text-indigo-500">{{ $task->goal->title }}</span>
                        </p>
                    @endif

                    {{-- Meta --}}
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full
                            {{ $task->priority === 'high'   ? 'bg-red-100 text-red-700' :
                              ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' :
                                                              'bg-gray-100 text-gray-500') }}">
                            {{ ucfirst($task->priority) }}
                        </span>

                        <span class="text-xs text-gray-400">
                            {{ ucfirst($task->period) }}
                        </span>

                        @if ($task->due_date)
                            <span class="text-xs
                                {{ $task->is_overdue ? 'text-red-500 font-medium' : 'text-gray-400' }}">
                                Due {{ $task->due_date->format('M d, Y') }}
                            </span>
                        @endif

                        @if ($task->total_minutes > 0)
                            <span class="text-xs text-gray-400">
                                ⏱ {{ $task->formatted_time }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-2 ml-4">
                    <a href="{{ route('tasks.show', $task->task_id) }}"
                       class="text-xs text-indigo-600 hover:underline">View</a>
                    <a href="{{ route('tasks.edit', $task->task_id) }}"
                       class="text-xs text-gray-500 hover:underline">Edit</a>
                    <form action="{{ route('tasks.destroy', $task->task_id) }}"
                          method="POST"
                          onsubmit="return confirm('Delete this task?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="text-xs text-red-500 hover:underline">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-16 text-gray-400">
            <p class="text-lg">No tasks yet.</p>
            <a href="{{ route('tasks.create') }}"
               class="text-indigo-600 text-sm hover:underline mt-2 inline-block">
                Create your first task
            </a>
        </div>
    @endforelse

</x-layouts.app>