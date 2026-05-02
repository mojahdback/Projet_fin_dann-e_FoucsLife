{{-- resources/views/admin/users/index.blade.php --}}
<x-layouts.admin title="Users">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Users</h1>
            <p class="text-sm text-gray-400 mt-0.5">
                {{ $users->count() }} total
            </p>
        </div>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('admin.users') }}" class="mb-6">
        <div class="relative max-w-sm">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Search by name or email..."
                   class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200
                          focus:outline-none focus:ring-2 focus:ring-indigo-400
                          text-sm text-gray-700 bg-white">
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="text-xs text-gray-400 bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-6 py-3 font-semibold">User</th>
                    <th class="text-left px-6 py-3 font-semibold">Email</th>
                    <th class="text-left px-6 py-3 font-semibold">Role</th>
                    <th class="text-left px-6 py-3 font-semibold">Goals</th>
                    <th class="text-left px-6 py-3 font-semibold">Tasks</th>
                    <th class="text-left px-6 py-3 font-semibold">Habits</th>
                    <th class="text-left px-6 py-3 font-semibold">Joined</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="border-b border-gray-50 last:border-0
                               hover:bg-gray-50 transition-colors group">

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center
                                            justify-center font-bold text-sm flex-shrink-0
                                            {{ $user->role === 'admin'
                                                ? 'bg-red-100 text-red-600'
                                                : 'bg-indigo-100 text-indigo-600' }}">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-700">
                                        {{ $user->name }}
                                    </p>
                                    @if ($user->user_id === session('auth_user_id'))
                                        <p class="text-xs text-indigo-500 font-medium">
                                            (You)
                                        </p>
                                    @endif
                                </div>
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

                        <td class="px-6 py-4 text-sm font-semibold text-gray-600">
                            {{ $user->goals->count() }}
                        </td>

                        <td class="px-6 py-4 text-sm font-semibold text-gray-600">
                            {{ $user->tasks->count() }}
                        </td>

                        <td class="px-6 py-4 text-sm font-semibold text-gray-600">
                            {{ $user->habits->count() }}
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
                @empty
                    <tr>
                        <td colspan="8"
                            class="px-6 py-16 text-center text-gray-400 text-sm">
                            @if ($search)
                                No users found for "{{ $search }}"
                            @else
                                No users yet.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-layouts.admin>