<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habit Reminder</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
               background: #f9fafb; color: #111827; }
        .wrapper { max-width: 560px; margin: 40px auto; padding: 0 20px; }
        .card { background: white; border-radius: 16px; overflow: hidden;
                border: 1px solid #e5e7eb; }
        .top-bar { height: 6px; background: {{ $habit->color ?? '#6366f1' }}; }
        .body { padding: 32px; }
        .logo { font-size: 20px; font-weight: 700; color: #6366f1; margin-bottom: 24px; }
        .streak-box { display: flex; align-items: center; gap: 16px;
                      background: #fff7ed; border-radius: 12px; padding: 16px;
                      margin: 16px 0; }
        .streak-num { font-size: 40px; font-weight: 800; color: #ea580c; }
        .habit-box { background: #f9fafb; border-radius: 12px; padding: 20px;
                     margin: 16px 0;
                     border-left: 4px solid {{ $habit->color ?? '#6366f1' }}; }
        .habit-title { font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 6px; }
        .habit-meta { font-size: 13px; color: #6b7280; }
        .btn { display: inline-block; margin-top: 20px; padding: 12px 28px;
               background: {{ $habit->color ?? '#6366f1' }}; color: white;
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
            <p style="font-size: 15px; color: #374151;">
                Hey {{ $user->name }}, don't break the chain! 🔥
            </p>

            <div class="streak-box">
                <div class="streak-num">{{ $habit->streak }}</div>
                <div>
                    <p style="font-weight: 700; color: #111827;">Day streak</p>
                    <p style="font-size: 13px; color: #6b7280; margin-top: 2px;">
                        Keep it going — one more day!
                    </p>
                </div>
            </div>

            <div class="habit-box">
                <div class="habit-title">{{ $habit->name }}</div>
                @if ($habit->description)
                    <div class="habit-meta">{{ $habit->description }}</div>
                @endif
                <div class="habit-meta" style="margin-top: 8px;">
                    🔁 {{ ucfirst($habit->frequency) }}
                    &nbsp;·&nbsp;
                    📊 {{ $habit->completion_rate }}% last 30 days
                </div>
            </div>

            <a href="{{ route('habits.show', $habit->habit_id) }}" class="btn">
                Mark as Done →
            </a>
        </div>
    </div>
    <div class="footer">
        FocusLife · Stay focused, grow daily<br>
        You received this because you set a reminder for this habit.
    </div>
</div>
</body>
</html>