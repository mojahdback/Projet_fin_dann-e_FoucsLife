{{-- resources/views/evaluations/show.blade.php --}}
<x-layouts.app title="Evaluation Details">

    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('evaluations.index') }}"
                    class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Evaluations
                </a>
                <div class="h-6 w-px bg-gray-300"></div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                       {{ $evaluation->period_type === 'day' ? 'bg-blue-100 text-blue-800' : '' }}
                       {{ $evaluation->period_type === 'week' ? 'bg-green-100 text-green-800' : '' }}
                       {{ $evaluation->period_type === 'month' ? 'bg-purple-100 text-purple-800' : '' }}
                       {{ $evaluation->period_type === 'year' ? 'bg-orange-100 text-orange-800' : '' }}">
                    {{ ucfirst($evaluation->period_type) }}
                </span>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('evaluations.edit', $evaluation->evaluation_id) }}"
                    class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <button type="button" onclick="deleteEvaluation({{ $evaluation->evaluation_id }})"
                    class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-red-600 bg-white border border-red-300 rounded-lg hover:bg-red-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete
                </button>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Score Card --}}
            <div class="lg:col-span-1">
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                    <div class="text-center">
                        <div class="mb-4">
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4"
                                style="background: {{ $evaluation->score <= 3 ? '#fef2f2' : ($evaluation->score <= 6 ? '#fffbeb' : ($evaluation->score <= 8 ? '#f0fdf4' : '#faf5ff')) }}">
                                <span class="text-3xl font-bold"
                                    style="color: {{ $evaluation->score <= 3 ? '#dc2626' : ($evaluation->score <= 6 ? '#d97706' : ($evaluation->score <= 8 ? '#16a34a' : '#9333ea')) }}">
                                    {{ $evaluation->score }}
                                </span>
                            </div>
                            <div class="text-2xl font-bold text-gray-900">{{ $evaluation->score }}/10</div>
                        </div>

                        <div class="space-y-2">
                            <div class="text-sm font-medium px-3 py-1 rounded-full inline-block"
                                style="background: {{ $evaluation->score <= 3 ? '#fef2f2' : ($evaluation->score <= 6 ? '#fffbeb' : ($evaluation->score <= 8 ? '#f0fdf4' : '#faf5ff')) }};
                                        color: {{ $evaluation->score <= 3 ? '#dc2626' : ($evaluation->score <= 6 ? '#d97706' : ($evaluation->score <= 8 ? '#16a34a' : '#9333ea')) }}">
                                {{ $evaluation->score <= 3 ? 'Needs Improvement' : ($evaluation->score <= 6 ? 'Average' : ($evaluation->score <= 8 ? 'Good' : 'Excellent')) }}
                            </div>

                            <div class="text-gray-500 text-sm">
                                {{ $label }}
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="text-sm text-gray-600">
                                <div class="flex justify-between mb-2">
                                    <span>Created</span>
                                    <span class="font-medium">{{ $evaluation->created_at->format('M j, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Period</span>
                                    <span
                                        class="font-medium">{{ \Carbon\Carbon::parse($evaluation->period_date)->format('M j, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reflection Section -->
            <div class="lg:col-span-2 space-y-6">
                {{-- Reflection --}}
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Reflection</h2>

                    @if($evaluation->comment)
                        <div class="prose max-w-none">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $evaluation->comment }}</p>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <p class="text-gray-500">No reflection notes added</p>
                            <a href="{{ route('evaluations.edit', $evaluation->evaluation_id) }}"
                                class="inline-flex items-center gap-2 mt-3 text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Add Reflection
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Progress Insights --}}
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Progress Insights</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-indigo-600 mb-1">
                                {{ $evaluation->period_type === 'day' ? $averages['day'] ?? 'N/A' :
    ($evaluation->period_type === 'week' ? $averages['week'] ?? 'N/A' :
        ($evaluation->period_type === 'month' ? $averages['month'] ?? 'N/A' :
            $averages['year'] ?? 'N/A')) }}
                            </div>
                            <div class="text-sm text-gray-600">
                                {{ ucfirst($evaluation->period_type) }} Average
                            </div>
                        </div>

                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600 mb-1">
                                @php
                                    $trend = 'N/A';
                                    // Simple trend calculation - you might want to enhance this
                                    if ($evaluation->score > 7)
                                        $trend = 'Up';
                                    elseif ($evaluation->score < 4)
                                        $trend = 'Down';
                                    else
                                        $trend = 'Stable';
                                @endphp
                                {{ $trend }}
                            </div>
                            <div class="text-sm text-gray-600">Trend</div>
                        </div>
                    </div>
                </div>

                {{-- Related Actions --}}
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Next Steps</h2>

                    <div class="space-y-3">
                        @if($evaluation->score <= 4)
                            <div class="flex items-start gap-3 p-3 bg-red-50 rounded-lg">
                                <div class="w-5 h-5 text-red-600 mt-0.5">
                                    <svg class="w-full h-full" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-red-900">Focus on Improvement</h3>
                                    <p class="text-sm text-red-700 mt-1">Consider setting smaller, achievable goals and seek
                                        support if needed.</p>
                                </div>
                            </div>
                        @elseif($evaluation->score >= 8)
                            <div class="flex items-start gap-3 p-3 bg-green-50 rounded-lg">
                                <div class="w-5 h-5 text-green-600 mt-0.5">
                                    <svg class="w-full h-full" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-green-900">Great Progress!</h3>
                                    <p class="text-sm text-green-700 mt-1">Keep up the excellent work and consider setting
                                        new challenges.</p>
                                </div>
                            </div>
                        @else
                            <div class="flex items-start gap-3 p-3 bg-yellow-50 rounded-lg">
                                <div class="w-5 h-5 text-yellow-600 mt-0.5">
                                    <svg class="w-full h-full" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-yellow-900">Room for Growth</h3>
                                    <p class="text-sm text-yellow-700 mt-1">You're doing well! Identify specific areas to
                                        focus on for improvement.</p>
                                </div>
                            </div>
                        @endif

                        <div class="flex gap-3 pt-2">
                            <a href="{{ route('evaluations.create') }}"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                New Evaluation
                            </a>
                            <a href="{{ route('evaluations.index') }}"
                                class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                View All
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

<form id="delete-form-{{ $evaluation->evaluation_id }}" action="{{ route('evaluations.destroy', $evaluation->evaluation_id) }}" method="POST" class="hidden">
        @csrf @method('DELETE')
    </form>

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