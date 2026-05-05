{{-- resources/views/dashboard.blade.php --}}
<x-layouts.app title="Dashboard">

    {{-- Greeting --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">
            Good {{ now()->hour < 12
                ? 'morning'
                : (now()->hour < 18 ? 'afternoon' : 'evening') }},
            {{ auth()->user()->name }} 👋
        </h1>
        <p class="text-gray-400 text-sm mt-1">
            {{ now()->format('l, F j Y') }}
        </p>
    </div>

    {{-- ===== STATS CARDS (clickable) ===== --}}
    <div class="grid grid-cols-4 gap-4 mb-8">

        {{-- Goals --}}
        <a href="{{ route('goals.index') }}"
           class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm
                  hover:shadow-md hover:border-indigo-200 transition-all group">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">
                    Active Goals
                </p>
                <span class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center
                             justify-center group-hover:bg-indigo-100
                             transition-colors">
                    <svg class="w-4 h-4 text-indigo-600" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2
                                 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2
                                 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0
                                 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2
                                 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-black text-gray-800">
                {{ $goals['active'] }}
            </p>
            <p class="text-xs text-gray-400 mt-1">
                {{ $goals['done'] }} completed
                @if ($goals['overdue'] > 0)
                    · <span class="text-red-500 font-semibold">
                        {{ $goals['overdue'] }} overdue
                    </span>
                @endif
            </p>
        </a>

        {{-- Tasks today --}}
        <a href="{{ route('tasks.index', ['filter' => 'today']) }}"
           class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm
                  hover:shadow-md hover:border-amber-200 transition-all group">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">
                    Today's Tasks
                </p>
                <span class="w-9 h-9 rounded-xl bg-amber-50 flex items-center
                             justify-center group-hover:bg-amber-100
                             transition-colors">
                    <svg class="w-4 h-4 text-amber-600" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2
                                 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2
                                 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6
                                 9l2 2 4-4"/>
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-black text-gray-800">
                {{ $tasks['today_done'] }}/{{ $tasks['today']->count() }}
            </p>
            <p class="text-xs text-gray-400 mt-1">
                @if ($tasks['today']->count() === 0)
                    No tasks scheduled today
                @elseif ($tasks['today_done'] === $tasks['today']->count())
                    All done! 🎉
                @else
                    {{ $tasks['today']->count() - $tasks['today_done'] }}
                    remaining
                @endif
                @if ($tasks['overdue'] > 0)
                    · <span class="text-red-500 font-semibold">
                        {{ $tasks['overdue'] }} overdue
                    </span>
                @endif
            </p>
        </a>

        {{-- Habits today --}}
        <a href="{{ route('habits.index') }}"
           class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm
                  hover:shadow-md hover:border-green-200 transition-all group">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">
                    Habits Today
                </p>
                <span class="w-9 h-9 rounded-xl bg-green-50 flex items-center
                             justify-center group-hover:bg-green-100
                             transition-colors">
                    <svg class="w-4 h-4 text-green-600" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582
                                 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0
                                 01-15.357-2m15.357 2H15"/>
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-black text-gray-800">
                {{ $habits['done_today'] }}/{{ $habits['total'] }}
            </p>
            <p class="text-xs text-gray-400 mt-1">
                @if ($habits['total'] === 0)
                    No active habits
                @elseif ($habits['pending_today'] === 0)
                    All done! 🔥
                @else
                    {{ $habits['pending_today'] }} remaining today
                @endif
            </p>
        </a>

        {{-- Time today --}}
        <a href="{{ route('tasks.index') }}"
           class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm
                  hover:shadow-md hover:border-purple-200 transition-all group">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">
                    Time Today
                </p>
                <span class="w-9 h-9 rounded-xl bg-purple-50 flex items-center
                             justify-center group-hover:bg-purple-100
                             transition-colors">
                    <svg class="w-4 h-4 text-purple-600" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-black text-gray-800">
                {{ $time['today_formatted'] }}
            </p>
            <p class="text-xs text-gray-400 mt-1">
                This week: {{ $time['week_formatted'] }}
            </p>
        </a>

    </div>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="grid grid-cols-3 gap-6">

        {{-- Left: Tasks + Goals --}}
        <div class="col-span-2 space-y-5">

            {{-- Today's Tasks --}}
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm">

                {{-- Header (clickable) --}}
                <a href="{{ route('tasks.index', ['filter' => 'today']) }}"
                   class="flex items-center justify-between px-6 py-4
                          border-b border-gray-50 hover:bg-gray-50
                          transition-colors rounded-t-2xl">
                    <div class="flex items-center gap-3">
                        <h2 class="text-sm font-bold text-gray-800">
                            Today's Tasks
                        </h2>
                        @if ($tasks['today']->count() > 0)
                            <span class="text-xs font-bold bg-amber-100
                                         text-amber-700 px-2 py-0.5 rounded-full">
                                {{ $tasks['today']->count() }}
                            </span>
                        @endif
                    </div>
                    <svg class="w-4 h-4 text-gray-300" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>

                <div class="divide-y divide-gray-50">
                    @forelse ($tasks['today'] as $task)
                        @php
                            $gc  = $task->goal?->color ?? '#e5e7eb';
                            $gbg = \App\Helpers\ColorPalette::bg($gc);
                            $gt  = \App\Helpers\ColorPalette::text($gc);
                        @endphp

                        <a href="{{ route('tasks.show', $task->task_id) }}"
                           class="flex items-center gap-3 px-6 py-3.5
                                  hover:bg-gray-50 transition-colors group/task">

                            {{-- Color bar --}}
                            <div class="w-1 h-8 rounded-full flex-shrink-0"
                                 style="background: {{ $gc }}"></div>

                            {{-- Circle --}}
                            <div class="w-5 h-5 rounded-full border-2 flex items-center
                                        justify-center flex-shrink-0 transition-all"
                                 style="{{ $task->status === 'done'
                                            ? "background:{$gc};border-color:{$gc}"
                                            : "border-color:{$gc}" }}">
                                @if ($task->status === 'done')
                                    <svg class="w-2.5 h-2.5 text-white" fill="none"
                                         stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="3"
                                              d="M5 13l4 4L19 7"/>
                                    </svg>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold truncate
                                          {{ $task->status === 'done'
                                              ? 'line-through text-gray-400'
                                              : 'text-gray-800' }}">
                                    {{ $task->title }}
                                </p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    @if ($task->goal)
                                        <span class="text-xs font-semibold
                                                     px-1.5 py-0.5 rounded-md"
                                              style="background:{{ $gbg }};
                                                     color:{{ $gt }}">
                                            {{ Str::limit($task->goal->title, 20) }}
                                        </span>
                                    @endif
                                    @if ($task->scheduled_time)
                                        <span class="text-xs text-gray-400">
                                            ⏰ {{ \Carbon\Carbon::parse(
                                                $task->scheduled_time
                                            )->format('H:i') }}
                                        </span>
                                    @endif
                                    @if ($task->is_running)
                                        <span class="text-xs text-green-600
                                                     font-bold animate-pulse">
                                            ⏱ Running
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Priority dot --}}
                            <span class="w-2 h-2 rounded-full flex-shrink-0
                                {{ $task->priority === 'high'
                                    ? 'bg-red-400' :
                                  ($task->priority === 'medium'
                                    ? 'bg-amber-400'
                                    : 'bg-gray-300') }}">
                            </span>

                            {{-- Arrow --}}
                            <svg class="w-4 h-4 text-gray-200
                                        group-hover/task:text-gray-400
                                        transition-colors flex-shrink-0"
                                 fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>

                        </a>
                    @empty
                        <div class="flex flex-col items-center justify-center
                                    py-10 text-center">
                            <p class="text-2xl mb-2">☀️</p>
                            <p class="text-sm font-semibold text-gray-600 mb-1">
                                No tasks for today
                            </p>
                            <p class="text-xs text-gray-400 mb-4">
                                Schedule a task for today to see it here.
                            </p>
                            <a href="{{ route('tasks.create') }}"
                               class="inline-flex items-center gap-1.5 text-xs
                                      font-bold text-white bg-indigo-600
                                      hover:bg-indigo-700 px-4 py-2 rounded-xl
                                      transition-colors">
                                + Add task for today
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- Today progress bar at bottom --}}
                @if ($tasks['today']->count() > 0)
                    @php
                        $prog = $tasks['today']->count() > 0
                            ? round(($tasks['today_done']
                                / $tasks['today']->count()) * 100)
                            : 0;
                    @endphp
                    <div class="px-6 py-3 border-t border-gray-50">
                        <div class="flex justify-between text-xs mb-1.5">
                            <span class="text-gray-400 font-medium">
                                Progress
                            </span>
                            <span class="font-bold text-indigo-600">
                                {{ $prog }}%
                            </span>
                        </div>
                        <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-1.5 bg-indigo-500 rounded-full
                                        transition-all duration-500"
                                 style="width: {{ $prog }}%"></div>
                        </div>
                    </div>
                @endif

            </div>

            {{-- Recent Goals --}}
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm">

                {{-- Header (clickable) --}}
                <a href="{{ route('goals.index') }}"
                   class="flex items-center justify-between px-6 py-4
                          border-b border-gray-50 hover:bg-gray-50
                          transition-colors rounded-t-2xl">
                    <div class="flex items-center gap-3">
                        <h2 class="text-sm font-bold text-gray-800">
                            Recent Goals
                        </h2>
                        @if ($goals['total'] > 0)
                            <span class="text-xs font-bold bg-indigo-100
                                         text-indigo-700 px-2 py-0.5 rounded-full">
                                {{ $goals['total'] }}
                            </span>
                        @endif
                    </div>
                    <svg class="w-4 h-4 text-gray-300" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>

                <div class="divide-y divide-gray-50">
                    @forelse ($goals['recent'] as $goal)
                        @php
                            $color   = $goal->color ?? '#6366f1';
                            $bgLight = \App\Helpers\ColorPalette::bg($color);
                            $textCol = \App\Helpers\ColorPalette::text($color);
                        @endphp

                        <a href="{{ route('goals.show', $goal->goal_id) }}"
                           class="flex items-center gap-4 px-6 py-4
                                  hover:bg-gray-50 transition-colors
                                  group/goal">

                            {{-- Color dot --}}
                            <div class="w-9 h-9 rounded-xl flex items-center
                                        justify-center flex-shrink-0"
                                 style="background: {{ $bgLight }}">
                                <div class="w-3 h-3 rounded-full"
                                     style="background: {{ $color }}"></div>
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between
                                            mb-1.5">
                                    <p class="text-sm font-bold text-gray-800
                                               truncate">
                                        {{ $goal->title }}
                                    </p>
                                    <span class="text-xs font-bold ml-3
                                                 flex-shrink-0"
                                          style="color: {{ $color }}">
                                        {{ $goal->completion_percentage }}%
                                    </span>
                                </div>
                                <div class="h-1.5 bg-gray-100 rounded-full
                                            overflow-hidden">
                                    <div class="h-1.5 rounded-full transition-all"
                                         style="width: {{ $goal->completion_percentage }}%;
                                                background: {{ $color }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Arrow --}}
                            <svg class="w-4 h-4 text-gray-200
                                        group-hover/goal:text-gray-400
                                        transition-colors flex-shrink-0"
                                 fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>

                        </a>
                    @empty
                        <div class="flex flex-col items-center justify-center
                                    py-10 text-center">
                            <p class="text-2xl mb-2">🎯</p>
                            <p class="text-sm font-semibold text-gray-600 mb-1">
                                No goals yet
                            </p>
                            <p class="text-xs text-gray-400 mb-4">
                                Define where you want to go.
                            </p>
                            <a href="{{ route('goals.create') }}"
                               class="inline-flex items-center gap-1.5 text-xs
                                      font-bold text-white bg-indigo-600
                                      hover:bg-indigo-700 px-4 py-2 rounded-xl
                                      transition-colors">
                                + Create a goal
                            </a>
                        </div>
                    @endforelse
                </div>

            </div>

        </div>

        {{-- Right: Habits --}}
        <div class="col-span-1">
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm
                        h-full">

                {{-- Header (clickable) --}}
                <a href="{{ route('habits.index') }}"
                   class="flex items-center justify-between px-5 py-4
                          border-b border-gray-50 hover:bg-gray-50
                          transition-colors rounded-t-2xl">
                    <div class="flex items-center gap-2">
                        <h2 class="text-sm font-bold text-gray-800">
                            Habits
                        </h2>
                        @if ($habits['total'] > 0)
                            <span class="text-xs font-bold bg-green-100
                                         text-green-700 px-2 py-0.5 rounded-full">
                                {{ $habits['done_today'] }}/{{ $habits['total'] }}
                            </span>
                        @endif
                    </div>
                    <svg class="w-4 h-4 text-gray-300" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>

                {{-- Progress ring --}}
                @if ($habits['total'] > 0)
                    @php
                        $habitPct = round(
                            ($habits['done_today'] / $habits['total']) * 100
                        );
                    @endphp
                    <div class="flex flex-col items-center pt-5 pb-4
                                border-b border-gray-50">
                        <div class="relative w-20 h-20">
                            <svg class="w-20 h-20 -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="15.9"
                                        fill="none" stroke="#f3f4f6"
                                        stroke-width="3"/>
                                <circle cx="18" cy="18" r="15.9"
                                        fill="none" stroke="#6366f1"
                                        stroke-width="3"
                                        stroke-dasharray="{{ $habitPct }}, 100"
                                        stroke-linecap="round"/>
                            </svg>
                            <div class="absolute inset-0 flex items-center
                                        justify-center">
                                <span class="text-base font-black text-gray-800">
                                    {{ $habitPct }}%
                                </span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">
                            {{ $habits['done_today'] }} of
                            {{ $habits['total'] }} done today
                        </p>
                    </div>
                @endif

                {{-- Habit list with quick toggle --}}
                <div class="divide-y divide-gray-50">
                    @forelse ($habits['habits'] as $habit)
                        @php
                            $hc = $habit->color ?? '#6366f1';
                        @endphp

                        <div class="flex items-center gap-3 px-5 py-3
                                    hover:bg-gray-50 transition-colors">

                            {{-- Toggle circle --}}
                            <form action="{{ route('habits.toggle',
                                        $habit->habit_id) }}"
                                  method="POST" class="flex-shrink-0">
                                @csrf
                                <button type="submit"
                                        title="{{ $habit->is_done_today
                                            ? 'Mark as undone'
                                            : 'Mark as done' }}"
                                        class="w-7 h-7 rounded-full border-2
                                               flex items-center justify-center
                                               transition-all hover:scale-110
                                               active:scale-95"
                                        style="{{ $habit->is_done_today
                                            ? "background:{$hc};border-color:{$hc}"
                                            : "border-color:{$hc}" }}">
                                    @if ($habit->is_done_today)
                                        <svg class="w-3.5 h-3.5 text-white"
                                             fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="3"
                                                  d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @endif
                                </button>
                            </form>

                            {{-- Name + streak --}}
                            <a href="{{ route('habits.show', $habit->habit_id) }}"
                               class="flex-1 min-w-0 group/habit">
                                <p class="text-xs font-semibold truncate
                                          transition-colors
                                          group-hover/habit:text-indigo-600
                                          {{ $habit->is_done_today
                                              ? 'line-through text-gray-400'
                                              : 'text-gray-700' }}">
                                    {{ $habit->name }}
                                </p>
                                @if ($habit->streak > 0)
                                    <p class="text-xs text-orange-500 font-bold">
                                        🔥 {{ $habit->streak }} day streak
                                    </p>
                                @endif
                            </a>

                        </div>

                    @empty
                        <div class="flex flex-col items-center justify-center
                                    py-10 text-center px-5">
                            <p class="text-2xl mb-2">🔁</p>
                            <p class="text-sm font-semibold text-gray-600 mb-1">
                                No habits yet
                            </p>
                            <a href="{{ route('habits.create') }}"
                               class="inline-flex items-center gap-1.5 text-xs
                                      font-bold text-white bg-indigo-600
                                      hover:bg-indigo-700 px-4 py-2 rounded-xl
                                      transition-colors mt-3">
                                + Add a habit
                            </a>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>

    </div>

</x-layouts.app>