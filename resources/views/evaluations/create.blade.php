{{-- resources/views/evaluations/create.blade.php --}}
<x-layouts.app title="Create Evaluation">

    <div class="max-w-2xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('evaluations.index') }}"
                class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Evaluations
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900">Create Self Evaluation</h1>
                <p class="text-gray-600 mt-1">Rate your progress and reflect on your growth</p>
            </div>

            <form action="{{ route('evaluations.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                {{-- Period Type --}}
                <div>
                    <label for="period_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Evaluation Period <span class="text-red-500">*</span>
                    </label>
                    <select id="period_type" name="period_type" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select a period</option>
                        <option value="day">Daily</option>
                        <option value="week">Weekly</option>
                        <option value="month">Monthly</option>
                        <option value="year">Yearly</option>
                    </select>
                    @error('period_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Period Date --}}
                <div>
                    <label for="period_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="period_date" name="period_date" required
                        value="{{ old('period_date', now()->format('Y-m-d')) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('period_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Score --}}
                <div>
                    <label for="score" class="block text-sm font-medium text-gray-700 mb-2">
                        Score (1-10) <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-3">
                        <input type="range" id="score" name="score" min="1" max="10" value="5" required
                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-indigo-600"
                            oninput="updateScoreDisplay(this.value)">

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span id="scoreDisplay" class="text-3xl font-bold text-indigo-600">5</span>
                                <span class="text-gray-500">/10</span>
                            </div>
                            <div id="scoreLabel"
                                class="text-sm font-medium px-3 py-1 rounded-full bg-yellow-100 text-yellow-800">
                                Average
                            </div>
                        </div>

                        <div class="flex justify-between text-xs text-gray-500">
                            <span>1 - Poor</span>
                            <span>5 - Average</span>
                            <span>10 - Excellent</span>
                        </div>
                    </div>
                    @error('score')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Comment --}}
                <div>
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                        Reflection Notes
                    </label>
                    <textarea id="comment" name="comment" rows="4"
                        placeholder="What went well? What could be improved? What did you learn?"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('comment') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">
                        Share your thoughts, achievements, and areas for improvement (optional)
                    </p>
                    @error('comment')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Buttons --}}
                <div class="flex items-center gap-4 pt-4">
                    <button type="submit"
                        class="flex-1 bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                        Create Evaluation
                    </button>
                    <a href="{{ route('evaluations.index') }}"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>

    <script>
        function updateScoreDisplay(value) {
            const scoreDisplay = document.getElementById('scoreDisplay');
            const scoreLabel = document.getElementById('scoreLabel');

            scoreDisplay.textContent = value;

            // Update label based on score
            let label, labelClass;
            if (value <= 3) {
                label = 'Needs Improvement';
                labelClass = 'bg-red-100 text-red-800';
            } else if (value <= 6) {
                label = 'Average';
                labelClass = 'bg-yellow-100 text-yellow-800';
            } else if (value <= 8) {
                label = 'Good';
                labelClass = 'bg-green-100 text-green-800';
            } else {
                label = 'Excellent';
                labelClass = 'bg-purple-100 text-purple-800';
            }

            scoreLabel.textContent = label;
            scoreLabel.className = `text-sm font-medium px-3 py-1 rounded-full ${labelClass}`;
        }

        // Initialize score display
        document.addEventListener('DOMContentLoaded', function () {
            const scoreInput = document.getElementById('score');
            updateScoreDisplay(scoreInput.value);
        });
    </script>

</x-layouts.app>