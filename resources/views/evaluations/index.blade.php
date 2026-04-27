{{-- resources/views/evaluations/index.blade.php --}}
<x-layouts.app title="Self Evaluations">

    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Self Evaluations</h1>
                <p class="text-gray-600 mt-1">Track your personal growth and progress</p>
            </div>
            <a href="{{ route('evaluations.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Evaluation
            </a>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <span class="text-lg">1d</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Daily Avg</p>
                        <p class="text-xl font-bold text-blue-600">{{ $averages['day'] ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <span class="text-lg">7d</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Weekly Avg</p>
                        <p class="text-xl font-bold text-green-600">{{ $averages['week'] ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <span class="text-lg">30d</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Monthly Avg</p>
                        <p class="text-xl font-bold text-purple-600">{{ $averages['month'] ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                        <span class="text-lg">365d</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Yearly Avg</p>
                        <p class="text-xl font-bold text-orange-600">{{ $averages['year'] ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Tabs --}}
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="flex border-b border-gray-200">
                <a href="{{ route('evaluations.index', ['type' => 'all']) }}"
                    class="px-6 py-3 text-sm font-medium border-b-2 transition-colors
                          {{ $type === 'all' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    All
                </a>
                <a href="{{ route('evaluations.index', ['type' => 'day']) }}"
                    class="px-6 py-3 text-sm font-medium border-b-2 transition-colors
                          {{ $type === 'day' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    Daily
                </a>
                <a href="{{ route('evaluations.index', ['type' => 'week']) }}"
                    class="px-6 py-3 text-sm font-medium border-b-2 transition-colors
                          {{ $type === 'week' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    Weekly
                </a>
                <a href="{{ route('evaluations.index', ['type' => 'month']) }}"
                    class="px-6 py-3 text-sm font-medium border-b-2 transition-colors
                          {{ $type === 'month' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    Monthly
                </a>
                <a href="{{ route('evaluations.index', ['type' => 'year']) }}"
                    class="px-6 py-3 text-sm font-medium border-b-2 transition-colors
                          {{ $type === 'year' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    Yearly
                </a>
            </div>

            {{-- Evaluations List --}}
            <div class="divide-y divide-gray-200">
                @if($evaluations->count() > 0)
                    @foreach($evaluations as $evaluation)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                       {{ $evaluation->period_type === 'day' ? 'bg-blue-100 text-blue-800' : '' }}
                                                                       {{ $evaluation->period_type === 'week' ? 'bg-green-100 text-green-800' : '' }}
                                                                       {{ $evaluation->period_type === 'month' ? 'bg-purple-100 text-purple-800' : '' }}
                                                                       {{ $evaluation->period_type === 'year' ? 'bg-orange-100 text-orange-800' : '' }}">
                                            {{ ucfirst($evaluation->period_type) }}
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($evaluation->period_date)->format('M j, Y') }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-4 mb-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-32 bg-gray-200 rounded-full h-2">
                                                <div class="h-2 rounded-full transition-all"
                                                    style="width: {{ $evaluation->score * 10 }}%; background-color: 
                                                                             {{ $evaluation->score <= 3 ? '#ef4444' : ($evaluation->score <= 6 ? '#f59e0b' : '#10b981') }}">
                                                </div>
                                            </div>
                                            <span class="text-lg font-bold text-gray-900">{{ $evaluation->score }}/10</span>
                                        </div>
                                    </div>

                                    @if($evaluation->comment)
                                        <p class="text-gray-600 text-sm mb-3">{{ Str::limit($evaluation->comment, 150) }}</p>
                                    @endif

                                    <div class="flex items-center gap-4">
                                        <a href="{{ route('evaluations.show', $evaluation->evaluation_id) }}"
                                            class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                            View Details
                                        </a>
                                        <a href="{{ route('evaluations.edit', $evaluation->evaluation_id) }}"
                                            class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                                            Edit
                                        </a>
                                    </div>
                                </div>

                                <button type="button" onclick="deleteEvaluation({{ $evaluation->evaluation_id }})"
                                    class="text-red-600 hover:text-red-800 p-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <form id="delete-form-{{ $evaluation->evaluation_id }}"
                            action="{{ route('evaluations.destroy', $evaluation->evaluation_id) }}" method="POST"
                            class="hidden">
                            @csrf @method('DELETE')
                        </form>
                    @endforeach
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No evaluations yet</h3>
                        <p class="text-gray-600 mb-4">Start tracking your personal growth by creating your first evaluation.
                        </p>
                        <a href="{{ route('evaluations.create') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            Create First Evaluation
                        </a>
                    </div>
                @endif
            </div>
        </div>

    </div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function deleteEvaluation(id) {
            Swal.fire({
                title: 'Delete Evaluation?',
                html: '<span style="color:#6b7280;font-size:14px">Are you sure you want to delete this evaluation? This action cannot be undone.</span>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }
    </script>
</x-layouts.app>