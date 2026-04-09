<x-layouts.app title="My Goals">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">My Goals</h1>
        <a href="{{ route('goals.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm
                  font-medium px-4 py-2 rounded-lg transition-colors">
            + New Goal
        </a>
    </div>

    {{-- Success message --}}
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700
                    text-sm rounded-lg p-3 mb-5">
            {{ session('success') }}
        </div>
    @endif

    {{-- Goals list --}}
    @forelse ($goals as $goal)
        <div class="bg-white border border-gray-100 rounded-xl p-5 mb-4 shadow-sm">

            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full
                        {{ $goal->type === 'short_term' ? 'bg-blue-100 text-blue-700' :
                          ($goal->type === 'mid_term'   ? 'bg-amber-100 text-amber-700' :
                                                          'bg-purple-100 text-purple-700') }}">
                        {{ ucfirst(str_replace('_', ' ', $goal->type)) }}
                    </span>
                    <h2 class="text-lg font-semibold text-gray-800 mt-2">
                        {{ $goal->title }}
                    </h2>
                    @if ($goal->description)
                        <p class="text-sm text-gray-500 mt-1">{{ $goal->description }}</p>
                    @endif
                </div>

                {{-- Priority badge --}}
                <span class="text-xs font-medium px-2 py-0.5 rounded-full
                    {{ $goal->priority === 'high'   ? 'bg-red-100 text-red-700' :
                      ($goal->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' :
                                                      'bg-gray-100 text-gray-600') }}">
                    {{ ucfirst($goal->priority) }}
                </span>
            </div>

            {{-- Progress bar --}}
            <div class="mt-4">
                <div class="flex justify-between text-xs text-gray-500 mb-1">
                    <span>Progress</span>
                    <span>{{ $goal->completion_percentage }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-indigo-500 h-2 rounded-full transition-all"
                         style="width: {{ $goal->completion_percentage }}%">
                    </div>
                </div>
            </div>

            {{-- Dates + Actions --}}
            <div class="flex items-center justify-between mt-4">
                <div class="text-xs text-gray-400">
                    @if ($goal->end_date)
                        Due: {{ $goal->end_date->format('M d, Y') }}
                        @if ($goal->is_overdue)
                            <span class="text-red-500 font-medium ml-1">Overdue</span>
                        @endif
                    @endif
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('goals.show', $goal->goal_id) }}"
                       class="text-xs text-indigo-600 hover:underline">View</a>
                    <a href="{{ route('goals.edit', $goal->goal_id) }}"
                       class="text-xs text-gray-500 hover:underline">Edit</a>
                    <form action="{{ route('goals.destroy', $goal->goal_id) }}"
                          method="POST"
                          onsubmit="return confirm('Delete this goal?')">
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
            <p class="text-lg">No goals yet.</p>
            <a href="{{ route('goals.create') }}"
               class="text-indigo-600 text-sm hover:underline mt-2 inline-block">
                Create your first goal
            </a>
        </div>
    @endforelse

</x-layouts.app>