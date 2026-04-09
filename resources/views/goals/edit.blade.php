<x-layouts.app title="Edit Goal">

    <div class="max-w-2xl mx-auto">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('goals.index') }}"
               class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="text-2xl font-semibold text-gray-800">Edit Goal</h1>
        </div>

        {{-- Errors --}}
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

        <form action="{{ route('goals.update', $goal->goal_id) }}" method="POST"
              class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm space-y-5">
            @csrf @method('PUT')

            {{-- Title --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input type="text" name="title" value="{{ old('title', $goal->title) }}"
                       placeholder="Goal title"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200
                              focus:outline-none focus:ring-2 focus:ring-indigo-400
                              text-sm @error('title') border-red-400 @enderror">
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Description <span class="text-gray-400">(optional)</span>
                </label>
                <textarea name="description" rows="3" placeholder="Describe your goal..."
                          class="w-full px-4 py-2.5 rounded-lg border border-gray-200
                                 focus:outline-none focus:ring-2 focus:ring-indigo-400
                                 text-sm resize-none">{{ old('description', $goal->description) }}</textarea>
            </div>

            {{-- Type + Priority --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200
                                   focus:outline-none focus:ring-2 focus:ring-indigo-400
                                   text-sm @error('type') border-red-400 @enderror">
                        <option value="short_term" {{ old('type', $goal->type) === 'short_term' ? 'selected' : '' }}>Short term</option>
                        <option value="mid_term"   {{ old('type', $goal->type) === 'mid_term'   ? 'selected' : '' }}>Mid term</option>
                        <option value="long_term"  {{ old('type', $goal->type) === 'long_term'  ? 'selected' : '' }}>Long term</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                    <select name="priority"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200
                                   focus:outline-none focus:ring-2 focus:ring-indigo-400
                                   text-sm @error('priority') border-red-400 @enderror">
                        <option value="low"    {{ old('priority', $goal->priority) === 'low'    ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $goal->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high"   {{ old('priority', $goal->priority) === 'high'   ? 'selected' : '' }}>High</option>
                    </select>
                </div>
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200
                               focus:outline-none focus:ring-2 focus:ring-indigo-400
                               text-sm @error('status') border-red-400 @enderror">
                    <option value="active"    {{ old('status', $goal->status) === 'active'    ? 'selected' : '' }}>Active</option>
                    <option value="paused"    {{ old('status', $goal->status) === 'paused'    ? 'selected' : '' }}>Paused</option>
                    <option value="done"      {{ old('status', $goal->status) === 'done'      ? 'selected' : '' }}>Done</option>
                    <option value="cancelled" {{ old('status', $goal->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            {{-- Dates --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start date</label>
                    <input type="date" name="start_date"
                           value="{{ old('start_date', $goal->start_date?->format('Y-m-d')) }}"
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-200
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End date</label>
                    <input type="date" name="end_date"
                           value="{{ old('end_date', $goal->end_date?->format('Y-m-d')) }}"
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-200
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400 text-sm">
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white
                               font-medium px-6 py-2.5 rounded-lg text-sm transition-colors">
                    Save Changes
                </button>
                <a href="{{ route('goals.show', $goal->goal_id) }}"
                   class="text-gray-500 hover:text-gray-700 px-4 py-2.5 text-sm">
                    Cancel
                </a>
            </div>

        </form>
    </div>

</x-layouts.app>