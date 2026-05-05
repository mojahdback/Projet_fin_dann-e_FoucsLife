<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FocusLife — {{ $title ?? 'Dashboard' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">

    <div class="flex min-h-screen">

        {{-- ===== SIDEBAR ===== --}}
        <aside class="w-64 bg-white border-r border-gray-100 flex flex-col fixed h-full">

            {{-- Logo --}}
            <div class="px-6 py-5 border-b border-gray-100">
                <h1 class="text-xl font-bold text-indigo-600">FocusLife</h1>
                <p class="text-xs text-gray-400 mt-0.5">Stay focused, grow daily</p>
            </div>

            {{-- Nav links --}}
            <nav class="flex-1 px-4 py-6 space-y-1">

                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm
                          transition-colors
                          {{ request()->routeIs('dashboard')
                              ? 'bg-indigo-50 text-indigo-600 font-medium'
                              : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1
                                 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1
                                 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('goals.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm
                          transition-colors
                          {{ request()->routeIs('goals.*')
                              ? 'bg-indigo-50 text-indigo-600 font-medium'
                              : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0
                                 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2
                                 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2
                                 2 0 01-2-2z"/>
                    </svg>
                    Goals
                </a>

                <a href="{{ route('tasks.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm
                          transition-colors
                          {{ request()->routeIs('tasks.*')
                              ? 'bg-indigo-50 text-indigo-600 font-medium'
                              : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0
                                 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2
                                 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Tasks
                </a>

                <a href="{{ route('habits.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm
                          transition-colors
                          {{ request()->routeIs('habits.*')
                              ? 'bg-indigo-50 text-indigo-600 font-medium'
                              : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581
                                 m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Habits
                </a>

                <a href="{{ route('evaluations.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm
                          transition-colors
                          {{ request()->routeIs('evaluations.*')
                              ? 'bg-indigo-50 text-indigo-600 font-medium'
                              : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                    Evaluations
                </a>

            </nav>

            {{-- User info + Logout --}}
            <div class="px-4 py-4 border-t border-gray-100">
                <div class="flex items-center gap-3 px-3 py-2 rounded-lg bg-gray-50 mb-2">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center
                                justify-center text-indigo-600 font-semibold text-sm">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-700 truncate">
                            {{ auth()->user()->name ?? 'User' }}
                        </p>
                        <p class="text-xs text-gray-400 truncate">
                            {{ auth()->user()->email ?? '' }}
                        </p>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg
                                   text-sm text-red-500 hover:bg-red-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0
                                     00-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>

        </aside>

        {{-- ===== MAIN CONTENT ===== --}}
        <main class="flex-1 ml-64">

            {{-- ===== TOP BAR ===== --}}
            <div class="flex items-center justify-between px-8 py-4
                        border-b border-gray-100 bg-white sticky top-0 z-40">

                {{-- Page title (dynamic) --}}
                <p class="text-sm font-semibold text-gray-500">
                    {{ now()->format('l, F j Y') }}
                </p>

                {{-- Right side: bell + admin badge --}}
                <div class="flex items-center gap-3">

                    {{-- Admin badge --}}
                    @if (auth()->user()?->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           class="text-xs font-bold px-3 py-1.5 rounded-xl
                                  bg-red-50 text-red-600 border border-red-100
                                  hover:bg-red-100 transition-colors">
                            Admin Panel →
                        </a>
                    @endif

                    {{-- ===== NOTIFICATION BELL ===== --}}
                    @php
                        $bellCount = session('auth_user_id')
                            ? app(\App\Services\NotificationService::class)
                                ->countUnread(session('auth_user_id'))
                            : 0;

                        $recentNotifs = session('auth_user_id')
                            ? app(\App\Services\NotificationService::class)
                                ->getUnread(session('auth_user_id'))
                                ->take(5)
                            : collect();
                    @endphp

                    <div class="relative" id="notif-wrapper">

                        {{-- Bell button --}}
                        <button onclick="toggleNotifDropdown()"
                                class="relative p-2 text-gray-400 hover:text-gray-600
                                       transition-colors rounded-xl hover:bg-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118
                                         14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0
                                         10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159
                                         c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3
                                         3 0 11-6 0v-1m6 0H9"/>
                            </svg>

                            @if ($bellCount > 0)
                                <span class="absolute -top-0.5 -right-0.5 w-4 h-4
                                             bg-red-500 text-white text-xs rounded-full
                                             flex items-center justify-center font-bold
                                             animate-pulse">
                                    {{ $bellCount > 9 ? '9+' : $bellCount }}
                                </span>
                            @endif
                        </button>

                        {{-- Dropdown --}}
                        <div id="notif-dropdown"
                             class="hidden absolute right-0 top-full mt-2 w-80
                                    bg-white rounded-2xl shadow-xl border border-gray-100
                                    z-50 overflow-hidden">

                            {{-- Dropdown header --}}
                            <div class="flex items-center justify-between
                                        px-4 py-3 border-b border-gray-50">
                                <p class="text-sm font-bold text-gray-800">
                                    Notifications
                                </p>
                                @if ($bellCount > 0)
                                    <span class="text-xs bg-red-100 text-red-600
                                                 font-bold px-2 py-0.5 rounded-full">
                                        {{ $bellCount }} new
                                    </span>
                                @endif
                            </div>

                            {{-- Notification items --}}
                            @forelse ($recentNotifs as $notif)
                                @php
                                    $icon = match($notif->type) {
                                        'task_reminder'       => '📋',
                                        'habit_alert'         => '🔁',
                                        'goal_deadline'       => '🎯',
                                        'evaluation_reminder' => '📊',
                                        default               => '🔔',
                                    };
                                    $notifLink = match($notif->related_type ?? '') {
                                        'task'  => $notif->related_id ? route('tasks.show',  $notif->related_id) : '#',
                                        'habit' => $notif->related_id ? route('habits.show', $notif->related_id) : '#',
                                        'goal'  => $notif->related_id ? route('goals.show',  $notif->related_id) : '#',
                                        default => route('notifications.index'),
                                    };
                                @endphp

                                <a href="{{ $notifLink }}"
                                   class="flex items-start gap-3 px-4 py-3
                                          hover:bg-gray-50 transition-colors
                                          border-b border-gray-50 last:border-0 block">
                                    <span class="text-base flex-shrink-0 mt-0.5">
                                        {{ $icon }}
                                    </span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-semibold text-gray-800
                                                   leading-relaxed line-clamp-2">
                                            {{ $notif->message }}
                                        </p>
                                        <p class="text-xs text-gray-300 mt-0.5">
                                            {{ $notif->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div class="w-1.5 h-1.5 rounded-full bg-indigo-500
                                                flex-shrink-0 mt-1.5"></div>
                                </a>

                            @empty
                                <div class="px-4 py-8 text-center">
                                    <p class="text-2xl mb-2">🔔</p>
                                    <p class="text-sm text-gray-400">
                                        No new notifications
                                    </p>
                                </div>
                            @endforelse

                            {{-- Footer --}}
                            <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                                <a href="{{ route('notifications.index') }}"
                                   class="block text-center text-xs font-bold
                                          text-indigo-600 hover:text-indigo-700
                                          transition-colors">
                                    View all notifications →
                                </a>
                            </div>

                        </div>
                    </div>
                    {{-- ===== END NOTIFICATION BELL ===== --}}

                </div>
            </div>

            {{-- ===== PAGE CONTENT ===== --}}
            <div class="p-8">

                {{-- Flash messages --}}
                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700
                                text-sm rounded-2xl p-4 mb-6 flex items-center gap-3">
                        <span class="text-green-500 text-base">✓</span>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700
                                text-sm rounded-2xl p-4 mb-6 flex items-center gap-3">
                        <span class="text-red-500 text-base">✕</span>
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}

            </div>
        </main>

    </div>

    {{-- ===== NOTIFICATION DROPDOWN SCRIPT ===== --}}
    <script>
    function toggleNotifDropdown() {
        const d = document.getElementById('notif-dropdown');
        d.classList.toggle('hidden');
    }

    // Close when clicking outside
    document.addEventListener('click', function(e) {
        const wrapper = document.getElementById('notif-wrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            document.getElementById('notif-dropdown')
                ?.classList.add('hidden');
        }
    });
    </script>

</body>
</html>