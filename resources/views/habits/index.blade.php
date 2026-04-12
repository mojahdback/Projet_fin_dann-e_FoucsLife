<x-layouts.app title="My Habits">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">My Habits</h1>
        <a href="{{ route('habits.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm
                  font-medium px-4 py-2 rounded-lg transition-colors">
            + New Habit
        </a>
    </div>

    @forelse ($habits as $habit)
        <div class="bg-white border border-gray-100 rounded-xl p-5 mb-3 shadow-sm">
            <div class="flex items-center justify-between">

                {{-- Left: info --}}
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <h2 class="text-sm font-semibold text-gray-800">
                            {{ $habit->name }}
                        </h2>

                        {{-- Frequency badge --}}
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full
                            {{ $habit->frequency === 'daily'   ? 'bg-blue-100 text-blue-700' :
                              ($habit->frequency === 'weekly'  ? 'bg-purple-100 text-purple-700' :
                                                                 'bg-amber-100 text-amber-700') }}">
                            {{ ucfirst($habit->frequency) }}
                        </span>

                        {{-- Active / Inactive --}}
                        @if (!$habit->is_active)
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full
                                         bg-gray-100 text-gray-500">
                                Inactive
                            </span>
                        @endif
                    </div>

                    @if ($habit->description)
                        <p class="text-xs text-gray-400 mb-2">{{ $habit->description }}</p>
                    @endif

                    {{-- Streak + Completion rate --}}
                    <div class="flex items-center gap-4">
                        <span class="text-xs text-gray-500">
                            🔥 <span class="font-medium text-orange-500">
                                {{ $habit->streak }}
                            </span> day streak
                        </span>
                        <span class="text-xs text-gray-500">
                            📊 <span class="font-medium text-indigo-500">
                                {{ $habit->completion_rate }}%
                            </span> last 30 days
                        </span>
                    </div>
                </div>

                {{-- Right: toggle + actions --}}
                <div class="flex items-center gap-3 ml-4">

                    {{-- Toggle button --}}
                    <form action="{{ route('habits.toggle', $habit->habit_id) }}"
                          method="POST">
                        @csrf
                        <button type="submit"
                                class="w-9 h-9 rounded-full border-2 flex items-center
                                       justify-center transition-colors
                                       {{ $habit->is_done_today
                                           ? 'bg-green-500 border-green-500 text-white'
                                           : 'border-gray-200 text-gray-300 hover:border-green-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                        </button>
                    </form>

                    <a href="{{ route('habits.show', $habit->habit_id) }}"
                       class="text-xs text-indigo-600 hover:underline">View</a>
                    <a href="{{ route('habits.edit', $habit->habit_id) }}"
                       class="text-xs text-gray-500 hover:underline">Edit</a>
                    <form action="{{ route('habits.destroy', $habit->habit_id) }}"
                          method="POST" onsubmit="return confirm('Delete this habit?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="text-xs text-red-500 hover:underline">
                            Delete
                        </button>
                    </form>
                </div>

            </div>
        </div>
    @empty
        <div class="text-center py-16 text-gray-400">
            <p class="text-lg">No habits yet.</p>
            <a href="{{ route('habits.create') }}"
               class="text-indigo-600 text-sm hover:underline mt-2 inline-block">
                Create your first habit
            </a>
        </div>
    @endforelse

</x-layouts.app>