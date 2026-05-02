{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FocusLife Admin — {{ $title ?? 'Dashboard' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="flex min-h-screen">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="w-60 bg-gray-900 flex flex-col fixed h-full z-50">

        {{-- Logo --}}
        <div class="px-6 py-5 border-b border-gray-700">
            <h1 class="text-lg font-bold text-white">FocusLife</h1>
            <span class="text-xs font-semibold text-red-400 bg-red-400/10
                         px-2 py-0.5 rounded-full">
                Admin Panel
            </span>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-5 space-y-1">

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm
                      font-medium transition-colors
                      {{ request()->routeIs('admin.dashboard')
                          ? 'bg-gray-700 text-white'
                          : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none"
                     stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2
                             2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0
                             011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('admin.users') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm
                      font-medium transition-colors
                      {{ request()->routeIs('admin.users*')
                          ? 'bg-gray-700 text-white'
                          : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none"
                     stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0
                             0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Users
                @php $totalUsers = \App\Models\User::count(); @endphp
                <span class="ml-auto text-xs bg-gray-700 text-gray-300
                             px-2 py-0.5 rounded-full">
                    {{ $totalUsers }}
                </span>
            </a>

        </nav>

        {{-- Bottom --}}
        <div class="px-3 py-4 border-t border-gray-700 space-y-1">

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm
                      font-medium text-gray-400 hover:bg-gray-800
                      hover:text-white transition-colors">
                <svg class="w-4 h-4 flex-shrink-0" fill="none"
                     stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to App
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl
                               text-sm font-medium text-red-400
                               hover:bg-red-400/10 transition-colors">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3
                                 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>

    </aside>

    {{-- ===== MAIN ===== --}}
    <main class="flex-1 ml-60">

        {{-- Top bar --}}
        <div class="flex items-center justify-between px-8 py-4 bg-white
                    border-b border-gray-200 sticky top-0 z-40">
            <p class="text-sm font-semibold text-gray-500">
                {{ now()->format('l, F j Y') }}
            </p>
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center
                            justify-center text-red-600 font-bold text-sm">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-700">
                        {{ auth()->user()->name ?? 'Admin' }}
                    </p>
                    <p class="text-xs text-red-500 font-medium">Administrator</p>
                </div>
            </div>
        </div>

        <div class="p-8">

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700
                            text-sm rounded-2xl p-4 mb-6 flex items-center gap-3">
                    <span class="text-base">✓</span>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700
                            text-sm rounded-2xl p-4 mb-6 flex items-center gap-3">
                    <span class="text-base">✕</span>
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}

        </div>
    </main>

</div>

<style>
.swal2-popup { border-radius: 20px !important; }
</style>

</body>
</html>