{{-- resources/views/notifications/index.blade.php --}}
<x-layouts.app title="Notifications">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Notifications</h1>
            @if ($unreadCount > 0)
                <p class="text-sm text-gray-400 mt-0.5">
                    <span class="font-semibold text-indigo-600">{{ $unreadCount }}</span>
                    unread
                </p>
            @else
                <p class="text-sm text-gray-400 mt-0.5">All caught up ✓</p>
            @endif
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-2">
            @if ($unreadCount > 0)
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="text-xs font-semibold px-3 py-2 rounded-xl
                                   border border-indigo-200 bg-indigo-50 text-indigo-600
                                   hover:bg-indigo-100 transition-colors">
                        ✓ Mark all read
                    </button>
                </form>
            @endif

            @if ($notifications->where('is_read', true)->count() > 0)
                <button type="button"
                        onclick="clearRead()"
                        class="text-xs font-semibold px-3 py-2 rounded-xl
                               border border-gray-200 text-gray-400
                               hover:border-red-200 hover:text-red-500
                               hover:bg-red-50 transition-colors">
                    🗑 Clear read
                </button>
            @endif
        </div>
    </div>

    {{-- Filter tabs --}}
    <div class="flex gap-2 mb-5">
        @foreach (['all' => 'All', 'unread' => 'Unread', 'read' => 'Read'] as $val => $label)
            <a href="{{ route('notifications.index', ['filter' => $val]) }}"
               class="px-4 py-1.5 rounded-full text-sm font-semibold transition-all
                      {{ request('filter', 'all') === $val
                          ? 'bg-indigo-600 text-white'
                          : 'bg-white border border-gray-200 text-gray-500
                             hover:bg-gray-50' }}">
                {{ $label }}
                @if ($val === 'unread' && $unreadCount > 0)
                    <span class="ml-1 bg-white/30 text-white text-xs
                                 px-1.5 py-0.5 rounded-full">
                        {{ $unreadCount }}
                    </span>
                @endif
            </a>
        @endforeach
    </div>

    {{-- Notifications list --}}
    @forelse ($notifications as $notification)

        @php
            $typeConfig = match($notification->type) {
                'task_reminder'       => ['icon' => '📋', 'color' => '#f59e0b', 'bg' => '#fffbeb', 'border' => '#fde68a'],
                'habit_alert'         => ['icon' => '🔁', 'color' => '#10b981', 'bg' => '#ecfdf5', 'border' => '#a7f3d0'],
                'goal_deadline'       => ['icon' => '🎯', 'color' => '#6366f1', 'bg' => '#eef2ff', 'border' => '#c7d2fe'],
                'evaluation_reminder' => ['icon' => '📊', 'color' => '#3b82f6', 'bg' => '#eff6ff', 'border' => '#bfdbfe'],
                default               => ['icon' => '🔔', 'color' => '#6b7280', 'bg' => '#f9fafb', 'border' => '#e5e7eb'],
            };
        @endphp

        <div class="group relative bg-white rounded-2xl border shadow-sm mb-3
                    transition-all hover:shadow-md
                    {{ $notification->is_read
                        ? 'border-gray-100'
                        : 'border-indigo-200' }}"
             id="notif-{{ $notification->notification_id }}">

            {{-- Unread indicator --}}
            @if (!$notification->is_read)
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-12
                            rounded-r-full bg-indigo-500"></div>
            @endif

            <div class="flex items-start gap-4 p-4 pl-5">

                {{-- Icon --}}
                <div class="w-10 h-10 rounded-xl flex items-center justify-center
                            flex-shrink-0 text-lg"
                     style="background: {{ $typeConfig['bg'] }};
                            border: 1px solid {{ $typeConfig['border'] }}">
                    {{ $typeConfig['icon'] }}
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">

                    {{-- Type badge --}}
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full"
                              style="background: {{ $typeConfig['bg'] }};
                                     color: {{ $typeConfig['color'] }}">
                            {{ ucfirst(str_replace('_', ' ', $notification->type)) }}
                        </span>
                        @if (!$notification->is_read)
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500
                                         flex-shrink-0"></span>
                        @endif
                    </div>

                    {{-- Message --}}
                    <p class="text-sm {{ $notification->is_read
                                            ? 'text-gray-500'
                                            : 'text-gray-800 font-medium' }}
                               leading-relaxed">
                        {{ $notification->message }}
                    </p>

                    {{-- Time --}}
                    <p class="text-xs text-gray-300 mt-1.5">
                        {{ $notification->created_at->diffForHumans() }}
                    </p>

                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-1.5 flex-shrink-0">

                    {{-- View related --}}
                    @php
                        $link = match($notification->related_type) {
                            'task'  => $notification->related_id
                                        ? route('tasks.show',  $notification->related_id)
                                        : null,
                            'habit' => $notification->related_id
                                        ? route('habits.show', $notification->related_id)
                                        : null,
                            'goal'  => $notification->related_id
                                        ? route('goals.show',  $notification->related_id)
                                        : null,
                            default => null,
                        };
                    @endphp

                    @if ($link)
                        <a href="{{ $link }}"
                           onclick="markRead({{ $notification->notification_id }}, event)"
                           class="text-xs font-semibold px-2.5 py-1.5 rounded-xl
                                  border transition-all"
                           style="border-color: {{ $typeConfig['border'] }};
                                  color: {{ $typeConfig['color'] }};
                                  background: {{ $typeConfig['bg'] }}">
                            View →
                        </a>
                    @endif

                    {{-- Mark as read --}}
                    @if (!$notification->is_read)
                        <form action="{{ route('notifications.read', $notification->notification_id) }}"
                              method="POST">
                            @csrf
                            <button type="submit"
                                    class="text-xs font-semibold px-2.5 py-1.5 rounded-xl
                                           border border-indigo-200 bg-indigo-50 text-indigo-600
                                           hover:bg-indigo-100 transition-colors">
                                ✓
                            </button>
                        </form>
                    @endif

                    {{-- Delete --}}
                    <button type="button"
                            onclick="deleteNotif({{ $notification->notification_id }})"
                            class="opacity-0 group-hover:opacity-100 w-7 h-7 rounded-xl
                                   flex items-center justify-center border border-gray-100
                                   text-gray-300 hover:text-red-500 hover:bg-red-50
                                   hover:border-red-200 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                </div>
            </div>

            {{-- Hidden delete form --}}
            <form id="delete-notif-{{ $notification->notification_id }}"
                  method="POST"
                  action="{{ route('notifications.destroy', $notification->notification_id) }}"
                  class="hidden">
                @csrf @method('DELETE')
            </form>

        </div>

    @empty

        {{-- Empty state --}}
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <div class="w-20 h-20 rounded-3xl bg-indigo-50 flex items-center
                        justify-center mb-5">
                <svg class="w-10 h-10 text-indigo-200" fill="none"
                     stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="1.5"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118
                             14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0
                             10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0
                             .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3
                             0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-700 mb-1">
                No notifications yet
            </h3>
            <p class="text-sm text-gray-400 max-w-xs">
                You'll receive reminders here for your tasks, habits and goals.
            </p>
        </div>

    @endforelse

    {{-- Hidden form for clear read --}}
    <form id="clear-read-form"
          method="POST"
          action="{{ route('notifications.clear-read') }}"
          class="hidden">
        @csrf @method('DELETE')
    </form>

</div>

<script>
// Mark single as read then redirect
function markRead(id, e) {
    // Let the link navigate normally,
    // but also submit the read form in background
    fetch(`/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
    });
}

// Delete single notification
function deleteNotif(id) {
    Swal.fire({
        title: 'Delete notification?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#e5e7eb',
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
    }).then(r => {
        if (r.isConfirmed) {
            document.getElementById(`delete-notif-${id}`).submit();
        }
    });
}

// Clear all read
function clearRead() {
    Swal.fire({
        title: 'Clear read notifications?',
        text: 'All read notifications will be deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#e5e7eb',
        confirmButtonText: 'Yes, clear',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
    }).then(r => {
        if (r.isConfirmed) {
            document.getElementById('clear-read-form').submit();
        }
    });
}
</script>

<style>
.swal2-popup { border-radius: 20px !important; }
</style>

</x-layouts.app>