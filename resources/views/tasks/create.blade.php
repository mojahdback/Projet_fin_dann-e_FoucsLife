{{-- resources/views/tasks/create.blade.php --}}
<x-layouts.app title="New Task">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="max-w-xl mx-auto">

    {{-- Back --}}
    <a href="{{ route('tasks.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-gray-400
              hover:text-gray-600 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back
    </a>

    {{-- Steps --}}
    <div class="flex items-center gap-2 mb-8">
        @foreach ([1 => 'What', 2 => 'When', 3 => 'Remind'] as $n => $label)
            <div class="flex items-center gap-2 {{ $n < 3 ? 'flex-1' : '' }}">
                <div class="step-dot w-7 h-7 rounded-full flex items-center
                            justify-center text-xs font-bold transition-all
                            {{ $n === 1
                                ? 'bg-indigo-600 text-white'
                                : 'bg-gray-100 text-gray-400' }}"
                     data-step="{{ $n }}">
                    {{ $n }}
                </div>
                <span class="text-xs font-medium transition-all
                             {{ $n === 1 ? 'text-gray-800' : 'text-gray-400' }}"
                      data-step-label="{{ $n }}">
                    {{ $label }}
                </span>
                @if ($n < 3)
                    <div class="flex-1 h-px bg-gray-100 mx-1"></div>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Server errors --}}
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

    <form action="{{ route('tasks.store') }}" method="POST" id="task-form">
    @csrf

    {{-- ===== STEP 1: WHAT ===== --}}
    <div class="step-panel" data-panel="1">
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm space-y-5">

        <div>
            <p class="text-xs font-semibold text-indigo-600 uppercase
                       tracking-widest mb-1">Step 1 of 3</p>
            <h2 class="text-xl font-bold text-gray-800">What's the task?</h2>
        </div>

        {{-- Title --}}
        <div>
            <label class="block text-xs font-semibold text-gray-500
                          uppercase tracking-wider mb-2">Task name</label>
            <input type="text" name="title" id="task-title"
                   value="{{ old('title') }}"
                   placeholder="e.g. Review project proposal"
                   autofocus
                   class="w-full px-4 py-3.5 rounded-xl border border-gray-200
                          focus:outline-none focus:ring-2 focus:ring-indigo-400
                          text-gray-800 placeholder-gray-300 transition-all
                          @error('title') border-red-400 bg-red-50 @enderror">
            <p class="text-xs text-red-500 mt-1.5 hidden" id="title-error">
                Task name is required (min 2 characters).
            </p>
        </div>

        {{-- Description --}}
        <div>
            <label class="block text-xs font-semibold text-gray-500
                          uppercase tracking-wider mb-2">
                Description
                <span class="text-gray-300 font-normal normal-case">
                    (optional)
                </span>
            </label>
            <textarea name="description" rows="2"
                      placeholder="Add details..."
                      class="w-full px-4 py-3 rounded-xl border border-gray-200
                             focus:outline-none focus:ring-2 focus:ring-indigo-400
                             text-sm text-gray-700 placeholder-gray-300
                             resize-none transition-all">{{ old('description') }}</textarea>
        </div>

        {{-- Priority --}}
        <div>
            <label class="block text-xs font-semibold text-gray-500
                          uppercase tracking-wider mb-2">Priority</label>
            <div class="grid grid-cols-3 gap-2">
                @foreach ([
                    'low'    => ['Low',    '🟢',
                                 'border-gray-200 bg-gray-50 text-gray-600'],
                    'medium' => ['Medium', '🟡',
                                 'border-amber-300 bg-amber-50 text-amber-700'],
                    'high'   => ['High',   '🔴',
                                 'border-red-300 bg-red-50 text-red-700'],
                ] as $val => [$label, $dot, $active])
                    <label class="priority-chip flex items-center justify-center
                                  gap-2 py-3 rounded-xl border cursor-pointer
                                  font-semibold text-sm transition-all
                                  {{ old('priority', 'medium') === $val
                                      ? $active
                                      : 'border-gray-200 text-gray-400
                                         hover:border-gray-300' }}"
                           data-active="{{ $active }}"
                           data-val="{{ $val }}">
                        <input type="radio" name="priority"
                               value="{{ $val }}" class="hidden"
                               {{ old('priority', 'medium') === $val
                                   ? 'checked' : '' }}>
                        <span>{{ $dot }}</span>
                        {{ $label }}
                    </label>
                @endforeach
            </div>
            <p class="text-xs text-red-500 mt-1.5 hidden" id="priority-error">
                Please select a priority.
            </p>
        </div>

        {{-- Linked Goal --}}
        <div>
            <label class="block text-xs font-semibold text-gray-500
                          uppercase tracking-wider mb-2">
                Link to a Goal
                <span class="text-gray-300 font-normal normal-case">
                    (optional)
                </span>
            </label>

            @if ($goals->isEmpty())
                <div class="p-4 rounded-xl bg-gray-50 border border-dashed
                            border-gray-200 text-center">
                    <p class="text-xs text-gray-400">No active goals yet.</p>
                    <a href="{{ route('goals.create') }}"
                       class="text-xs font-semibold text-indigo-500
                              hover:underline mt-1 inline-block">
                        Create a goal first →
                    </a>
                </div>
                <input type="hidden" name="goal_id" value="">
            @else
                {{-- Loading state overlay --}}
                <div id="goals-loading"
                     class="hidden p-4 rounded-xl bg-gray-50 border border-gray-200
                            flex items-center justify-center gap-2">
                    <div class="w-4 h-4 border-2 border-indigo-400
                                border-t-transparent rounded-full animate-spin">
                    </div>
                    <span class="text-xs text-gray-400">Loading goals...</span>
                </div>

                <div class="space-y-2" id="goals-list">

                    {{-- No goal --}}
                    <label class="goal-radio-label flex items-center gap-3 p-3
                                  rounded-xl border cursor-pointer transition-all
                                  {{ old('goal_id', $selectedGoalId ?? '') == ''
                                      ? 'border-indigo-300 bg-indigo-50'
                                      : 'border-gray-200 hover:border-gray-300' }}">
                        <input type="radio" name="goal_id" value=""
                               class="hidden goal-radio"
                               {{ old('goal_id', $selectedGoalId ?? '') == ''
                                   ? 'checked' : '' }}>
                        <div class="w-3 h-3 rounded-full bg-gray-300
                                    flex-shrink-0"></div>
                        <span class="text-sm font-medium text-gray-500">
                            No linked goal
                        </span>
                    </label>

                    {{-- Goals --}}
                    @foreach ($goals as $goal)
                        @php
                            $gc  = $goal->color ?? '#6366f1';
                            $gbg = \App\Helpers\ColorPalette::bg($gc);
                            $gt  = \App\Helpers\ColorPalette::text($gc);
                            $sel = old('goal_id', $selectedGoalId ?? '')
                                       == $goal->goal_id;
                        @endphp
                        <label class="goal-radio-label flex items-center gap-3
                                      p-3 rounded-xl border cursor-pointer
                                      transition-all"
                               style="{{ $sel
                                            ? "background:{$gbg};border-color:{$gc}"
                                            : '' }}">
                            <input type="radio" name="goal_id"
                                   value="{{ $goal->goal_id }}"
                                   class="hidden goal-radio"
                                   {{ $sel ? 'checked' : '' }}
                                   data-color="{{ $gc }}"
                                   data-bg="{{ $gbg }}"
                                   data-text="{{ $gt }}">
                            <div class="w-3 h-3 rounded-full flex-shrink-0"
                                 style="background:{{ $gc }}"></div>
                            <span class="text-sm font-semibold goal-label-text"
                                  style="{{ $sel ? "color:{$gt}" : '' }}">
                                {{ $goal->title }}
                            </span>
                        </label>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Next button --}}
        <button type="button" onclick="validateStep1()"
                class="w-full bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800
                       text-white font-semibold py-3 rounded-xl transition-all
                       flex items-center justify-center gap-2"
                id="step1-btn">
            <span>Continue</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
        </button>

    </div>
    </div>

    {{-- ===== STEP 2: WHEN ===== --}}
    <div class="step-panel hidden" data-panel="2">
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm space-y-5">

        <div>
            <p class="text-xs font-semibold text-indigo-600 uppercase
                       tracking-widest mb-1">Step 2 of 3</p>
            <h2 class="text-xl font-bold text-gray-800">When to do it?</h2>
            <p class="text-sm text-gray-400 mt-1">
                Pick a day — tasks are auto-categorized based on date.
            </p>
        </div>

        {{-- Calendar --}}
        <div>
            <div class="flex items-center justify-between mb-3">
                <label class="text-xs font-semibold text-gray-500
                              uppercase tracking-wider">
                    Scheduled day
                </label>
                <button type="button" onclick="clearDay()"
                        id="clear-day-btn"
                        class="hidden text-xs text-red-400 hover:text-red-600
                               font-medium transition-colors">
                    Clear
                </button>
            </div>

            {{-- Calendar widget --}}
            <div class="border border-gray-200 rounded-2xl overflow-hidden">

                {{-- Header --}}
                <div class="flex items-center justify-between px-4 py-3
                            bg-gray-50 border-b border-gray-100">
                    <button type="button" onclick="changeMonth(-1)"
                            class="w-8 h-8 rounded-lg hover:bg-gray-200 flex items-center
                                   justify-center text-gray-500 transition-colors
                                   text-xl font-bold leading-none">
                        ‹
                    </button>
                    <span class="text-sm font-bold text-gray-800"
                          id="cal-title"></span>
                    <button type="button" onclick="changeMonth(1)"
                            class="w-8 h-8 rounded-lg hover:bg-gray-200 flex items-center
                                   justify-center text-gray-500 transition-colors
                                   text-xl font-bold leading-none">
                        ›
                    </button>
                </div>

                {{-- Day labels --}}
                <div class="grid grid-cols-7 px-3 pt-3">
                    @foreach (['Su','Mo','Tu','We','Th','Fr','Sa'] as $d)
                        <div class="text-center text-xs text-gray-400
                                    font-semibold pb-2">
                            {{ $d }}
                        </div>
                    @endforeach
                </div>

                {{-- Grid --}}
                <div class="grid grid-cols-7 gap-y-1 px-3 pb-3"
                     id="cal-grid"></div>
            </div>

            <input type="hidden" name="scheduled_date"
                   id="scheduled-date" value="{{ old('scheduled_date') }}">

            {{-- Selected date label --}}
            <div class="mt-3 flex items-center gap-2">
                <div id="selected-badge"
                     class="hidden inline-flex items-center gap-2 px-3 py-1.5
                            rounded-full text-xs font-semibold bg-indigo-50
                            text-indigo-700 border border-indigo-200">
                    <span>📅</span>
                    <span id="selected-date-text"></span>
                </div>
                <p class="text-xs text-gray-400" id="no-date-label">
                    No day selected — task will appear in "All"
                </p>
            </div>

            {{-- Auto-category hint --}}
            <div id="category-hint" class="hidden mt-2">
                <p class="text-xs font-semibold" id="category-hint-text"></p>
            </div>
        </div>

        {{-- Scheduled time --}}
        <div>
            <label class="block text-xs font-semibold text-gray-500
                          uppercase tracking-wider mb-2">
                Time
                <span class="text-gray-300 font-normal normal-case">
                    (optional)
                </span>
            </label>
            <input type="time" name="scheduled_time"
                   id="scheduled-time"
                   value="{{ old('scheduled_time') }}"
                   class="w-full px-4 py-3 rounded-xl border border-gray-200
                          focus:outline-none focus:ring-2 focus:ring-indigo-400
                          text-sm text-gray-700 transition-all">
        </div>

        {{-- Due date --}}
        <div>
            <label class="block text-xs font-semibold text-gray-500
                          uppercase tracking-wider mb-2">
                Due date
                <span class="text-gray-300 font-normal normal-case">
                    (optional)
                </span>
            </label>
            <input type="date" name="due_date"
                   value="{{ old('due_date') }}"
                   class="w-full px-4 py-3 rounded-xl border border-gray-200
                          focus:outline-none focus:ring-2 focus:ring-indigo-400
                          text-sm text-gray-700 transition-all">
        </div>

        {{-- Repeat days --}}
        <div>
            <label class="block text-xs font-semibold text-gray-500
                          uppercase tracking-wider mb-2">
                Repeat on
                <span class="text-gray-300 font-normal normal-case">
                    (optional)
                </span>
            </label>
            <div class="flex gap-1.5" id="repeat-days-container">
                @foreach ([
                    'mon' => 'M', 'tue' => 'T', 'wed' => 'W',
                    'thu' => 'T', 'fri' => 'F', 'sat' => 'S',
                    'sun' => 'S'
                ] as $val => $letter)
                    <label class="day-chip flex-1 aspect-square flex items-center
                                  justify-center rounded-xl border cursor-pointer
                                  font-bold text-xs transition-all
                                  border-gray-200 text-gray-400
                                  hover:border-indigo-300 hover:text-indigo-500">
                        <input type="checkbox" name="repeat_days[]"
                               value="{{ $val }}" class="hidden"
                               {{ in_array($val, old('repeat_days', []))
                                   ? 'checked' : '' }}>
                        {{ $letter }}
                    </label>
                @endforeach
            </div>
            <p class="text-xs text-gray-300 mt-1.5 text-center">
                Leave empty for a one-time task
            </p>
        </div>

        <div class="flex gap-3">
            <button type="button" onclick="goToStep(1)"
                    class="flex-1 py-3 rounded-xl border border-gray-200
                           text-sm font-semibold text-gray-500
                           hover:bg-gray-50 active:bg-gray-100
                           transition-all">
                ← Back
            </button>
            <button type="button" onclick="goToStep(3)"
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700
                           active:bg-indigo-800 text-white font-semibold
                           py-3 rounded-xl transition-all">
                Continue →
            </button>
        </div>

    </div>
    </div>

    {{-- ===== STEP 3: REMIND ===== --}}
    <div class="step-panel hidden" data-panel="3">
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm space-y-5">

        <div>
            <p class="text-xs font-semibold text-indigo-600 uppercase
                       tracking-widest mb-1">Step 3 of 3</p>
            <h2 class="text-xl font-bold text-gray-800">Remind me</h2>
            <p class="text-sm text-gray-400 mt-1">
                Get an email reminder before this task.
            </p>
        </div>

        {{-- Toggle --}}
        <div class="flex items-center justify-between p-4 rounded-xl
                    bg-gray-50 border border-gray-200">
            <div>
                <p class="text-sm font-semibold text-gray-700">
                    Email reminder
                </p>
                <p class="text-xs text-gray-400 mt-0.5">
                    FocusLife → {{ auth()->user()->email }}
                </p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" id="reminder-toggle"
                       class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 rounded-full
                            peer-checked:bg-indigo-600 transition-colors
                            relative">
                    <div class="absolute left-1 top-1 w-4 h-4 bg-white
                                rounded-full shadow-sm transition-transform
                                peer-checked:translate-x-5"
                         id="toggle-thumb"></div>
                </div>
            </label>
        </div>

        {{-- Reminder fields --}}
        <div id="reminder-fields" class="space-y-4 hidden">

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-500
                                  uppercase tracking-wider mb-2">
                        Date
                    </label>
                    <input type="date" name="remind_date"
                           id="remind-date"
                           value="{{ old('remind_date') }}"
                           class="w-full px-4 py-2.5 rounded-xl border
                                  border-gray-200 focus:outline-none
                                  focus:ring-2 focus:ring-indigo-400
                                  text-sm text-gray-700 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500
                                  uppercase tracking-wider mb-2">
                        Time
                    </label>
                    <input type="time" name="remind_time"
                           id="remind-time"
                           value="{{ old('remind_time', '08:00') }}"
                           class="w-full px-4 py-2.5 rounded-xl border
                                  border-gray-200 focus:outline-none
                                  focus:ring-2 focus:ring-indigo-400
                                  text-sm text-gray-700 transition-all">
                </div>
            </div>

            {{-- Quick presets --}}
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase
                          tracking-wider mb-2">Quick presets</p>
                <div class="grid grid-cols-2 gap-2">
                    @foreach ([
                        '1h'        => ['1h before',     '⏰'],
                        '3h'        => ['3h before',     '⏰'],
                        'morning'   => ['Same day 8am',  '🌅'],
                        'daybefore' => ['Day before 8pm','🌙'],
                    ] as $key => [$label, $icon])
                        <button type="button"
                                onclick="applyPreset('{{ $key }}')"
                                data-preset="{{ $key }}"
                                class="preset-btn flex items-center gap-2
                                       px-3 py-2.5 rounded-xl border
                                       border-gray-200 text-left transition-all
                                       hover:border-indigo-300 hover:bg-indigo-50">
                            <span class="text-sm">{{ $icon }}</span>
                            <span class="text-xs font-semibold text-gray-600
                                         preset-label">
                                {{ $label }}
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- Hidden remind_at --}}
        <input type="hidden" name="remind_at" id="remind-at"
               value="{{ old('remind_at') }}">

        {{-- Summary --}}
        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 space-y-2">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Summary
            </p>
            <p class="text-base font-bold text-gray-800 truncate"
               id="sum-title">—</p>
            <div class="flex flex-wrap gap-x-3 gap-y-1">
                <span class="text-xs text-gray-500" id="sum-priority"></span>
                <span class="text-xs text-gray-500" id="sum-date"></span>
                <span class="text-xs text-gray-500" id="sum-time"></span>
                <span class="text-xs text-indigo-600 font-medium"
                      id="sum-reminder"></span>
                <span class="text-xs text-gray-500" id="sum-goal"></span>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="button" onclick="goToStep(2)"
                    class="flex-1 py-3 rounded-xl border border-gray-200
                           text-sm font-semibold text-gray-500
                           hover:bg-gray-50 transition-all">
                ← Back
            </button>
            <button type="submit" id="submit-btn"
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700
                           active:bg-indigo-800 text-white font-bold py-3
                           rounded-xl transition-all flex items-center
                           justify-center gap-2">
                <span id="submit-text">Create Task</span>
                <span id="submit-spinner"
                      class="hidden w-4 h-4 border-2 border-white
                             border-t-transparent rounded-full animate-spin">
                </span>
                <svg id="submit-icon" class="w-4 h-4" fill="none"
                     stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </button>
        </div>

    </div>
    </div>

    </form>
</div>

<script>
// ===== STATE =====
let currentDate  = new Date();
let selectedDate = '{{ old('scheduled_date') }}' || null;

function getGoalColor() {
    const radio = document.querySelector('.goal-radio:checked');
    return radio?.dataset.color || '#6366f1';
}

// ===== CALENDAR =====
function renderCalendar() {
    const year    = currentDate.getFullYear();
    const month   = currentDate.getMonth();
    const today   = new Date();
    const todayStr= today.toISOString().split('T')[0];

    document.getElementById('cal-title').textContent =
        new Date(year, month, 1).toLocaleDateString('en', {
            month: 'long', year: 'numeric'
        });

    const grid     = document.getElementById('cal-grid');
    grid.innerHTML = '';

    const firstDay = new Date(year, month, 1).getDay();
    const lastDay  = new Date(year, month + 1, 0).getDate();
    const goalColor = getGoalColor();

    for (let i = 0; i < firstDay; i++) {
        grid.innerHTML += `<div></div>`;
    }

    for (let d = 1; d <= lastDay; d++) {
        const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        const isToday    = dateStr === todayStr;
        const isSelected = dateStr === selectedDate;
        const isPast     = dateStr < todayStr;

        let style = '';
        let cls   = 'aspect-square flex items-center justify-center text-xs '
                  + 'font-semibold rounded-xl transition-all ';

        if (isSelected) {
            style = `background:${goalColor};color:white;`;
            cls  += 'font-bold shadow-sm';
        } else if (isToday) {
            style = `border:2px solid ${goalColor};color:${goalColor};`;
            cls  += 'cursor-pointer hover:opacity-80';
        } else if (isPast) {
            style = 'color:#d1d5db;cursor:not-allowed;';
        } else {
            cls  += 'cursor-pointer text-gray-700 hover:bg-indigo-50 '
                  + 'hover:text-indigo-600';
        }

        grid.innerHTML += `
            <div class="${cls}" style="${style}"
                 onclick="${isPast ? '' : `selectDay('${dateStr}')`}"
                 title="${dateStr}">
                ${d}
            </div>`;
    }
}

function selectDay(dateStr) {
    selectedDate = dateStr;
    document.getElementById('scheduled-date').value = dateStr;

    const d = new Date(dateStr + 'T00:00:00');
    const formatted = d.toLocaleDateString('en', {
        weekday: 'short', month: 'short',
        day: 'numeric', year: 'numeric'
    });

    document.getElementById('selected-date-text').textContent = formatted;
    document.getElementById('selected-badge').classList.remove('hidden');
    document.getElementById('no-date-label').classList.add('hidden');
    document.getElementById('clear-day-btn').classList.remove('hidden');

    // Category hint
    const todayStr = new Date().toISOString().split('T')[0];
    const hint     = document.getElementById('category-hint');
    const hintText = document.getElementById('category-hint-text');
    hint.classList.remove('hidden');

    const taskDate = new Date(dateStr + 'T00:00:00');
    const todayD   = new Date(todayStr + 'T00:00:00');

    // Get week boundaries
    const weekStart = new Date(todayD);
    weekStart.setDate(todayD.getDate() - todayD.getDay() + 1);
    const weekEnd = new Date(weekStart);
    weekEnd.setDate(weekStart.getDate() + 6);

    if (dateStr === todayStr) {
        hintText.textContent = '☀️ This task will appear in Today';
        hintText.style.color = '#6366f1';
    } else if (taskDate >= weekStart && taskDate <= weekEnd) {
        hintText.textContent = '📅 This task will appear in This Week';
        hintText.style.color = '#d97706';
    } else if (taskDate > todayD) {
        hintText.textContent = '📆 This task will appear in All (future)';
        hintText.style.color = '#6b7280';
    }

    renderCalendar();
    updateSummary();
}

function clearDay() {
    selectedDate = null;
    document.getElementById('scheduled-date').value = '';
    document.getElementById('selected-badge').classList.add('hidden');
    document.getElementById('no-date-label').classList.remove('hidden');
    document.getElementById('clear-day-btn').classList.add('hidden');
    document.getElementById('category-hint').classList.add('hidden');
    renderCalendar();
    updateSummary();
}

function changeMonth(dir) {
    currentDate.setMonth(currentDate.getMonth() + dir);
    renderCalendar();
}

renderCalendar();

// ===== STEPS =====
function goToStep(n) {
    document.querySelectorAll('.step-panel').forEach(p => {
        p.classList.add('hidden');
    });
    document.querySelector(`[data-panel="${n}"]`).classList.remove('hidden');

    document.querySelectorAll('.step-dot').forEach(dot => {
        const s = parseInt(dot.dataset.step);
        dot.className = dot.className
            .replace(/bg-\S+/g, '').replace(/text-\S+/g, '');
        if (s < n) {
            dot.classList.add('bg-indigo-100', 'text-indigo-600');
            dot.innerHTML = '✓';
        } else if (s === n) {
            dot.classList.add('bg-indigo-600', 'text-white');
            dot.innerHTML = s;
        } else {
            dot.classList.add('bg-gray-100', 'text-gray-400');
            dot.innerHTML = s;
        }
    });

    document.querySelectorAll('[data-step-label]').forEach(el => {
        const s = parseInt(el.dataset.stepLabel);
        el.className = el.className.replace(/text-\S+/g, '');
        el.classList.add(s <= n ? 'text-gray-800' : 'text-gray-400');
    });

    if (n === 3) updateSummary();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ===== VALIDATION STEP 1 =====
function validateStep1() {
    let valid = true;

    const title = document.getElementById('task-title')?.value.trim();
    const titleError = document.getElementById('title-error');
    if (!title || title.length < 2) {
        titleError.classList.remove('hidden');
        document.getElementById('task-title').classList.add(
            'border-red-400', 'bg-red-50'
        );
        valid = false;
    } else {
        titleError.classList.add('hidden');
        document.getElementById('task-title').classList.remove(
            'border-red-400', 'bg-red-50'
        );
    }

    const priority = document.querySelector('[name="priority"]:checked');
    const priorityError = document.getElementById('priority-error');
    if (!priority) {
        priorityError.classList.remove('hidden');
        valid = false;
    } else {
        priorityError.classList.add('hidden');
    }

    if (valid) goToStep(2);
}

// Clear title error on input
document.getElementById('task-title')?.addEventListener('input', function() {
    if (this.value.trim().length >= 2) {
        document.getElementById('title-error').classList.add('hidden');
        this.classList.remove('border-red-400', 'bg-red-50');
    }
});

// ===== PRIORITY CHIPS =====
document.querySelectorAll('.priority-chip').forEach(label => {
    const input = label.querySelector('input');
    function apply() {
        document.querySelectorAll('.priority-chip').forEach(l => {
            l.className = l.className
                .replace(/border-\S+/g, '')
                .replace(/bg-\S+/g, '')
                .replace(/text-\S+/g, '');
            l.classList.add(
                'border-gray-200', 'text-gray-400', 'hover:border-gray-300'
            );
        });
        label.dataset.active.split(' ').forEach(c => label.classList.add(c));
        label.classList.remove(
            'border-gray-200', 'text-gray-400', 'hover:border-gray-300'
        );
        document.getElementById('priority-error')?.classList.add('hidden');
    }
    if (input.checked) apply();
    label.addEventListener('click', apply);
});

// ===== GOAL RADIO =====
document.querySelectorAll('.goal-radio').forEach(input => {
    function apply() {
        document.querySelectorAll('.goal-radio-label').forEach(l => {
            l.style.background  = '';
            l.style.borderColor = '';
            const span = l.querySelector('.goal-label-text');
            if (span) span.style.color = '';
            // Reset to default border
            l.classList.remove('border-indigo-300', 'bg-indigo-50');
            l.classList.add('border-gray-200');
        });

        const label = input.closest('label');
        if (!label) return;

        if (input.value && input.dataset.bg) {
            label.style.background  = input.dataset.bg;
            label.style.borderColor = input.dataset.color;
            label.classList.remove('border-gray-200');
            const span = label.querySelector('.goal-label-text');
            if (span) span.style.color = input.dataset.text;
        } else {
            // No goal selected
            label.classList.remove('border-gray-200');
            label.classList.add('border-indigo-300', 'bg-indigo-50');
        }

        // Re-render calendar with goal color
        renderCalendar();
        // Re-apply day chip colors
        applyDayChipColors();
    }

    if (input.checked) apply();
    input.addEventListener('change', apply);
});

// ===== DAY CHIPS =====
function applyDayChipColors() {
    const color = getGoalColor();
    document.querySelectorAll('.day-chip').forEach(label => {
        const input = label.querySelector('input');
        if (input.checked) {
            label.style.background  = color;
            label.style.color       = 'white';
            label.style.borderColor = color;
        } else {
            label.style.background  = '';
            label.style.color       = '';
            label.style.borderColor = '';
        }
    });
}

document.querySelectorAll('.day-chip').forEach(label => {
    const input = label.querySelector('input');
    if (input.checked) applyDayChipColors();
    label.addEventListener('click', () => setTimeout(applyDayChipColors, 10));
});

// ===== REMINDER TOGGLE =====
const reminderToggle = document.getElementById('reminder-toggle');
reminderToggle?.addEventListener('change', function() {
    const fields = document.getElementById('reminder-fields');
    fields.classList.toggle('hidden', !this.checked);
    if (!this.checked) {
        document.getElementById('remind-at').value = '';
    }
    updateSummary();
});

// ===== BUILD remind_at =====
function buildRemindAt() {
    const d = document.getElementById('remind-date')?.value;
    const t = document.getElementById('remind-time')?.value || '08:00';
    document.getElementById('remind-at').value =
        d ? `${d} ${t}:00` : '';
    updateSummary();
}

document.getElementById('remind-date')?.addEventListener('change', buildRemindAt);
document.getElementById('remind-time')?.addEventListener('change', buildRemindAt);

// ===== QUICK PRESETS =====
function applyPreset(key) {
    // Highlight active preset
    document.querySelectorAll('.preset-btn').forEach(b => {
        b.classList.remove('border-indigo-500', 'bg-indigo-50');
        b.querySelector('.preset-label')?.classList.remove('text-indigo-700');
        b.querySelector('.preset-label')?.classList.add('text-gray-600');
    });

    const activeBtn = document.querySelector(`[data-preset="${key}"]`);
    if (activeBtn) {
        activeBtn.classList.add('border-indigo-500', 'bg-indigo-50');
        const label = activeBtn.querySelector('.preset-label');
        label?.classList.remove('text-gray-600');
        label?.classList.add('text-indigo-700');
    }

    // Get scheduled date and time
    const sDate = document.getElementById('scheduled-date')?.value
        || new Date().toISOString().split('T')[0];
    const sTime = document.getElementById('scheduled-time')?.value
        || '09:00';

    let remindDate = '';
    let remindTime = '';

    if (key === '1h') {
        const dt = new Date(`${sDate}T${sTime}:00`);
        if (!isNaN(dt.getTime())) {
            dt.setHours(dt.getHours() - 1);
            remindDate = dt.toISOString().split('T')[0];
            remindTime = dt.toTimeString().slice(0, 5);
        } else {
            // Fallback: same day 8am
            remindDate = sDate;
            remindTime = '08:00';
        }
    } else if (key === '3h') {
        const dt = new Date(`${sDate}T${sTime}:00`);
        if (!isNaN(dt.getTime())) {
            dt.setHours(dt.getHours() - 3);
            remindDate = dt.toISOString().split('T')[0];
            remindTime = dt.toTimeString().slice(0, 5);
        } else {
            remindDate = sDate;
            remindTime = '06:00';
        }
    } else if (key === 'morning') {
        remindDate = sDate;
        remindTime = '08:00';
    } else if (key === 'daybefore') {
        const dt = new Date(`${sDate}T00:00:00`);
        dt.setDate(dt.getDate() - 1);
        remindDate = dt.toISOString().split('T')[0];
        remindTime = '20:00';
    }

    if (remindDate) {
        document.getElementById('remind-date').value = remindDate;
        document.getElementById('remind-time').value = remindTime;
        document.getElementById('remind-at').value =
            `${remindDate} ${remindTime}:00`;

        // Auto-enable toggle
        const toggle = document.getElementById('reminder-toggle');
        if (toggle && !toggle.checked) {
            toggle.checked = true;
            document.getElementById('reminder-fields')
                .classList.remove('hidden');
        }

        updateSummary();
    }
}

// ===== SUMMARY =====
function updateSummary() {
    const title    = document.getElementById('task-title')?.value || '—';
    const priority = document.querySelector('[name="priority"]:checked')
        ?.value || '';
    const date     = document.getElementById('scheduled-date')?.value || '';
    const time     = document.getElementById('scheduled-time')?.value || '';
    const reminded = document.getElementById('reminder-toggle')?.checked;
    const remDate  = document.getElementById('remind-date')?.value || '';
    const remTime  = document.getElementById('remind-time')?.value || '';

    const goalRadio = document.querySelector('.goal-radio:checked');
    const goalName  = goalRadio?.value
        ? goalRadio.closest('label')
            ?.querySelector('.goal-label-text')
            ?.textContent?.trim()
        : '';

    document.getElementById('sum-title').textContent =
        title.length > 40 ? title.slice(0, 40) + '…' : title;

    document.getElementById('sum-priority').textContent =
        priority ? `🎯 ${priority} priority` : '';

    document.getElementById('sum-date').textContent =
        date ? `📅 ${new Date(date + 'T00:00:00')
            .toLocaleDateString('en', { month: 'short', day: 'numeric' })}` : '';

    document.getElementById('sum-time').textContent =
        time ? `⏰ ${time}` : '';

    document.getElementById('sum-reminder').textContent =
        (reminded && remDate)
            ? `🔔 ${remDate} ${remTime}`
            : '';

    document.getElementById('sum-goal').textContent =
        goalName ? `🎯 ${goalName}` : '';
}

// ===== SUBMIT LOADING =====
document.getElementById('task-form')?.addEventListener('submit', function() {
    const btn     = document.getElementById('submit-btn');
    const text    = document.getElementById('submit-text');
    const spinner = document.getElementById('submit-spinner');
    const icon    = document.getElementById('submit-icon');

    btn.disabled    = true;
    btn.classList.add('opacity-80');
    text.textContent = 'Creating...';
    spinner.classList.remove('hidden');
    icon.classList.add('hidden');
});

// Live summary updates
document.getElementById('task-title')
    ?.addEventListener('input', updateSummary);
document.getElementById('scheduled-time')
    ?.addEventListener('change', updateSummary);

// Init
updateSummary();
</script>

<style>
.swal2-popup { border-radius: 20px !important; }
</style>

</x-layouts.app>