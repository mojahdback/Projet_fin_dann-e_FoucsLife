{{-- resources/views/tasks/index.blade.php --}}
<x-layouts.app title="My Tasks">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-5">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">My Tasks</h1>
            <p class="text-sm text-gray-400 mt-0.5">
                {{ now()->format('l, F j') }}
            </p>
        </div>
        {{-- View toggle --}}
        <div class="flex bg-gray-100 rounded-xl p-1 gap-1">
            <button onclick="setView('board')" id="btn-board"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold
                           transition-all bg-white text-gray-700 shadow-sm">
                Board
            </button>
            <button onclick="setView('list')" id="btn-list"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold
                           transition-all text-gray-400 hover:text-gray-600">
                List
            </button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex gap-2 mb-6">
        @php
            $filters = [
                'all'   => ['All tasks', '📋'],
                'week'  => ['This week', '📅'],
                'today' => ['Today',     '☀️'],
            ];
        @endphp
        @foreach ($filters as $val => [$label, $icon])
            <a href="{{ route('tasks.index', ['filter' => $val]) }}"
               class="flex items-center gap-1.5 px-4 py-1.5 rounded-full
                      text-sm font-semibold transition-all
                      {{ $filter === $val
                          ? 'bg-indigo-600 text-white shadow-sm'
                          : 'bg-white border border-gray-200 text-gray-500
                             hover:bg-gray-50 hover:border-gray-300' }}">
                <span>{{ $icon }}</span>
                {{ $label }}
                {{-- Count badge --}}
                @php
                    $cnt = match($val) {
                        'today' => $todo->merge($inProgress)->count(),
                        'week'  => $tasks->count(),
                        default => $tasks->count(),
                    };
                @endphp
                @if ($filter === $val && $cnt > 0)
                    <span class="bg-white/20 text-white text-xs
                                 px-1.5 py-0.5 rounded-full font-bold">
                        {{ $cnt }}
                    </span>
                @endif
            </a>
        @endforeach
    </div>

    {{-- Progress bar (today only) --}}
    @if ($filter === 'today')
        @php
            $totalToday = $todo->count() + $inProgress->count() + $done->count();
            $doneToday  = $done->count();
            $progress   = $totalToday > 0
                ? round(($doneToday / $totalToday) * 100)
                : 0;
        @endphp
        @if ($totalToday > 0)
            <div class="bg-white border border-gray-100 rounded-2xl p-4 mb-5 shadow-sm">
                <div class="flex justify-between text-xs mb-2">
                    <span class="font-semibold text-gray-600">
                        Today's progress
                    </span>
                    <span class="font-bold text-indigo-600">
                        {{ $doneToday }}/{{ $totalToday }} done
                    </span>
                </div>
                <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-2.5 bg-indigo-500 rounded-full transition-all
                                duration-500"
                         style="width: {{ $progress }}%"></div>
                </div>
            </div>
        @endif
    @endif

    {{-- ===== BOARD VIEW ===== --}}
    <div id="view-board">
        @php
            $columns = [
                'todo'        => ['To Do',       $todo,
                                  'bg-gray-100 text-gray-600'],
                'in_progress' => ['In Progress', $inProgress,
                                  'bg-amber-100 text-amber-700'],
                'done'        => ['Done',         $done,
                                  'bg-green-100 text-green-700'],
            ];
        @endphp

        <div class="grid grid-cols-3 gap-4">
            @foreach ($columns as $status => [$colLabel, $colTasks, $badgeClass])
                <div class="bg-gray-50 rounded-2xl p-4">

                    {{-- Column header --}}
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-bold text-gray-600">
                            {{ $colLabel }}
                        </span>
                        <span class="text-xs font-bold px-2 py-0.5
                                     rounded-lg {{ $badgeClass }}">
                            {{ $colTasks->count() }}
                        </span>
                    </div>

                    {{-- Cards --}}
                    <div class="space-y-2.5">
                        @forelse ($colTasks as $task)
                            @php
                                $gc  = $task->goal?->color ?? '#e5e7eb';
                                $gbg = \App\Helpers\ColorPalette::bg($gc);
                                $gt  = \App\Helpers\ColorPalette::text($gc);
                            @endphp

                            <div class="bg-white border border-gray-100
                                        rounded-xl overflow-hidden shadow-sm
                                        hover:shadow-md transition-all cursor-pointer
                                        group
                                        {{ $status === 'done' ? 'opacity-60' : '' }}"
                                 style="border-left: 3px solid {{ $gc }}"
                                 onclick="window.location=
                                     '{{ route('tasks.show', $task->task_id) }}'">

                                <div class="p-3.5">

                                    {{-- Title row --}}
                                    <div class="flex items-start gap-2.5 mb-2.5">

                                        {{-- Circle --}}
                                        <form action="{{ route('tasks.update',
                                                    $task->task_id) }}"
                                              method="POST"
                                              onclick="event.stopPropagation()"
                                              class="flex-shrink-0 mt-0.5">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="title"
                                                   value="{{ $task->title }}">
                                            <input type="hidden" name="description"
                                                   value="{{ $task->description }}">
                                            <input type="hidden" name="goal_id"
                                                   value="{{ $task->goal_id }}">
                                            <input type="hidden" name="priority"
                                                   value="{{ $task->priority }}">
                                            <input type="hidden" name="scheduled_date"
                                                   value="{{ $task->scheduled_date
                                                       ?->format('Y-m-d') }}">
                                            <input type="hidden" name="status"
                                                   value="{{ $task->status === 'done'
                                                       ? 'todo' : 'done' }}">
                                            <button type="submit"
                                                    title="{{ $task->status === 'done'
                                                        ? 'Mark as todo'
                                                        : 'Mark as done' }}"
                                                    class="w-5 h-5 rounded-full border-2
                                                           flex items-center justify-center
                                                           transition-all hover:scale-110"
                                                    style="{{ $task->status === 'done'
                                                        ? "background:{$gc};
                                                           border-color:{$gc}"
                                                        : "border-color:{$gc}" }}">
                                                @if ($task->status === 'done')
                                                    <svg class="w-2.5 h-2.5 text-white"
                                                         fill="none"
                                                         stroke="currentColor"
                                                         viewBox="0 0 24 24">
                                                        <path stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              stroke-width="3"
                                                              d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                @endif
                                            </button>
                                        </form>

                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-bold leading-tight
                                                       {{ $status === 'done'
                                                           ? 'line-through text-gray-400'
                                                           : 'text-gray-800' }}">
                                                {{ $task->title }}
                                            </p>
                                        </div>

                                    </div>

                                    {{-- Goal badge --}}
                                    @if ($task->goal)
                                        <div class="inline-flex items-center gap-1
                                                    text-xs font-semibold px-2 py-0.5
                                                    rounded-lg mb-2.5"
                                             style="background:{{ $gbg }};
                                                    color:{{ $gt }}">
                                            <span class="w-1.5 h-1.5 rounded-full"
                                                  style="background:{{ $gc }}">
                                            </span>
                                            {{ Str::limit($task->goal->title, 20) }}
                                        </div>
                                    @endif

                                    {{-- Footer --}}
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">

                                            {{-- Priority dot --}}
                                            <span class="w-2 h-2 rounded-full
                                                flex-shrink-0
                                                {{ $task->priority === 'high'
                                                    ? 'bg-red-400' :
                                                  ($task->priority === 'medium'
                                                    ? 'bg-amber-400'
                                                    : 'bg-gray-300') }}">
                                            </span>

                                            {{-- Date --}}
                                            @if ($task->scheduled_date)
                                                <span class="text-xs
                                                    {{ $task->is_overdue
                                                        ? 'text-red-500 font-semibold'
                                                        : 'text-gray-400' }}">
                                                    {{ $task->scheduled_date
                                                        ->format('M j') }}
                                                </span>
                                            @endif

                                            {{-- Running indicator --}}
                                            @if ($task->is_running)
                                                <span class="text-xs text-green-600
                                                             font-semibold animate-pulse">
                                                    ⏱
                                                </span>
                                            @endif

                                        </div>

                                        {{-- Delete --}}
                                        <button type="button"
                                                onclick="event.stopPropagation();
                                                    deleteTask(
                                                        {{ $task->task_id }},
                                                        '{{ addslashes($task->title) }}'
                                                    )"
                                                class="opacity-0 group-hover:opacity-100
                                                       w-6 h-6 rounded-lg flex items-center
                                                       justify-center text-gray-300
                                                       hover:text-red-500 hover:bg-red-50
                                                       transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none"
                                                 stroke="currentColor"
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0
                                                         0116.138 21H7.862a2 2 0
                                                         01-1.995-1.858L5 7m5 4v6m4-6v6
                                                         m1-10V4a1 1 0 00-1-1h-4a1 1 0
                                                         00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>

                                    </div>
                                </div>

                                {{-- Hidden delete form --}}
                                <form id="del-task-{{ $task->task_id }}"
                                      method="POST"
                                      action="{{ route('tasks.destroy',
                                          $task->task_id) }}"
                                      class="hidden">
                                    @csrf @method('DELETE')
                                </form>

                            </div>
                        @empty
                            <div class="text-center py-8 text-xs text-gray-300
                                        font-medium">
                                Empty
                            </div>
                        @endforelse
                    </div>

                </div>
            @endforeach
        </div>
    </div>

    {{-- ===== LIST VIEW ===== --}}
    <div id="view-list" class="hidden space-y-2">
        @forelse ($tasks as $task)
            @php
                $gc  = $task->goal?->color ?? '#e5e7eb';
                $gbg = \App\Helpers\ColorPalette::bg($gc);
                $gt  = \App\Helpers\ColorPalette::text($gc);
            @endphp
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden
                        hover:shadow-sm transition-all cursor-pointer group"
                 style="border-left: 4px solid {{ $gc }}"
                 onclick="window.location=
                     '{{ route('tasks.show', $task->task_id) }}'">
                <div class="flex items-center gap-3 p-4">

                    {{-- Circle --}}
                    <form action="{{ route('tasks.update', $task->task_id) }}"
                          method="POST"
                          onclick="event.stopPropagation()"
                          class="flex-shrink-0">
                        @csrf @method('PUT')
                        <input type="hidden" name="title"
                               value="{{ $task->title }}">
                        <input type="hidden" name="description"
                               value="{{ $task->description }}">
                        <input type="hidden" name="goal_id"
                               value="{{ $task->goal_id }}">
                        <input type="hidden" name="priority"
                               value="{{ $task->priority }}">
                        <input type="hidden" name="scheduled_date"
                               value="{{ $task->scheduled_date?->format('Y-m-d') }}">
                        <input type="hidden" name="status"
                               value="{{ $task->status === 'done'
                                   ? 'todo' : 'done' }}">
                        <button type="submit"
                                class="w-5 h-5 rounded-full border-2 flex items-center
                                       justify-center transition-all hover:scale-110"
                                style="{{ $task->status === 'done'
                                    ? "background:{$gc};border-color:{$gc}"
                                    : "border-color:{$gc}" }}">
                            @if ($task->status === 'done')
                                <svg class="w-2.5 h-2.5 text-white" fill="none"
                                     stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            @endif
                        </button>
                    </form>

                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold
                                  {{ $task->status === 'done'
                                      ? 'line-through text-gray-400'
                                      : 'text-gray-800' }}">
                            {{ $task->title }}
                        </p>
                        <div class="flex items-center gap-2 mt-0.5">
                            @if ($task->goal)
                                <span class="text-xs font-semibold px-2 py-0.5
                                             rounded-lg"
                                      style="background:{{ $gbg }};
                                             color:{{ $gt }}">
                                    {{ $task->goal->title }}
                                </span>
                            @endif
                            @if ($task->scheduled_date)
                                <span class="text-xs
                                    {{ $task->is_overdue
                                        ? 'text-red-500 font-semibold'
                                        : 'text-gray-400' }}">
                                    {{ $task->scheduled_date->format('M j') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <span class="w-2 h-2 rounded-full flex-shrink-0
                        {{ $task->priority === 'high'   ? 'bg-red-400' :
                          ($task->priority === 'medium' ? 'bg-amber-400'
                                                        : 'bg-gray-300') }}">
                    </span>

                    <button type="button"
                            onclick="event.stopPropagation();
                                deleteTask(
                                    {{ $task->task_id }},
                                    '{{ addslashes($task->title) }}'
                                )"
                            class="opacity-0 group-hover:opacity-100 w-7 h-7
                                   rounded-lg flex items-center justify-center
                                   text-gray-300 hover:text-red-500
                                   hover:bg-red-50 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                                     a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4
                                     a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>

                    {{-- Hidden delete form --}}
                    <form id="del-task-list-{{ $task->task_id }}"
                          method="POST"
                          action="{{ route('tasks.destroy', $task->task_id) }}"
                          class="hidden">
                        @csrf @method('DELETE')
                    </form>

                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-16 h-16 rounded-2xl bg-indigo-50 flex items-center
                            justify-center mb-4">
                    <svg class="w-8 h-8 text-indigo-300" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="1.5"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0
                                 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2
                                 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2
                                 4-4"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-700 mb-1">
                    No tasks yet
                </h3>
                <p class="text-sm text-gray-400 mb-5 max-w-xs">
                    Break your goals into small actions.
                </p>
                <a href="{{ route('tasks.create') }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm
                          font-bold px-6 py-2.5 rounded-xl transition-colors">
                    Create your first task
                </a>
            </div>
        @endforelse
    </div>

    {{-- FAB --}}
    <a href="{{ route('tasks.create') }}"
       class="fixed bottom-8 right-8 w-14 h-14 bg-indigo-600 hover:bg-indigo-700
              text-white rounded-full shadow-lg hover:shadow-xl flex items-center
              justify-center text-2xl font-light transition-all hover:scale-110
              z-50 active:scale-95">
        +
    </a>

<script>
// View toggle
function setView(v) {
    document.getElementById('view-board')
        .classList.toggle('hidden', v !== 'board');
    document.getElementById('view-list')
        .classList.toggle('hidden', v !== 'list');

    document.getElementById('btn-board').className =
        v === 'board'
            ? 'px-3 py-1.5 rounded-lg text-xs font-semibold transition-all bg-white text-gray-700 shadow-sm'
            : 'px-3 py-1.5 rounded-lg text-xs font-semibold transition-all text-gray-400 hover:text-gray-600';

    document.getElementById('btn-list').className =
        v === 'list'
            ? 'px-3 py-1.5 rounded-lg text-xs font-semibold transition-all bg-white text-gray-700 shadow-sm'
            : 'px-3 py-1.5 rounded-lg text-xs font-semibold transition-all text-gray-400 hover:text-gray-600';
}

// Delete with SweetAlert
function deleteTask(id, title) {
    Swal.fire({
        title: 'Delete task?',
        html: `<span style="color:#6b7280;font-size:14px">
                   "${title}"<br>This cannot be undone.
               </span>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#e5e7eb',
        confirmButtonText: 'Yes, delete',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
    }).then(r => {
        if (r.isConfirmed) {
            // Try board form first, then list form
            const form = document.getElementById(`del-task-${id}`)
                      || document.getElementById(`del-task-list-${id}`);
            if (form) form.submit();
        }
    });
}
</script>

<style>
.swal2-popup { border-radius: 20px !important; }
</style>

</x-layouts.app>