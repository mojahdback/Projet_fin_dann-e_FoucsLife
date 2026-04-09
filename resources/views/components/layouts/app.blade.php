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
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
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
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
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
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
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
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
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
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>

        </aside>

        {{-- ===== MAIN CONTENT ===== --}}
        <main class="flex-1 ml-64 p-8">

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700
                            text-sm rounded-lg p-3 mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700
                            text-sm rounded-lg p-3 mb-6">
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}

        </main>

    </div>

</body>
</html>