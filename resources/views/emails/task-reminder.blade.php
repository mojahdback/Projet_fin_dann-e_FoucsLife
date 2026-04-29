<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Reminder</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
               background: #f9fafb; color: #111827; }
        .wrapper { max-width: 560px; margin: 40px auto; padding: 0 20px; }
        .card { background: white; border-radius: 16px; overflow: hidden;
                border: 1px solid #e5e7eb; }
        .top-bar { height: 6px; background: {{ $task->goal?->color ?? '#6366f1' }}; }
        .body { padding: 32px; }
        .logo { font-size: 20px; font-weight: 700; color: #6366f1; margin-bottom: 24px; }
        .greeting { font-size: 15px; color: #374151; margin-bottom: 8px; }
        .task-box { background: #f9fafb; border-radius: 12px; padding: 20px;
                    margin: 20px 0; border-left: 4px solid {{ $task->goal?->color ?? '#6366f1' }}; }
        .task-title { font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 8px; }
        .task-meta { font-size: 13px; color: #6b7280; margin-bottom: 4px; }
        .goal-badge { display: inline-block; font-size: 12px; font-weight: 600;
                      padding: 4px 12px; border-radius: 20px; margin-top: 10px;
                      background: {{ \App\Helpers\ColorPalette::bg($task->goal?->color ?? '#6366f1') }};
                      color: {{ \App\Helpers\ColorPalette::text($task->goal?->color ?? '#6366f1') }}; }
        .btn { display: inline-block; margin-top: 24px; padding: 12px 28px;
               background: {{ $task->goal?->color ?? '#6366f1' }}; color: white;
               text-decoration: none; border-radius: 10px; font-weight: 600;
               font-size: 14px; }
        .footer { margin-top: 32px; font-size: 12px; color: #9ca3af;
                  text-align: center; padding-bottom: 20px; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="card">
        <div class="top-bar"></div>
        <div class="body">
            <div class="logo">FocusLife</div>
            <p class="greeting">Hey {{ $user->name }}, time to focus! 👋</p>
            <p style="font-size: 14px; color: #6b7280; margin-top: 4px;">
                You have a task scheduled for today.
            </p>

            <div class="task-box">
                <div class="task-title">{{ $task->title }}</div>
                @if ($task->description)
                    <div class="task-meta">{{ $task->description }}</div>
                @endif
                <div class="task-meta" style="margin-top: 8px;">
                    📅 Scheduled:
                    {{ $task->scheduled_date?->format('M j, Y') }}
                    @if ($task->scheduled_time)
                        at {{ \Carbon\Carbon::parse($task->scheduled_time)->format('H:i') }}
                    @endif
                </div>
                <div class="task-meta">
                    🎯 Priority: {{ ucfirst($task->priority) }}
                </div>
                @if ($task->goal)
                    <div class="goal-badge">{{ $task->goal->title }}</div>
                @endif
            </div>

            <a href="{{ route('tasks.show', $task->task_id) }}" class="btn">
                View Task →
            </a>
        </div>
    </div>
    <div class="footer">
        FocusLife · Stay focused, grow daily<br>
        You received this because you set a reminder for this task.
    </div>
</div>
</body>
</html>