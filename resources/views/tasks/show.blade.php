{{-- resources/views/tasks/show.blade.php --}}
<x-layouts.app title="{{ $task->title }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@php
    $gc  = $task->goal?->color ?? '#6366f1';
    $gbg = \App\Helpers\ColorPalette::bg($gc);
    $gt  = \App\Helpers\ColorPalette::text($gc);
@endphp

<div class="max-w-2xl mx-auto space-y-4">

    {{-- Back --}}
    <a href="{{ route('tasks.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-gray-400
              hover:text-gray-600 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Tasks
    </a>

    {{-- ===== HERO ===== --}}
    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden
                shadow-sm">
        <div class="h-2" style="background: {{ $gc }}"></div>
        <div class="p-6">

            {{-- Badges --}}
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <span class="text-xs font-bold px-2.5 py-1 rounded-full
                    {{ $task->status === 'done'        ? 'bg-green-100 text-green-700' :
                      ($task->status === 'in_progress' ? 'bg-amber-100 text-amber-700' :
                      ($task->status === 'cancelled'   ? 'bg-gray-100 text-gray-500' :
                                                         'bg-blue-100 text-blue-700')) }}">
                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                </span>

                <span class="text-xs font-bold px-2.5 py-1 rounded-full
                    {{ $task->priority === 'high'   ? 'bg-red-100 text-red-700' :
                      ($task->priority === 'medium' ? 'bg-amber-100 text-amber-700' :
                                                      'bg-gray-100 text-gray-600') }}">
                    {{ ucfirst($task->priority) }} priority
                </span>

                @if ($task->is_overdue)
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full
                                 bg-red-100 text-red-600">
                        ⚠ Overdue
                    </span>
                @endif

                @if ($task->is_running)
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full
                                 bg-green-100 text-green-700 animate-pulse">
                        ⏱ Timer running
                    </span>
                @endif

                @if ($task->remind_at && !$task->reminder_sent)
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full
                                 bg-purple-100 text-purple-700">
                        🔔 Reminder set
                    </span>
                @endif
            </div>

            {{-- Title --}}
            <h1 class="text-2xl font-bold mb-2
                       {{ $task->status === 'done'
                           ? 'line-through text-gray-400'
                           : 'text-gray-800' }}">
                {{ $task->title }}
            </h1>

            @if ($task->description)
                <p class="text-sm text-gray-500 leading-relaxed">
                    {{ $task->description }}
                </p>
            @endif

            {{-- Goal link --}}
            @if ($task->goal)
                <a href="{{ route('goals.show', $task->goal->goal_id) }}"
                   class="inline-flex items-center gap-2 mt-4 text-xs
                          font-bold px-3 py-2 rounded-xl transition-all
                          hover:opacity-80"
                   style="background:{{ $gbg }};color:{{ $gt }}">
                    <span class="w-2 h-2 rounded-full flex-shrink-0"
                          style="background:{{ $gc }}"></span>
                    {{ $task->goal->title }}
                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            @endif

        </div>
    </div>

    {{-- ===== INFO GRID ===== --}}
    <div class="grid grid-cols-3 gap-3">
        <div class="bg-white border border-gray-100 rounded-2xl p-4
                    shadow-sm text-center">
            <p class="text-xs text-gray-400 mb-1.5 font-medium">Scheduled</p>
            <p class="text-sm font-bold
                      {{ $task->is_overdue ? 'text-red-500' : 'text-gray-800' }}">
                {{ $task->scheduled_date
                    ? $task->scheduled_date->format('M j, Y')
                    : '—' }}
            </p>
            @if ($task->scheduled_time)
                <p class="text-xs text-gray-400 mt-0.5">
                    at {{ \Carbon\Carbon::parse($task->scheduled_time)
                        ->format('H:i') }}
                </p>
            @endif
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl p-4
                    shadow-sm text-center">
            <p class="text-xs text-gray-400 mb-1.5 font-medium">Due date</p>
            <p class="text-sm font-bold text-gray-800">
                {{ $task->due_date
                    ? $task->due_date->format('M j, Y')
                    : '—' }}
            </p>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl p-4
                    shadow-sm text-center">
            <p class="text-xs text-gray-400 mb-1.5 font-medium">Time spent</p>
            <p class="text-sm font-bold"
               style="color: {{ $gc }}">
                {{ $task->total_minutes > 0
                    ? $task->formatted_time
                    : '—' }}
            </p>
        </div>
    </div>

    {{-- ===== REMINDER INFO ===== --}}
    @if ($task->remind_at)
        <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center
                            justify-center text-lg flex-shrink-0"
                     style="background: {{ $gbg }}">
                    🔔
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-800">
                        Email reminder
                    </p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        {{ $task->remind_at->format('M j, Y \a\t H:i') }}
                        @if ($task->reminder_sent)
                            <span class="text-green-600 font-bold ml-2">
                                ✓ Sent
                            </span>
                        @else
                            <span class="text-amber-600 font-bold ml-2">
                                Pending
                            </span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- ===== REPEAT DAYS ===== --}}
    @if ($task->repeat_days && count($task->repeat_days) > 0)
        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
            <p class="text-xs font-bold text-gray-400 uppercase
                      tracking-widest mb-3">
                Repeats on
            </p>
            <div class="flex gap-2">
                @foreach ([
                    'mon'=>'M','tue'=>'T','wed'=>'W',
                    'thu'=>'T','fri'=>'F','sat'=>'S','sun'=>'S'
                ] as $day => $letter)
                    <div class="flex-1 aspect-square flex items-center
                                justify-center rounded-xl text-xs font-bold"
                         style="{{ in_array($day, $task->repeat_days)
                                    ? "background:{$gc};color:white"
                                    : 'background:#f9fafb;color:#d1d5db' }}">
                        {{ $letter }}
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ===== TIMER ===== --}}
    @if ($task->status !== 'done' && $task->status !== 'cancelled')
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm
                    overflow-hidden">

            <div class="px-5 py-4 border-b border-gray-50">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                    Time Tracker
                </p>
            </div>

            <div class="p-5">
                @if ($isRunning)
                    {{-- Timer running state --}}
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-2.5 h-2.5 rounded-full animate-pulse"
                                 style="background: {{ $gc }}"></div>
                            <span class="text-sm font-bold text-gray-700">
                                Timer is running
                            </span>
                        </div>

                        {{-- Live counter --}}
                        @if ($activeLog)
                            <span class="text-sm font-mono font-bold text-gray-500"
                                  id="live-timer">
                                00:00
                            </span>
                        @endif
                    </div>

                    {{-- Progress bar --}}
                    <div class="h-1.5 bg-gray-100 rounded-full mb-4 overflow-hidden">
                        <div class="h-1.5 rounded-full animate-pulse"
                             style="width: 60%; background: {{ $gc }}"></div>
                    </div>

                    <form action="{{ route('tasks.timer.stop', $task->task_id) }}"
                          method="POST" id="stop-timer-form">
                        @csrf
                        <button type="submit"
                                id="stop-timer-btn"
                                class="w-full flex items-center justify-center
                                       gap-2 py-3 rounded-xl bg-red-500
                                       hover:bg-red-600 active:bg-red-700
                                       text-white font-bold text-sm
                                       transition-all">
                            <span id="stop-btn-text">⏹ Stop Timer</span>
                            <span id="stop-btn-spinner"
                                  class="hidden w-4 h-4 border-2 border-white
                                         border-t-transparent rounded-full
                                         animate-spin"></span>
                        </button>
                    </form>

                @else
                    {{-- Timer idle state --}}
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-700">
                                Ready to start?
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Track time for this task
                            </p>
                        </div>
                        @if ($task->total_minutes > 0)
                            <div class="text-right">
                                <p class="text-xs text-gray-400">Total so far</p>
                                <p class="text-sm font-bold"
                                   style="color: {{ $gc }}">
                                    {{ $task->formatted_time }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <form action="{{ route('tasks.timer.start', $task->task_id) }}"
                          method="POST" id="start-timer-form">
                        @csrf
                        <button type="submit"
                                id="start-timer-btn"
                                class="w-full flex items-center justify-center
                                       gap-2 py-3 rounded-xl font-bold text-sm
                                       text-white transition-all hover:opacity-90
                                       active:scale-98"
                                style="background: {{ $gc }}">
                            <span id="start-btn-text">▶ Start Timer</span>
                            <span id="start-btn-spinner"
                                  class="hidden w-4 h-4 border-2 border-white
                                         border-t-transparent rounded-full
                                         animate-spin"></span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @endif

    {{-- ===== TIME LOGS ===== --}}
    @if ($timeLogs->isNotEmpty())
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm">

            <div class="px-5 py-4 border-b border-gray-50 flex items-center
                        justify-between">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                    Time Logs
                </p>
                <span class="text-xs font-bold" style="color: {{ $gc }}">
                    Total: {{ $task->formatted_time }}
                </span>
            </div>

            <div class="divide-y divide-gray-50">
                @foreach ($timeLogs as $log)
                    <div class="flex items-center justify-between px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-1.5 rounded-full flex-shrink-0"
                                 style="background: {{ $gc }}"></div>
                            <span class="text-sm text-gray-600">
                                {{ $log->start_time->format('M j — H:i') }}
                                <span class="text-gray-300 mx-1">→</span>
                                {{ $log->end_time->format('H:i') }}
                            </span>
                        </div>
                        <span class="text-sm font-bold"
                              style="color: {{ $gc }}">
                            {{ $log->formatted_duration }}
                        </span>
                    </div>
                @endforeach
            </div>

        </div>
    @endif

    {{-- ===== ACTIONS ===== --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <p class="text-xs text-gray-400">
                Updated {{ $task->updated_at->diffForHumans() }}
            </p>
            <div class="flex gap-2">
                <a href="{{ route('tasks.edit', $task->task_id) }}"
                   class="flex items-center gap-1.5 px-4 py-2 rounded-xl
                          border text-sm font-bold transition-all hover:opacity-80"
                   style="border-color:{{ $gc }};color:{{ $gc }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0
                                 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828
                                 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <button type="button"
                        onclick="deleteThisTask()"
                        class="flex items-center gap-1.5 px-4 py-2 rounded-xl
                               border border-red-100 text-sm font-bold
                               text-red-500 hover:bg-red-50 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2
                                 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1
                                 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete
                </button>
            </div>
        </div>
    </div>

</div>

{{-- Hidden delete form --}}
<form id="delete-task-form" method="POST"
      action="{{ route('tasks.destroy', $task->task_id) }}" class="hidden">
    @csrf @method('DELETE')
</form>

<script>
// Delete SweetAlert
function deleteThisTask() {
    Swal.fire({
        title: 'Delete task?',
        html: `<span style="color:#6b7280;font-size:14px">
                   "{{ addslashes($task->title) }}"<br>
                   This cannot be undone.
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
            document.getElementById('delete-task-form').submit();
        }
    });
}

// Loading state for timer buttons
document.getElementById('start-timer-form')
    ?.addEventListener('submit', function() {
        document.getElementById('start-btn-text').textContent = 'Starting...';
        document.getElementById('start-btn-spinner').classList.remove('hidden');
        document.getElementById('start-timer-btn').disabled = true;
        document.getElementById('start-timer-btn').classList.add('opacity-80');
    });

document.getElementById('stop-timer-form')
    ?.addEventListener('submit', function() {
        document.getElementById('stop-btn-text').textContent = 'Stopping...';
        document.getElementById('stop-btn-spinner').classList.remove('hidden');
        document.getElementById('stop-timer-btn').disabled = true;
        document.getElementById('stop-timer-btn').classList.add('opacity-80');
    });

// Live timer counter
@if ($isRunning && $activeLog)
    const startTime = new Date('{{ $activeLog->start_time->toISOString() }}');
    function updateLiveTimer() {
        const now     = new Date();
        const elapsed = Math.floor((now - startTime) / 1000);
        const h       = Math.floor(elapsed / 3600);
        const m       = Math.floor((elapsed % 3600) / 60);
        const s       = elapsed % 60;

        const el = document.getElementById('live-timer');
        if (el) {
            el.textContent = h > 0
                ? `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`
                : `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
        }
    }
    updateLiveTimer();
    setInterval(updateLiveTimer, 1000);
@endif
</script>

<style>
.swal2-popup { border-radius: 20px !important; }
</style>

</x-layouts.app>