{{-- resources/views/habits/create.blade.php --}}
<x-layouts.app title="New Habit">

    <div class="max-w-2xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('habits.index') }}"
               class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="text-2xl font-semibold text-gray-800">New Habit</h1>
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

        <form action="{{ route('habits.store') }}" method="POST"
              class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm space-y-5">
            @csrf

            {{-- Name --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       placeholder="e.g. Read 30 minutes"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200
                              focus:outline-none focus:ring-2 focus:ring-indigo-400
                              text-sm @error('name') border-red-400 @enderror">
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Description <span class="text-gray-400">(optional)</span>
                </label>
                <textarea name="description" rows="3"
                          placeholder="Why is this habit important to you?"
                          class="w-full px-4 py-2.5 rounded-lg border border-gray-200
                                 focus:outline-none focus:ring-2 focus:ring-indigo-400
                                 text-sm resize-none">{{ old('description') }}</textarea>
            </div>

            {{-- Frequency --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Frequency</label>
                <div class="grid grid-cols-3 gap-3">
                    @foreach (['daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly'] as $value => $label)
                        <label class="flex items-center justify-center gap-2 px-4 py-3
                                      rounded-lg border cursor-pointer transition-colors
                                      {{ old('frequency') === $value
                                          ? 'border-indigo-500 bg-indigo-50 text-indigo-700'
                                          : 'border-gray-200 text-gray-600 hover:border-indigo-300' }}">
                            <input type="radio" name="frequency" value="{{ $value }}"
                                   class="hidden"
                                   {{ old('frequency', 'daily') === $value ? 'checked' : '' }}>
                            <span class="text-sm font-medium">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white
                               font-medium px-6 py-2.5 rounded-lg text-sm transition-colors">
                    Create Habit
                </button>
                <a href="{{ route('habits.index') }}"
                   class="text-gray-500 hover:text-gray-700 px-4 py-2.5 text-sm">
                    Cancel
                </a>
            </div>
        </form>
    </div>

</x-layouts.app>