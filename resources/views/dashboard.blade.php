<x-layouts.app title="Dashboard">

    {{-- Greeting --}}
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-gray-800">
            Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }},
            {{ auth()->user()->name }} 👋
        </h1>
        <p class="text-gray-400 text-sm mt-1">
            {{ now()->format('l, F j Y') }}
        </p>
    </div>

    {{-- ===== Stats Cards ===== --}}
    <div class="grid grid-cols-4 gap-4 mb-8">

        {{-- Goals --}}
        <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">
                    Active Goals
                </p>
                <span class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center
                             justify-center text-indigo-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0
                                 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2
                                 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2
                                 2 0 01-2-2z"/>
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $goals['active'] }}</p>
            <p class="text-xs text-gray-400 mt-1">
                {{ $goals['done'] }} completed · {{ $goals['overdue'] }} overdue
            </p>
        </div>

        {{-- Tasks today --}}
        <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">
                    Today's Tasks
                </p>
                <span class="w-8 h-8 rounded-lg bg-amber-50 flex items-center
                             justify-center text-amber-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0
                                 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2
                                 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-bold text-gray-800">
                {{ $tasks['today_done'] }}/{{ $tasks['today']->count() }}
            </p>
            <p class="text-xs text-gray-400 mt-1">
                {{ $tasks['in_progress'] }} in progress · {{ $tasks['overdue'] }} overdue
            </p>
        </div>

        {{-- Habits today --}}
        <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">
                    Habits Today
                </p>
                <span class="w-8 h-8 rounded-lg bg-green-50 flex items-center
                             justify-center text-green-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11
                                 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </span>
            </div>

            <p class="text-3xl font-bold text-gray-800">
                {{ $habits['done_today'] }}/{{ $habits['total'] }}
            </p>
            <p class="text-xs text-gray-400 mt-1">
                {{ $habits['pending_today'] }} remaining today
            </p>
        </div>

        {{-- Time today --}}
        <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">
                    Time Today
                </p>
                <span class="w-8 h-8 rounded-lg bg-purple-50 flex items-center
                             justify-center text-purple-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $time['today_formatted'] }}</p>
            <p class="text-xs text-gray-400 mt-1">
                This week: {{ $time['week_formatted'] }}
            </p>
        </div>

    </div>

    {{-- ===== Main content ===== --}}
    <div class="grid grid-cols-3 gap-6">

        {{-- Today's Tasks --}}
        <div class="col-span-2">
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-semibold text-gray-800">Today's Tasks</h2>
                    <a href="{{ route('tasks.index', ['period' => 'day']) }}"
                       class="text-xs text-indigo-600 hover:underline">View all</a>
                </div>

                @forelse ($tasks['today'] as $task)
                    <div class="flex items-center justify-between py-3
                                border-b border-gray-50 last:border-0">
                        <div class="flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full flex-shrink-0
                                {{ $task->status === 'done'        ? 'bg-green-400' :
                                  ($task->status === 'in_progress' ? 'bg-amber-400' :
                                                                     'bg-gray-200') }}">
                            </span>
                            <span class="text-sm
                                {{ $task->status === 'done'
                                    ? 'line-through text-gray-400'
                                    : 'text-gray-700' }}">
                                {{ $task->title }}
                            </span>
                            @if ($task->is_running)
                                <span class="text-xs bg-green-100 text-green-700
                                             font-medium px-2 py-0.5 rounded-full animate-pulse">
                                    Running
                                </span>
                            @endif
                        </div>
                        <a href="{{ route('tasks.show', $task->task_id) }}"
                           class="text-xs text-indigo-600 hover:underline">View</a>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-6">
                        No tasks scheduled for today.
                    </p>
                @endforelse
            </div>
            
            {{-- Recent Goals --}}
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-semibold text-gray-800">Recent Goals</h2>
                    <a href="{{ route('goals.index') }}"
                       class="text-xs text-indigo-600 hover:underline">View all</a>
                </div>

                @forelse ($goals['recent'] as $goal)
                    <div class="py-3 border-b border-gray-50 last:border-0">
                        <div class="flex items-center justify-between mb-2">
                            <a href="{{ route('goals.show', $goal->goal_id) }}"
                               class="text-sm font-medium text-gray-700 hover:text-indigo-600">
                                {{ $goal->title }}
                            </a>
                            <span class="text-xs text-indigo-600 font-medium">
                                {{ $goal->completion_percentage }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="bg-indigo-500 h-1.5 rounded-full"
                                 style="width: {{ $goal->completion_percentage }}%">
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-6">
                        No goals yet.
                        <a href="{{ route('goals.create') }}"
                           class="text-indigo-600 hover:underline">Create one</a>
                    </p>
                @endforelse
            </div>
        </div>

        {{-- Habits sidebar --}}
        <div class="col-span-1">
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-semibold text-gray-800">Habits</h2>
                    <a href="{{ route('habits.index') }}"
                       class="text-xs text-indigo-600 hover:underline">View all</a>
                </div>

                {{-- Progress ring --}}
                @if ($habits['total'] > 0)
                    @php
                        $percentage = round(($habits['done_today'] / $habits['total']) * 100);
                    @endphp
                    <div class="flex flex-col items-center mb-5">
                        <div class="relative w-24 h-24">
                            <svg class="w-24 h-24 -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="15.9"
                                        fill="none" stroke="#f3f4f6"
                                        stroke-width="3"/>
                                <circle cx="18" cy="18" r="15.9"
                                        fill="none" stroke="#6366f1"
                                        stroke-width="3"
                                        stroke-dasharray="{{ $percentage }}, 100"
                                        stroke-linecap="round"/>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-lg font-bold text-gray-800">
                                    {{ $percentage }}%
                                </span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">
                            {{ $habits['done_today'] }} of {{ $habits['total'] }} done
                        </p>
                    </div>
                @endif

                {{-- Habit list with quick toggle --}}
                @forelse ($habits['habits'] as $habit)
                    <div class="flex items-center justify-between py-2.5
                                border-b border-gray-50 last:border-0">
                        <div class="flex items-center gap-2">
                            <form action="{{ route('habits.toggle', $habit->habit_id) }}"
                                  method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-6 h-6 rounded-full border-2 flex items-center
                                               justify-center transition-colors flex-shrink-0
                                               {{ $habit->is_done_today
                                                   ? 'bg-green-500 border-green-500'
                                                   : 'border-gray-200 hover:border-green-400' }}">
                                    @if ($habit->is_done_today)
                                        <svg class="w-3 h-3 text-white" fill="none"
                                             stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @endif
                                </button>
                            </form>
                            <span class="text-sm {{ $habit->is_done_today
                                                      ? 'line-through text-gray-400'
                                                      : 'text-gray-700' }}">
                                {{ $habit->name }}
                            </span>
                        </div>
                        @if ($habit->streak > 0)
                            <span class="text-xs text-orange-500 font-medium">
                                🔥{{ $habit->streak }}
                            </span>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-6">
                        No active habits.
                    </p>
                @endforelse
            </div>
        </div>

    </div>

</x-layouts.app>