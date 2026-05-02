{{-- resources/views/admin/dashboard.blade.php --}}
<x-layouts.admin title="Dashboard">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Admin Dashboard</h1>
        <p class="text-sm text-gray-400 mt-0.5">
            Overview of FocusLife application
        </p>
    </div>

    {{-- ===== Stats Grid ===== --}}
    <div class="grid grid-cols-4 gap-4 mb-8">

        @php
            $cards = [
                ['label' => 'Total Users',    'value' => $stats['users'],
                 'sub' => $stats['admins'].' admins',
                 'icon' => '👥', 'color' => '#6366f1', 'bg' => '#eef2ff'],

                ['label' => 'Total Goals',    'value' => $stats['goals'],
                 'sub' => 'across all users',
                 'icon' => '🎯', 'color' => '#7c3aed', 'bg' => '#f5f3ff'],

                ['label' => 'Total Tasks',    'value' => $stats['tasks'],
                 'sub' => $stats['tasks_done'].' completed',
                 'icon' => '✅', 'color' => '#059669', 'bg' => '#ecfdf5'],

                ['label' => 'Active Habits',  'value' => $stats['habits_active'],
                 'sub' => $stats['habits'].' total',
                 'icon' => '🔁', 'color' => '#d97706', 'bg' => '#fffbeb'],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">
                        {{ $card['label'] }}
                    </p>
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg"
                         style="background: {{ $card['bg'] }}">
                        {{ $card['icon'] }}
                    </div>
                </div>
                <p class="text-3xl font-black text-gray-800">
                    {{ $card['value'] }}
                </p>
                <p class="text-xs text-gray-400 mt-1">{{ $card['sub'] }}</p>
            </div>
        @endforeach

    </div>

    {{-- ===== Users per month chart (simple bars) ===== --}}
    <div class="grid grid-cols-3 gap-6 mb-8">

        {{-- Monthly registrations --}}
        <div class="col-span-2 bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
            <h2 class="text-sm font-bold text-gray-800 mb-5">
                New users per month ({{ now()->year }})
            </h2>
            @php
                $months   = ['Jan','Feb','Mar','Apr','May','Jun',
                             'Jul','Aug','Sep','Oct','Nov','Dec'];
                $maxCount = max(array_values($stats['users_per_month']) ?: [1]);
            @endphp
            <div class="flex items-end gap-2 h-32">
                @foreach ($months as $i => $month)
                    @php
                        $count  = $stats['users_per_month'][$i + 1] ?? 0;
                        $height = $maxCount > 0
                            ? round(($count / $maxCount) * 100)
                            : 0;
                    @endphp
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <span class="text-xs font-bold text-gray-600">
                            {{ $count ?: '' }}
                        </span>
                        <div class="w-full rounded-t-lg transition-all"
                             style="height: {{ max($height, 4) }}%;
                                    background: {{ ($i + 1) === now()->month
                                        ? '#6366f1' : '#e0e7ff' }}">
                        </div>
                        <span class="text-xs text-gray-400">{{ $month }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Quick stats --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
            <h2 class="text-sm font-bold text-gray-800 mb-5">Quick Stats</h2>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-xs mb-1.5">
                        <span class="text-gray-500 font-medium">Tasks completion</span>
                        @php
                            $taskRate = $stats['tasks'] > 0
                                ? round(($stats['tasks_done'] / $stats['tasks']) * 100)
                                : 0;
                        @endphp
                        <span class="font-bold text-green-600">{{ $taskRate }}%</span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full">
                        <div class="h-2 bg-green-500 rounded-full"
                             style="width: {{ $taskRate }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-xs mb-1.5">
                        <span class="text-gray-500 font-medium">Active habits</span>
                        @php
                            $habitRate = $stats['habits'] > 0
                                ? round(($stats['habits_active'] / $stats['habits']) * 100)
                                : 0;
                        @endphp
                        <span class="font-bold text-amber-600">{{ $habitRate }}%</span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full">
                        <div class="h-2 bg-amber-500 rounded-full"
                             style="width: {{ $habitRate }}%"></div>
                    </div>
                </div>
                <div class="pt-3 border-t border-gray-50 space-y-2">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-400">Evaluations</span>
                        <span class="font-bold text-gray-700">
                            {{ $stats['evaluations'] }}
                        </span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-400">Admin accounts</span>
                        <span class="font-bold text-red-600">
                            {{ $stats['admins'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ===== Recent Users ===== --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">

        <div class="flex items-center justify-between px-6 py-4
                    border-b border-gray-50">
            <h2 class="text-sm font-bold text-gray-800">Recent Users</h2>
            <a href="{{ route('admin.users') }}"
               class="text-xs font-semibold text-indigo-600 hover:underline">
                View all →
            </a>
        </div>

        <table class="w-full">
            <thead>
                <tr class="text-xs text-gray-400 bg-gray-50
                           border-b border-gray-100">
                    <th class="text-left px-6 py-3 font-semibold">User</th>
                    <th class="text-left px-6 py-3 font-semibold">Email</th>
                    <th class="text-left px-6 py-3 font-semibold">Role</th>
                    <th class="text-left px-6 py-3 font-semibold">Joined</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stats['recent_users'] as $user)
                    <tr class="border-b border-gray-50 last:border-0
                               hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center
                                            justify-center font-bold text-sm flex-shrink-0
                                            {{ $user->role === 'admin'
                                                ? 'bg-red-100 text-red-600'
                                                : 'bg-indigo-100 text-indigo-600' }}">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-semibold text-gray-700">
                                    {{ $user->name }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full
                                {{ $user->role === 'admin'
                                    ? 'bg-red-100 text-red-700'
                                    : 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-400">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.users.show', $user->user_id) }}"
                               class="text-xs font-semibold text-indigo-600
                                      hover:underline">
                                View →
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</x-layouts.admin>