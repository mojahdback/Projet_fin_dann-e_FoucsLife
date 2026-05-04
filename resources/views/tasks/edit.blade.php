{{-- resources/views/tasks/edit.blade.php --}}
<x-layouts.app title="Edit Task">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@php
    $gc  = $task->goal?->color ?? '#6366f1';
    $gbg = \App\Helpers\ColorPalette::bg($gc);
    $gt  = \App\Helpers\ColorPalette::text($gc);
@endphp

<div class="max-w-xl mx-auto">

    {{-- Back --}}
    <a href="{{ route('tasks.show', $task->task_id) }}"
       class="inline-flex items-center gap-1.5 text-sm text-gray-400
              hover:text-gray-600 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Task
    </a>

    {{-- Preview card --}}
    <div class="rounded-2xl overflow-hidden border border-gray-100
                mb-6 shadow-sm">
        <div class="h-1.5" style="background: {{ $gc }}"></div>
        <div class="px-5 py-4 flex items-center gap-3">
            <div class="w-2 h-10 rounded-full flex-shrink-0"
                 style="background: {{ $gc }}"></div>
            <div>
                <p class="text-sm font-bold text-gray-800">
                    {{ $task->title }}
                </p>
                @if ($task->goal)
                    <p class="text-xs font-semibold mt-0.5"
                       style="color: {{ $gt }}">
                        {{ $task->goal->title }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700
                    text-sm rounded-2xl p-4 mb-5">
            <ul class="space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="flex items-center gap-2">
                        <span class="w-1 h-1 rounded-full bg-red-400 flex-shrink-0">
                        </span>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tasks.update', $task->task_id) }}" method="POST"
          class="space-y-4" id="edit-form">
    @csrf @method('PUT')

        {{-- Title + Description --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm
                    space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase
                              tracking-wider mb-2">Title</label>
                <input type="text" name="title"
                       value="{{ old('title', $task->title) }}"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200
                              focus:outline-none focus:ring-2 focus:ring-indigo-400
                              text-gray-800 text-sm transition-all
                              @error('title') border-red-400 @enderror">
                @error('title')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase
                              tracking-wider mb-2">
                    Description
                    <span class="text-gray-300 font-normal normal-case">
                        (optional)
                    </span>
                </label>
                <textarea name="description" rows="2"
                          class="w-full px-4 py-3 rounded-xl border border-gray-200
                                 focus:outline-none focus:ring-2 focus:ring-indigo-400
                                 text-sm text-gray-700 resize-none transition-all">{{ old('description', $task->description) }}</textarea>
            </div>
        </div>

        {{-- Priority + Status --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm
                    space-y-4">

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase
                              tracking-wider mb-2">Priority</label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach ([
                        'low'    => ['Low',    '🟢',
                                     'border-gray-200 bg-gray-50 text-gray-600'],
                        'medium' => ['Medium', '🟡',
                                     'border-amber-300 bg-amber-50 text-amber-700'],
                        'high'   => ['High',   '🔴',
                                     'border-red-300 bg-red-50 text-red-700'],
                    ] as $val => [$label, $dot, $active])
                        <label class="flex items-center justify-center gap-2 py-3
                                      rounded-xl border cursor-pointer font-bold
                                      text-sm transition-all
                                      {{ old('priority', $task->priority) === $val
                                          ? $active
                                          : 'border-gray-200 text-gray-400' }}">
                            <input type="radio" name="priority"
                                   value="{{ $val }}" class="hidden"
                                   {{ old('priority', $task->priority) === $val
                                       ? 'checked' : '' }}>
                            <span>{{ $dot }}</span> {{ $label }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase
                              tracking-wider mb-2">Status</label>
                <div class="grid grid-cols-4 gap-2">
                    @foreach ([
                        'todo'        => ['To Do',       'bg-blue-50 border-blue-200 text-blue-700'],
                        'in_progress' => ['In Progress', 'bg-amber-50 border-amber-200 text-amber-700'],
                        'done'        => ['Done',        'bg-green-50 border-green-200 text-green-700'],
                        'cancelled'   => ['Cancelled',   'bg-gray-50 border-gray-200 text-gray-600'],
                    ] as $val => [$label, $active])
                        <label class="flex items-center justify-center py-2.5
                                      rounded-xl border cursor-pointer font-bold
                                      text-xs transition-all
                                      {{ old('status', $task->status) === $val
                                          ? $active
                                          : 'border-gray-200 text-gray-400
                                             hover:border-gray-300' }}">
                            <input type="radio" name="status"
                                   value="{{ $val }}" class="hidden"
                                   {{ old('status', $task->status) === $val
                                       ? 'checked' : '' }}>
                            {{ $label }}
                        </label>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- Goal --}}
        @if ($goals->isNotEmpty())
            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
                <label class="block text-xs font-bold text-gray-500 uppercase
                              tracking-wider mb-3">
                    Linked Goal
                    <span class="text-gray-300 font-normal normal-case">
                        (optional)
                    </span>
                </label>
                <div class="space-y-2">

                    <label class="edit-goal-label flex items-center gap-3 p-3
                                  rounded-xl border cursor-pointer transition-all
                                  {{ old('goal_id', $task->goal_id) == ''
                                      ? 'border-indigo-300 bg-indigo-50'
                                      : 'border-gray-200 hover:border-gray-300' }}">
                        <input type="radio" name="goal_id" value=""
                               class="hidden edit-goal-radio"
                               {{ old('goal_id', $task->goal_id) == ''
                                   ? 'checked' : '' }}>
                        <div class="w-3 h-3 rounded-full bg-gray-300 flex-shrink-0">
                        </div>
                        <span class="text-sm font-semibold text-gray-500">
                            No linked goal
                        </span>
                    </label>

                    @foreach ($goals as $goal)
                        @php
                            $gc2  = $goal->color ?? '#6366f1';
                            $gbg2 = \App\Helpers\ColorPalette::bg($gc2);
                            $gt2  = \App\Helpers\ColorPalette::text($gc2);
                            $sel  = old('goal_id', $task->goal_id)
                                        == $goal->goal_id;
                        @endphp
                        <label class="edit-goal-label flex items-center gap-3 p-3
                                      rounded-xl border cursor-pointer transition-all"
                               style="{{ $sel
                                            ? "background:{$gbg2};border-color:{$gc2}"
                                            : '' }}">
                            <input type="radio" name="goal_id"
                                   value="{{ $goal->goal_id }}"
                                   class="hidden edit-goal-radio"
                                   {{ $sel ? 'checked' : '' }}
                                   data-color="{{ $gc2 }}"
                                   data-bg="{{ $gbg2 }}"
                                   data-text="{{ $gt2 }}">
                            <div class="w-3 h-3 rounded-full flex-shrink-0"
                                 style="background: {{ $gc2 }}"></div>
                            <span class="text-sm font-bold edit-goal-text"
                                  style="{{ $sel ? "color:{$gt2}" : '' }}">
                                {{ $goal->title }}
                            </span>
                        </label>
                    @endforeach

                </div>
            </div>
        @else
            <input type="hidden" name="goal_id" value="">
        @endif

        {{-- Dates --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm
                    space-y-4">

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase
                                  tracking-wider mb-2">Scheduled date</label>
                    <input type="date" name="scheduled_date"
                           value="{{ old('scheduled_date',
                               $task->scheduled_date?->format('Y-m-d')) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400
                                  text-sm text-gray-700 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase
                                  tracking-wider mb-2">Time</label>
                    <input type="time" name="scheduled_time"
                           value="{{ old('scheduled_time', $task->scheduled_time) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400
                                  text-sm text-gray-700 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase
                              tracking-wider mb-2">
                    Due date
                    <span class="text-gray-300 font-normal normal-case">
                        (optional)
                    </span>
                </label>
                <input type="date" name="due_date"
                       value="{{ old('due_date',
                           $task->due_date?->format('Y-m-d')) }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200
                              focus:outline-none focus:ring-2 focus:ring-indigo-400
                              text-sm text-gray-700 transition-all">
            </div>

        </div>

        {{-- Repeat days --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
            <label class="block text-xs font-bold text-gray-500 uppercase
                          tracking-wider mb-3">
                Repeat on
                <span class="text-gray-300 font-normal normal-case">
                    (optional)
                </span>
            </label>
            <div class="flex gap-1.5">
                @foreach ([
                    'mon'=>'M','tue'=>'T','wed'=>'W',
                    'thu'=>'T','fri'=>'F','sat'=>'S','sun'=>'S'
                ] as $val => $letter)
                    <label class="edit-day-chip flex-1 aspect-square flex items-center
                                  justify-center rounded-xl border cursor-pointer
                                  font-bold text-xs transition-all"
                           style="{{ in_array($val, old('repeat_days',
                                        $task->repeat_days ?? []))
                                        ? "background:{$gc};color:white;
                                           border-color:{$gc}"
                                        : 'border-color:#e5e7eb;color:#9ca3af' }}"
                           data-color="{{ $gc }}">
                        <input type="checkbox" name="repeat_days[]"
                               value="{{ $val }}" class="hidden"
                               {{ in_array($val, old('repeat_days',
                                   $task->repeat_days ?? []))
                                   ? 'checked' : '' }}>
                        {{ $letter }}
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Reminder --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
            <label class="block text-xs font-bold text-gray-500 uppercase
                          tracking-wider mb-3">
                Email reminder
                <span class="text-gray-300 font-normal normal-case">
                    (optional)
                </span>
            </label>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5">
                        Date
                    </label>
                    <input type="date" name="remind_date"
                           id="edit-remind-date"
                           value="{{ old('remind_date',
                               $task->remind_at?->format('Y-m-d')) }}"
                           class="w-full px-4 py-2.5 rounded-xl border
                                  border-gray-200 focus:outline-none
                                  focus:ring-2 focus:ring-indigo-400
                                  text-sm text-gray-700 transition-all">
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5">
                        Time
                    </label>
                    <input type="time" name="remind_time"
                           id="edit-remind-time"
                           value="{{ old('remind_time',
                               $task->remind_at?->format('H:i')) }}"
                           class="w-full px-4 py-2.5 rounded-xl border
                                  border-gray-200 focus:outline-none
                                  focus:ring-2 focus:ring-indigo-400
                                  text-sm text-gray-700 transition-all">
                </div>
            </div>
            <input type="hidden" name="remind_at" id="edit-remind-at"
                   value="{{ old('remind_at',
                       $task->remind_at?->format('Y-m-d H:i:s')) }}">
        </div>

        {{-- Actions --}}
        <div class="flex gap-3 pb-8">
            <button type="submit" id="edit-submit-btn"
                    class="flex-1 text-white font-bold py-3.5 rounded-xl
                           transition-all hover:opacity-90 active:scale-98
                           flex items-center justify-center gap-2"
                    style="background: {{ $gc }}">
                <span id="edit-submit-text">Save Changes</span>
                <span id="edit-submit-spinner"
                      class="hidden w-4 h-4 border-2 border-white
                             border-t-transparent rounded-full animate-spin">
                </span>
            </button>
            <a href="{{ route('tasks.show', $task->task_id) }}"
               class="px-6 py-3.5 rounded-xl border border-gray-200 text-sm
                      font-bold text-gray-500 hover:bg-gray-50
                      transition-colors text-center">
                Cancel
            </a>
        </div>

    </form>
</div>

<script>
// Goal radio highlight
document.querySelectorAll('.edit-goal-radio').forEach(input => {
    function apply() {
        document.querySelectorAll('.edit-goal-label').forEach(l => {
            l.style.background  = '';
            l.style.borderColor = '';
            l.classList.remove('border-indigo-300', 'bg-indigo-50');
            l.classList.add('border-gray-200');
            const span = l.querySelector('.edit-goal-text');
            if (span) span.style.color = '';
        });

        const label = input.closest('label');
        if (!label) return;

        if (input.value && input.dataset.bg) {
            label.classList.remove('border-gray-200');
            label.style.background  = input.dataset.bg;
            label.style.borderColor = input.dataset.color;
            const span = label.querySelector('.edit-goal-text');
            if (span) span.style.color = input.dataset.text;
        } else {
            label.classList.remove('border-gray-200');
            label.classList.add('border-indigo-300', 'bg-indigo-50');
        }
    }
    if (input.checked) apply();
    input.addEventListener('change', apply);
});

// Day chips
document.querySelectorAll('.edit-day-chip').forEach(label => {
    const input = label.querySelector('input');
    const color = label.dataset.color;
    label.addEventListener('click', () => {
        setTimeout(() => {
            if (input.checked) {
                label.style.background  = color;
                label.style.color       = 'white';
                label.style.borderColor = color;
            } else {
                label.style.background  = '';
                label.style.color       = '#9ca3af';
                label.style.borderColor = '#e5e7eb';
            }
        }, 10);
    });
});

// Combine remind_at
function buildEditRemindAt() {
    const d = document.getElementById('edit-remind-date')?.value;
    const t = document.getElementById('edit-remind-time')?.value || '08:00';
    document.getElementById('edit-remind-at').value =
        d ? `${d} ${t}:00` : '';
}
document.getElementById('edit-remind-date')
    ?.addEventListener('change', buildEditRemindAt);
document.getElementById('edit-remind-time')
    ?.addEventListener('change', buildEditRemindAt);

// Loading state on submit
document.getElementById('edit-form')
    ?.addEventListener('submit', function() {
        const btn     = document.getElementById('edit-submit-btn');
        const text    = document.getElementById('edit-submit-text');
        const spinner = document.getElementById('edit-submit-spinner');
        btn.disabled  = true;
        btn.classList.add('opacity-80');
        text.textContent = 'Saving...';
        spinner.classList.remove('hidden');
    });
</script>

<style>
.swal2-popup { border-radius: 20px !important; }
</style>

</x-layouts.app>