<x-layouts.app title="Goal Details">

    <div class="max-w-3xl mx-auto">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('goals.index') }}"
                   class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-2xl font-semibold text-gray-800">Goal Details</h1>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('goals.edit', $goal->goal_id) }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm
                          font-medium px-4 py-2 rounded-lg transition-colors">
                    Edit
                </a>
                <form action="{{ route('goals.destroy', $goal->goal_id) }}"
                      method="POST"
                      onsubmit="return confirm('Delete this goal?')">
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
            <div class="flex items-center gap-2 mb-4">
                <span class="text-xs font-medium px-2.5 py-1 rounded-full
                    {{ $goal->type === 'short_term' ? 'bg-blue-100 text-blue-700' :
                      ($goal->type === 'mid_term'   ? 'bg-amber-100 text-amber-700' :
                                                      'bg-purple-100 text-purple-700') }}">
                    {{ ucfirst(str_replace('_', ' ', $goal->type)) }}
                </span>

                <span class="text-xs font-medium px-2.5 py-1 rounded-full
                    {{ $goal->priority === 'high'   ? 'bg-red-100 text-red-700' :
                      ($goal->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' :
                                                      'bg-gray-100 text-gray-600') }}">
                    {{ ucfirst($goal->priority) }} priority
                </span>

                <span class="text-xs font-medium px-2.5 py-1 rounded-full
                    {{ $goal->status === 'active'    ? 'bg-green-100 text-green-700' :
                      ($goal->status === 'paused'    ? 'bg-orange-100 text-orange-700' :
                      ($goal->status === 'done'      ? 'bg-indigo-100 text-indigo-700' :
                                                       'bg-gray-100 text-gray-500')) }}">
                    {{ ucfirst($goal->status) }}
                </span>

                @if ($goal->is_overdue)
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full
                                 bg-red-100 text-red-600">
                        Overdue
                    </span>
                @endif
            </div>

            {{-- Title & Description --}}
            <h2 class="text-xl font-semibold text-gray-800 mb-2">
                {{ $goal->title }}
            </h2>
            @if ($goal->description)
                <p class="text-gray-500 text-sm leading-relaxed mb-5">
                    {{ $goal->description }}
                </p>
            @endif

            {{-- Dates --}}
            <div class="grid grid-cols-2 gap-4 mb-5">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-400 mb-1">Start date</p>
                    <p class="text-sm font-medium text-gray-700">
                        {{ $goal->start_date ? $goal->start_date->format('M d, Y') : '—' }}
                    </p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-400 mb-1">End date</p>
                    <p class="text-sm font-medium
                              {{ $goal->is_overdue ? 'text-red-500' : 'text-gray-700' }}">
                        {{ $goal->end_date ? $goal->end_date->format('M d, Y') : '—' }}
                    </p>
                </div>
            </div>

            {{-- Progress --}}
            <div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-500">Overall progress</span>
                    <span class="font-semibold text-indigo-600">
                        {{ $goal->completion_percentage }}%
                    </span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-3">
                    <div class="bg-indigo-500 h-3 rounded-full transition-all"
                         style="width: {{ $goal->completion_percentage }}%">
                    </div>
                </div>
            </div>
        </div>

        {{-- Tasks linked to this goal --}}
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6">

            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-gray-800">Linked Tasks</h3>
                <a href="{{ route('tasks.create', ['goal_id' => $goal->goal_id]) }}"
                   class="text-sm text-indigo-600 hover:underline">
                    + Add task
                </a>
            </div>

            @forelse ($goal->tasks as $task)
                <div class="flex items-center justify-between py-3
                            border-b border-gray-50 last:border-0">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full flex-shrink-0
                            {{ $task->status === 'done'        ? 'bg-green-400' :
                              ($task->status === 'in_progress' ? 'bg-amber-400' :
                              ($task->status === 'cancelled'   ? 'bg-gray-300' :
                                                                 'bg-gray-200')) }}">
                        </span>
                        <span class="text-sm {{ $task->status === 'done'
                                                ? 'line-through text-gray-400'
                                                : 'text-gray-700' }}">
                            {{ $task->title }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3">
                        @if ($task->is_overdue)
                            <span class="text-xs text-red-500">Overdue</span>
                        @endif
                        <a href="{{ route('tasks.show', $task->task_id) }}"
                           class="text-xs text-indigo-600 hover:underline">
                            View
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400 text-center py-6">
                    No tasks linked to this goal yet.
                </p>
            @endforelse
        </div>

    </div>

</x-layouts.app>