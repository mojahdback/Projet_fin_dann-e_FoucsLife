{{-- resources/views/habits/show.blade.php --}}
<x-layouts.app title="Habit Details">

    <div class="max-w-3xl mx-auto">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('habits.index') }}"
                   class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-2xl font-semibold text-gray-800">Habit Details</h1>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('habits.edit', $habit->habit_id) }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm
                          font-medium px-4 py-2 rounded-lg transition-colors">
                    Edit
                </a>
                <form action="{{ route('habits.destroy', $habit->habit_id) }}"
                      method="POST" onsubmit="return confirm('Delete this habit?')">
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
                    {{ $habit->frequency === 'daily'   ? 'bg-blue-100 text-blue-700' :
                      ($habit->frequency === 'weekly'  ? 'bg-purple-100 text-purple-700' :
                                                         'bg-amber-100 text-amber-700') }}">
                    {{ ucfirst($habit->frequency) }}
                </span>
                <span class="text-xs font-medium px-2.5 py-1 rounded-full
                             {{ $habit->is_active
                                 ? 'bg-green-100 text-green-700'
                                 : 'bg-gray-100 text-gray-500' }}">
                    {{ $habit->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $habit->name }}</h2>

            @if ($habit->description)
                <p class="text-gray-500 text-sm leading-relaxed mb-5">
                    {{ $habit->description }}
                </p>
            @endif

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-orange-50 rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-orange-500">{{ $habit->streak }}</p>
                    <p class="text-xs text-gray-500 mt-1">Day streak 🔥</p>
                </div>
                <div class="bg-indigo-50 rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-indigo-500">{{ $habit->completion_rate }}%</p>
                    <p class="text-xs text-gray-500 mt-1">Last 30 days</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-green-500">
                        {{ $habit->trackings->where('completed', true)->count() }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Total completions</p>
                </div>
            </div>
        </div>

        {{-- Today's check-in --}}
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6 mb-5">
            <h3 class="text-base font-semibold text-gray-800 mb-4">Today's Check-in</h3>

            <form action="{{ route('habits.toggle', $habit->habit_id) }}" method="POST"
                  class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Note <span class="text-gray-400">(optional)</span>
                    </label>
                    <textarea name="note" rows="2"
                              placeholder="How did it go today?"
                              class="w-full px-4 py-2.5 rounded-lg border border-gray-200
                                     focus:outline-none focus:ring-2 focus:ring-indigo-400
                                     text-sm resize-none">{{ $habit->todayTracking?->note }}</textarea>
                </div>
                <button type="submit"
                        class="w-full py-2.5 rounded-lg text-sm font-medium transition-colors
                               {{ $habit->is_done_today
                                   ? 'bg-green-500 hover:bg-green-600 text-white'
                                   : 'bg-gray-100 hover:bg-green-500 hover:text-white text-gray-600' }}">
                    {{ $habit->is_done_today ? '✓ Done today — Click to undo' : 'Mark as done today' }}
                </button>
            </form>
        </div>

        {{-- Recent history --}}
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-4">Recent History</h3>

            @forelse ($habit->trackings->sortByDesc('date')->take(14) as $log)
                <div class="flex items-center justify-between py-3
                            border-b border-gray-50 last:border-0">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full
                            {{ $log->completed ? 'bg-green-400' : 'bg-gray-200' }}">
                        </span>
                        <span class="text-sm text-gray-700">
                            {{ $log->date->format('D, M d Y') }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3">
                        @if ($log->note)
                            <span class="text-xs text-gray-400 italic">{{ $log->note }}</span>
                        @endif
                        <span class="text-xs font-medium
                            {{ $log->completed ? 'text-green-600' : 'text-gray-400' }}">
                            {{ $log->completed ? 'Done' : 'Missed' }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400 text-center py-6">No history yet.</p>
            @endforelse
        </div>

    </div>

</x-layouts.app>