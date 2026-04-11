<x-layouts.app title="Edit Task">

    <div class="max-w-2xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('tasks.show', $task->task_id) }}"
               class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="text-2xl font-semibold text-gray-800">Edit Task</h1>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700
                        text-sm rounded-lg p-3 mb-5">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tasks.update', $task->task_id) }}" method="POST"
              class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm space-y-5">
            @csrf @method('PUT')

            {{-- Title --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input type="text" name="title" value="{{ old('title', $task->title) }}"
                       placeholder="Task title"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200
                              focus:outline-none focus:ring-2 focus:ring-indigo-400
                              text-sm @error('title') border-red-400 @enderror">
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Description <span class="text-gray-400">(optional)</span>
                </label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-2.5 rounded-lg border border-gray-200
                                 focus:outline-none focus:ring-2 focus:ring-indigo-400
                                 text-sm resize-none">{{ old('description', $task->description) }}</textarea>
            </div>

            {{-- Goal --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Linked Goal <span class="text-gray-400">(optional)</span>
                </label>
                <select name="goal_id"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200
                               focus:outline-none focus:ring-2 focus:ring-indigo-400 text-sm">
                    <option value="">No linked goal</option>
                    @foreach ($goals as $goal)
                        <option value="{{ $goal->goal_id }}"
                            {{ old('goal_id', $task->goal_id) == $goal->goal_id ? 'selected' : '' }}>
                            {{ $goal->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Priority + Period --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                    <select name="priority"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200
                                   focus:outline-none focus:ring-2 focus:ring-indigo-400 text-sm">
                        <option value="low"    {{ old('priority', $task->priority) === 'low'    ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high"   {{ old('priority', $task->priority) === 'high'   ? 'selected' : '' }}>High</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Period</label>
                    <select name="period"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200
                                   focus:outline-none focus:ring-2 focus:ring-indigo-400 text-sm">
                        <option value="day"   {{ old('period', $task->period) === 'day'   ? 'selected' : '' }}>Daily</option>
                        <option value="week"  {{ old('period', $task->period) === 'week'  ? 'selected' : '' }}>Weekly</option>
                        <option value="month" {{ old('period', $task->period) === 'month' ? 'selected' : '' }}>Monthly</option>
                        <option value="year"  {{ old('period', $task->period) === 'year'  ? 'selected' : '' }}>Yearly</option>
                    </select>
                </div>
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200
                               focus:outline-none focus:ring-2 focus:ring-indigo-400 text-sm">
                    <option value="todo"        {{ old('status', $task->status) === 'todo'        ? 'selected' : '' }}>To Do</option>
                    <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="done"        {{ old('status', $task->status) === 'done'        ? 'selected' : '' }}>Done</option>
                    <option value="cancelled"   {{ old('status', $task->status) === 'cancelled'   ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            {{-- Due date --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Due date <span class="text-gray-400">(optional)</span>
                </label>
                <input type="date" name="due_date"
                       value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200
                              focus:outline-none focus:ring-2 focus:ring-indigo-400 text-sm">
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white
                               font-medium px-6 py-2.5 rounded-lg text-sm transition-colors">
                    Save Changes
                </button>
                <a href="{{ route('tasks.show', $task->task_id) }}"
                   class="text-gray-500 hover:text-gray-700 px-4 py-2.5 text-sm">
                    Cancel
                </a>
            </div>
        </form>
    </div>

</x-layouts.app>