{{-- resources/views/admin/users/show.blade.php --}}
<x-layouts.admin title="User Details">

    <div class="max-w-2xl mx-auto">

        {{-- Back --}}
        <a href="{{ route('admin.users') }}"
           class="inline-flex items-center gap-1.5 text-sm text-gray-400
                  hover:text-gray-600 transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Users
        </a>

        {{-- ===== User card ===== --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6 mb-5">

            {{-- Avatar + info --}}
            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center
                            font-black text-2xl flex-shrink-0
                            {{ $user->role === 'admin'
                                ? 'bg-red-100 text-red-600'
                                : 'bg-indigo-100 text-indigo-600' }}">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">
                        {{ $user->name }}
                        @if ($user->user_id === session('auth_user_id'))
                            <span class="text-sm font-normal text-indigo-500 ml-1">
                                (You)
                            </span>
                        @endif
                    </h2>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $user->email }}</p>
                    <div class="flex items-center gap-2 mt-1.5">
                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-full
                            {{ $user->role === 'admin'
                                ? 'bg-red-100 text-red-700'
                                : 'bg-indigo-100 text-indigo-700' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                        <span class="text-xs text-gray-400">
                            Joined {{ $user->created_at->format('M d, Y') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-4 gap-3">
                @foreach ([
                    ['Goals',       $user->goals->count(),       '🎯', '#eef2ff', '#6366f1'],
                    ['Tasks',       $user->tasks->count(),       '✅', '#ecfdf5', '#059669'],
                    ['Habits',      $user->habits->count(),      '🔁', '#fffbeb', '#d97706'],
                    ['Evaluations', $user->evaluations->count(), '📊', '#eff6ff', '#3b82f6'],
                ] as [$label, $count, $icon, $bg, $color])
                    <div class="rounded-xl p-4 text-center"
                         style="background: {{ $bg }}">
                        <p class="text-2xl mb-0.5">{{ $icon }}</p>
                        <p class="text-xl font-black" style="color: {{ $color }}">
                            {{ $count }}
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $label }}</p>
                    </div>
                @endforeach
            </div>

        </div>

        {{-- Actions only if not self --}}
        @if ($user->user_id !== session('auth_user_id'))

            {{-- ===== Change Role ===== --}}
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 mb-4">
                <h3 class="text-sm font-bold text-gray-800 mb-4">Change Role</h3>
                <form action="{{ route('admin.users.role', $user->user_id) }}"
                      method="POST" class="flex items-center gap-3">
                    @csrf
                    <select name="role"
                            class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200
                                   focus:outline-none focus:ring-2 focus:ring-indigo-400
                                   text-sm text-gray-700">
                        <option value="user"
                                {{ $user->role === 'user' ? 'selected' : '' }}>
                            User
                        </option>
                        <option value="admin"
                                {{ $user->role === 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>
                    </select>
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white
                                   text-sm font-bold px-5 py-2.5 rounded-xl
                                   transition-colors">
                        Update Role
                    </button>
                </form>
            </div>

            {{-- ===== Send Notification ===== --}}
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 mb-4">
                <h3 class="text-sm font-bold text-gray-800 mb-1">
                    Send Notification
                </h3>
                <p class="text-xs text-gray-400 mb-4">
                    This will appear in the user's notification center.
                </p>
                <form action="{{ route('admin.users.notify', $user->user_id) }}"
                      method="POST" class="space-y-3">
                    @csrf
                    <textarea name="message" rows="3"
                              placeholder="Write a message for {{ $user->name }}..."
                              class="w-full px-4 py-3 rounded-xl border border-gray-200
                                     focus:outline-none focus:ring-2 focus:ring-indigo-400
                                     text-sm text-gray-700 resize-none
                                     @error('message') border-red-400 @enderror">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                    <button type="submit"
                            class="bg-gray-800 hover:bg-gray-900 text-white text-sm
                                   font-bold px-5 py-2.5 rounded-xl transition-colors">
                        Send Notification
                    </button>
                </form>
            </div>

            {{-- ===== Danger Zone ===== --}}
            <div class="bg-white border border-red-100 rounded-2xl shadow-sm p-5">
                <h3 class="text-sm font-bold text-red-600 mb-1">Danger Zone</h3>
                <p class="text-xs text-gray-400 mb-4">
                    Permanently delete this user and all their data
                    (goals, tasks, habits, evaluations).
                    This action cannot be undone.
                </p>
                <button type="button"
                        onclick="confirmDelete({{ $user->user_id }}, '{{ addslashes($user->name) }}')"
                        class="bg-red-500 hover:bg-red-600 text-white text-sm
                               font-bold px-5 py-2.5 rounded-xl transition-colors">
                    Delete User
                </button>
            </div>

        @else

            {{-- Self protection message --}}
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4
                        text-sm text-amber-700 flex items-center gap-3">
                <span class="text-xl">⚠️</span>
                This is your account. You cannot modify or delete it from here.
            </div>

        @endif

    </div>

    {{-- Hidden delete form --}}
    <form id="delete-user-form"
          method="POST"
          action="{{ route('admin.users.delete', $user->user_id) }}"
          class="hidden">
        @csrf @method('DELETE')
    </form>

<script>
function confirmDelete(id, name) {
    Swal.fire({
        title: 'Delete user?',
        html: `<span style="color:#6b7280;font-size:14px">
                   "<strong>${name}</strong>" and all their data will be
                   permanently deleted.<br><br>
                   This action <strong>cannot be undone</strong>.
               </span>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#e5e7eb',
        confirmButtonText: 'Yes, delete',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
    }).then(r => {
        if (r.isConfirmed) {
            document.getElementById('delete-user-form').submit();
        }
    });
}
</script>

</x-layouts.admin>